<?php

namespace App\Livewire\Dashboard\Sales;

use App\Enums\UserRoles;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Models\VoidedSale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.dashboard')]
class VoidSales extends Component
{
    use WithPagination;

    public string $prfSearch = '';

    public ?Sale $sale = null;

    public bool $showPrfNotFound = false;

    public bool $showAuthModal = false;

    #[Validate('required|email')]
    public string $authEmail = '';

    #[Validate('required')]
    public string $authPassword = '';

    public ?int $authorizedAdminId = null;

    public bool $isEditing = false;

    public array $editItems = [];

    public bool $showVoidConfirm = false;

    public string $voidReason = '';

    public ?VoidedSale $selectedVoidedSale = null;

    public bool $showVoidedDetailsModal = false;

    public function updatedPrfSearch(string $value): void
    {
        $this->reset('sale', 'showPrfNotFound', 'authorizedAdminId', 'isEditing', 'editItems', 'showVoidConfirm', 'voidReason');
        $this->resetValidation();

        $value = trim($value);

        if (strlen($value) < 4) {
            return;
        }

        $this->sale = Sale::query()
            ->with(['salesItem.product.unit', 'salesItem.product.category', 'user'])
            ->where('prf_number', 'like', "%{$value}%")
            ->latest('id')
            ->first();

        if (! $this->sale) {
            $this->showPrfNotFound = true;

            return;
        }
    }

    public function resetPrfSearch(): void
    {
        $this->reset('prfSearch', 'sale', 'showPrfNotFound', 'authorizedAdminId', 'isEditing', 'editItems', 'showVoidConfirm', 'voidReason');
        $this->resetValidation();
    }

    public function openAuthModal(): void
    {
        $this->showAuthModal = true;
        $this->reset('authEmail', 'authPassword');
        $this->resetValidation();
    }

    public function authenticateAdmin(): void
    {
        $this->validate([
            'authEmail' => 'required|email',
            'authPassword' => 'required',
        ]);

        $admin = User::where('email', $this->authEmail)
            ->where('user_role', UserRoles::ADMIN->value)
            ->first();

        if (! $admin || ! Hash::check($this->authPassword, $admin->password)) {
            $this->addError('authPassword', 'Invalid admin credentials.');

            return;
        }

        $this->authorizedAdminId = $admin->id;
        $this->showAuthModal = false;
        $this->authEmail = '';
        $this->authPassword = '';
        $this->resetValidation();
    }

    public function startEditing(): void
    {
        if (! $this->authorizedAdminId) {
            $this->openAuthModal();

            return;
        }

        $this->isEditing = true;
        $this->editItems = $this->sale->salesItem
            ->map(fn ($item) => [
                'id' => $item->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name,
                'product_unit' => $item->product?->unit?->unit_name,
                'category' => strtolower($item->product?->category?->category_name ?? ''),
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'inventory_start' => $item->inventory_start,
                'inventory_end' => $item->inventory_end,
                'subtotal' => $item->subtotal,
            ])
            ->toArray();
    }

    public function cancelEditing(): void
    {
        $this->isEditing = false;
        $this->editItems = [];
    }

    public function isLivestockOrPoultry(int $index): bool
    {
        if (! isset($this->editItems[$index])) {
            return false;
        }

        return in_array($this->editItems[$index]['category'], ['livestock', 'poultry'], true);
    }

    public function updateEditQuantity(int $index, int $value): void
    {
        if ($value < 1 || ! isset($this->editItems[$index])) {
            return;
        }

        $item = $this->editItems[$index];
        $product = Product::find($item['product_id']);

        if (! $product) {
            return;
        }

        $originalQuantity = $item['quantity'];
        $availableStock = $product->stock_level + $originalQuantity;

        if ($value > $availableStock) {
            $this->addError("editItems.{$index}.quantity", "Quantity cannot exceed available stock ({$availableStock}).");

            return;
        }

        $this->editItems[$index]['quantity'] = $value;

        if ($this->isLivestockOrPoultry($index)) {
            $this->editItems[$index]['subtotal'] = $item['unit_price'];
        } else {
            $this->editItems[$index]['subtotal'] = $value * $item['unit_price'];
        }

        $this->resetValidation();
    }

    public function updateEditPrice(int $index, int $value): void
    {
        if ($value < 1 || ! isset($this->editItems[$index]) || ! $this->isLivestockOrPoultry($index)) {
            return;
        }

        $this->editItems[$index]['unit_price'] = $value;
        $this->editItems[$index]['subtotal'] = $value;
        $this->resetValidation();
    }

    public function removeEditItem(int $index): void
    {
        unset($this->editItems[$index]);
        $this->editItems = array_values($this->editItems);
    }

    public function saveEdit(): void
    {
        if (! $this->authorizedAdminId || ! $this->sale) {
            return;
        }

        if (empty($this->editItems)) {
            $this->voidSale();

            return;
        }

        DB::transaction(function () {
            $originalItems = $this->sale->salesItem
                ->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name ?? 'Unknown',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'inventory_start' => $item->inventory_start,
                    'inventory_end' => $item->inventory_end,
                ])
                ->toArray();

            $originalTotal = $this->sale->total_amount;

            // Calculate new total and update inventory
            $newTotal = 0;
            foreach ($this->editItems as $item) {
                $newTotal += $item['subtotal'];
            }

            // Update product stock levels
            foreach ($this->sale->salesItem as $originalItem) {
                $editedItem = collect($this->editItems)->firstWhere('id', $originalItem->id);

                if (! $editedItem) {
                    // Item was removed — add back full quantity to inventory
                    $product = Product::find($originalItem->product_id);
                    if ($product) {
                        $product->increment('stock_level', $originalItem->quantity);
                    }
                } else {
                    // Quantity changed — adjust inventory
                    $quantityDiff = $originalItem->quantity - $editedItem['quantity'];
                    if ($quantityDiff !== 0) {
                        $product = Product::find($originalItem->product_id);
                        if ($product) {
                            $product->increment('stock_level', $quantityDiff);
                        }
                    }
                }
            }

            // Delete original items
            $this->sale->salesItem()->delete();

            // Create new items for database (without product_name)
            $newDbItems = collect($this->editItems)->map(fn ($item) => [
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
                'inventory_start' => $item['inventory_start'],
                'inventory_end' => $item['inventory_start'] - $item['quantity'],
            ])->toArray();

            $this->sale->salesItem()->createMany($newDbItems);

            // Update sale total
            $this->sale->update(['total_amount' => $newTotal]);

            // Build modified items for JSON record (with product_name)
            $newItemsForRecord = collect($this->editItems)->map(fn ($item) => [
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'] ?? Product::find($item['product_id'])?->name ?? 'Unknown',
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['subtotal'],
                'inventory_start' => $item['inventory_start'],
                'inventory_end' => $item['inventory_start'] - $item['quantity'],
            ])->toArray();

            // Record in voided_sales
            VoidedSale::create([
                'original_sale_id' => $this->sale->id,
                'prf_number' => $this->sale->prf_number,
                'original_total_amount' => $originalTotal,
                'modified_total_amount' => $newTotal,
                'action' => 'modified',
                'authorized_by' => $this->authorizedAdminId,
                'original_cashier_id' => $this->sale->user_id,
                'original_items' => $originalItems,
                'modified_items' => $newItemsForRecord,
                'reason' => null,
                'voided_at' => now(),
            ]);

            // Reset auth
            $this->reset('authorizedAdminId', 'isEditing', 'editItems');
            $this->sale = Sale::with(['salesItem.product.unit', 'user'])->find($this->sale->id);
        });
    }

    public function confirmVoid(): void
    {
        if (! $this->authorizedAdminId) {
            $this->openAuthModal();

            return;
        }

        $this->showVoidConfirm = true;
    }

    public function voidSale(): void
    {
        if (! $this->authorizedAdminId || ! $this->sale) {
            return;
        }

        DB::transaction(function () {
            $originalItems = $this->sale->salesItem
                ->map(fn ($item) => [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product?->name ?? 'Unknown',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->subtotal,
                    'inventory_start' => $item->inventory_start,
                    'inventory_end' => $item->inventory_end,
                ])
                ->toArray();

            // Restore inventory for all items
            foreach ($this->sale->salesItem as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock_level', $item->quantity);
                }
            }

            // Record in voided_sales
            VoidedSale::create([
                'original_sale_id' => $this->sale->id,
                'prf_number' => $this->sale->prf_number,
                'original_total_amount' => $this->sale->total_amount,
                'modified_total_amount' => null,
                'action' => 'voided',
                'authorized_by' => $this->authorizedAdminId,
                'original_cashier_id' => $this->sale->user_id,
                'original_items' => $originalItems,
                'modified_items' => null,
                'reason' => $this->voidReason ?: null,
                'voided_at' => now(),
            ]);

            // Delete sales items first, then delete the sale
            $this->sale->salesItem()->delete();
            $this->sale->delete();

            $this->reset('sale', 'authorizedAdminId', 'showVoidConfirm', 'voidReason', 'isEditing', 'editItems');
        });
    }

    public function cancelVoid(): void
    {
        $this->showVoidConfirm = false;
        $this->voidReason = '';
    }

    public function showVoidedDetails(int $id): void
    {
        $this->selectedVoidedSale = VoidedSale::query()
            ->with(['authorizedBy', 'originalCashier', 'originalSale'])
            ->find($id);

        $this->showVoidedDetailsModal = true;
    }

    public function closeVoidedDetails(): void
    {
        $this->showVoidedDetailsModal = false;
        $this->selectedVoidedSale = null;
    }

    public function render()
    {
        $voidedSales = VoidedSale::query()
            ->with(['authorizedBy', 'originalCashier'])
            ->latest('voided_at')
            ->paginate(10);

        return view('livewire.dashboard.sales.void-sales', [
            'voidedSales' => $voidedSales,
        ]);
    }
}
