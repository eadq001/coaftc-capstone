<div class="space-y-8">
    <div class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
        <div class="border-b border-emerald-100 bg-linear-to-r from-emerald-50 via-white to-emerald-100/70 px-6 py-5 sm:px-8">
            <flux:heading size="lg">Void / Modify Sales</flux:heading>
            <flux:text class="mt-1">Search a PRF number to void or modify a transaction.</flux:text>
        </div>

        <div class="px-6 py-5 sm:px-8">
            <flux:field>
                <flux:label>PRF Number</flux:label>
                <flux:input
                    icon="magnifying-glass"
                    placeholder="PRF26-000001"
                    autocomplete="off"
                    wire:model.live.debounce.400ms="prfSearch"
                />
            </flux:field>

            <div wire:loading wire:target="prfSearch" class="mt-3 text-sm text-zinc-500">
                Searching...
            </div>

            @if($showPrfNotFound)
                <div class="mt-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    No sale found for "{{ $prfSearch }}".
                </div>
            @endif

            @if($sale)
                <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200">
                    <div class="grid gap-3 border-b border-zinc-200 bg-zinc-50 px-4 py-3 text-sm sm:grid-cols-2">
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">PRF No.</p>
                            <p class="font-semibold text-zinc-900">{{ $sale->prf_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Associate</p>
                            <p class="font-semibold text-zinc-900">{{ $sale->user?->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Date</p>
                            <p class="font-semibold text-zinc-900">{{ $sale->created_at?->format('d/m/Y g:i A') ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.18em] text-zinc-500">Total</p>
                            <p class="font-semibold text-zinc-900">₱{{ number_format($sale->total_amount, 2) }}</p>
                        </div>
                    </div>

                    <div class="max-h-96 divide-y divide-zinc-200 overflow-y-auto">
                        @if($isEditing)
                            @foreach($editItems as $index => $item)
                                <div class="grid grid-cols-[minmax(0,1fr)_100px_100px_110px_100px] gap-3 px-4 py-3 text-sm items-center" wire:key="edit-item-{{ $item['id'] }}">
                                    <div>
                                        <p class="font-medium text-zinc-900">{{ $item['product_name'] ?? 'Unknown' }}</p>
                                        <p class="text-xs text-zinc-500">{{ $item['product_unit'] ?? 'unit' }}</p>
                                    </div>
                                    <div>
                                        <flux:input
                                            type="number"
                                            size="sm"
                                            min="1"
                                            wire:change="updateEditQuantity({{ $index }}, $event.target.value)"
                                            value="{{ $item['quantity'] }}"
                                        />
                                        <flux:error name="editItems.{{ $index }}.quantity" />
                                    </div>
                                    <div class="text-right">
                                        @if(in_array($item['category'], ['livestock', 'poultry']))
                                            <flux:input
                                                type="number"
                                                size="sm"
                                                min="1"
                                                wire:change="updateEditPrice({{ $index }}, $event.target.value)"
                                                value="{{ $item['unit_price'] }}"
                                            />
                                        @else
                                            <p class="text-zinc-700">₱{{ number_format($item['unit_price'], 2) }}</p>
                                        @endif
                                    </div>
                                    <p class="text-right font-semibold text-zinc-900">
                                        @if(in_array($item['category'], ['livestock', 'poultry']))
                                            ₱{{ number_format($item['unit_price'], 2) }}
                                        @else
                                            ₱{{ number_format($item['subtotal'], 2) }}
                                        @endif
                                    </p>
                                    <div class="text-right">
                                        <flux:button variant="danger" size="xs" wire:click="removeEditItem({{ $index }})">Remove</flux:button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach($sale->salesItem as $item)
                                <div class="grid grid-cols-[minmax(0,1fr)_90px_110px_110px] gap-3 px-4 py-3 text-sm" wire:key="sale-item-{{ $item->id }}">
                                    <div>
                                        <p class="font-medium text-zinc-900">{{ $item->product?->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-zinc-500">{{ $item->product?->unit?->unit_name ?? 'unit' }}</p>
                                    </div>
                                    <p class="text-right text-zinc-700">{{ $item->quantity }}</p>
                                    <p class="text-right text-zinc-700">₱{{ number_format($item->unit_price, 2) }}</p>
                                    <p class="text-right font-semibold text-zinc-900">
                                        @if(in_array(strtolower($item->product?->category?->category_name ?? ''), ['livestock', 'poultry']))
                                            ₱{{ number_format($item->unit_price, 2) }}
                                        @else
                                            ₱{{ number_format($item->subtotal, 2) }}
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="mt-4 flex gap-2">
                    @if($isEditing)
                        <flux:button variant="primary" wire:click="saveEdit">Save Changes</flux:button>
                        <flux:button variant="ghost" wire:click="cancelEditing">Cancel</flux:button>
                    @else
                        <flux:button variant="primary" wire:click="startEditing">Edit Sale</flux:button>
                        <flux:button variant="danger" wire:click="confirmVoid">Void Sale</flux:button>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($voidedSales->count() > 0)
        <div class="overflow-hidden rounded-[2rem] border border-emerald-200 bg-white shadow-[0_24px_80px_rgba(15,23,42,0.10)]">
            <div class="border-b border-emerald-100 bg-linear-to-r from-emerald-50 via-white to-emerald-100/70 px-6 py-5 sm:px-8">
                <flux:heading size="lg">Voided / Modified Transactions</flux:heading>
            </div>

            <div class="px-6 py-5 sm:px-8">
                <div class="overflow-hidden rounded-xl border border-zinc-200">
                    <div class="grid grid-cols-[minmax(0,1fr)_120px_120px_140px_140px_140px_160px] border-b border-zinc-200 bg-zinc-50 text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">
                        <div class="px-4 py-3">PRF Number</div>
                        <div class="px-4 py-3">Action</div>
                        <div class="px-4 py-3">Original</div>
                        <div class="px-4 py-3">Modified</div>
                        <div class="px-4 py-3">Cashier</div>
                        <div class="px-4 py-3">Authorized By</div>
                        <div class="px-4 py-3">Date</div>
                    </div>

                    <div class="divide-y divide-zinc-200">
                        @foreach($voidedSales as $voided)
                            <div
                                wire:key="voided-{{ $voided->id }}"
                                wire:click="showVoidedDetails({{ $voided->id }})"
                                class="grid grid-cols-[minmax(0,1fr)_120px_120px_140px_140px_140px_160px] bg-white text-sm text-zinc-700 transition hover:bg-emerald-50/60 cursor-pointer"
                            >
                                <div class="px-4 py-4">
                                    <p class="font-semibold text-zinc-900">{{ $voided->prf_number }}</p>
                                    @if($voided->reason)
                                        <p class="mt-1 text-xs text-zinc-500">{{ $voided->reason }}</p>
                                    @endif
                                </div>
                                <div class="px-4 py-4">
                                    <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $voided->action === 'voided' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ ucfirst($voided->action) }}
                                    </span>
                                </div>
                                <div class="px-4 py-4">₱{{ number_format($voided->original_total_amount, 2) }}</div>
                                <div class="px-4 py-4">{{ $voided->modified_total_amount ? '₱'.number_format($voided->modified_total_amount, 2) : '-' }}</div>
                                <div class="px-4 py-4">{{ $voided->originalCashier?->name ?? 'Unknown' }}</div>
                                <div class="px-4 py-4">{{ $voided->authorizedBy?->name ?? 'Unknown' }}</div>
                                <div class="px-4 py-4 text-xs text-zinc-500">{{ $voided->voided_at?->format('d/m/Y g:i A') ?? 'Unknown' }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4">
                    {{ $voidedSales->links() }}
                </div>
            </div>
        </div>
    @endif

    <flux:modal name="auth-admin" class="min-w-[22rem]" wire:model="showAuthModal">
        <div class="space-y-6 text-left">
            <div>
                <flux:heading size="lg">Admin Authorization</flux:heading>
                <flux:text class="mt-2">An administrator must authenticate to void or modify this sale.</flux:text>
            </div>

            <flux:field>
                <flux:label>Email</flux:label>
                <flux:input type="email" wire:model="authEmail" placeholder="admin@example.com" />
                <flux:error name="authEmail" />
            </flux:field>

            <flux:field>
                <flux:label>Password</flux:label>
                <flux:input type="password" wire:model="authPassword" placeholder="Password" />
                <flux:error name="authPassword" />
            </flux:field>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>

                <flux:button variant="primary" wire:click="authenticateAdmin">Authenticate</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="void-confirm" class="min-w-[22rem]" wire:model="showVoidConfirm">
        <div class="space-y-6 text-left">
            <div>
                <flux:heading size="lg">Void Sale</flux:heading>
                <flux:text class="mt-2">Are you sure you want to void this sale? This action cannot be undone.</flux:text>
            </div>

            <flux:field>
                <flux:label>Reason (optional)</flux:label>
                <flux:input type="text" wire:model="voidReason" placeholder="Enter reason..." />
            </flux:field>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:button variant="ghost" wire:click="cancelVoid">Cancel</flux:button>

                <flux:button variant="danger" wire:click="voidSale">Void Sale</flux:button>
            </div>
        </div>
    </flux:modal>

    <flux:modal name="voided-details" class="min-w-[40rem] max-w-[50rem]" wire:model="showVoidedDetailsModal">
        @if($selectedVoidedSale)
            <div class="space-y-6 text-left">
                <div>
                    <flux:heading size="lg">{{ $selectedVoidedSale->prf_number }}</flux:heading>
                    <div class="mt-2 flex flex-wrap gap-3 text-sm">
                        <span class="inline-flex items-center rounded-full px-2 py-1 text-xs font-medium {{ $selectedVoidedSale->action === 'voided' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($selectedVoidedSale->action) }}
                        </span>
                        <span class="text-zinc-500">Cashier: <span class="font-medium text-zinc-900">{{ $selectedVoidedSale->originalCashier?->name ?? 'Unknown' }}</span></span>
                        <span class="text-zinc-500">Authorized By: <span class="font-medium text-zinc-900">{{ $selectedVoidedSale->authorizedBy?->name ?? 'Unknown' }}</span></span>
                        <span class="text-zinc-500">Date: <span class="font-medium text-zinc-900">{{ $selectedVoidedSale->voided_at?->format('d/m/Y g:i A') ?? 'Unknown' }}</span></span>
                    </div>
                    @if($selectedVoidedSale->reason)
                        <p class="mt-2 text-sm text-zinc-600"><span class="font-medium">Reason:</span> {{ $selectedVoidedSale->reason }}</p>
                    @endif
                </div>

                <div class="overflow-hidden rounded-xl border border-zinc-200">
                    <div class="border-b border-zinc-200 bg-zinc-50 px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">
                        Original Items — Total: ₱{{ number_format($selectedVoidedSale->original_total_amount, 2) }}
                    </div>
                    <div class="max-h-64 divide-y divide-zinc-200 overflow-y-auto">
                        @foreach($selectedVoidedSale->original_items as $item)
                            <div class="grid grid-cols-[minmax(0,1fr)_90px_110px_110px] gap-3 px-4 py-3 text-sm" wire:key="original-item-{{ $loop->index }}">
                                <div>
                                    <p class="font-medium text-zinc-900">{{ $item['product_name'] ?? 'Unknown' }}</p>
                                    <p class="text-xs text-zinc-500">ID: {{ $item['product_id'] ?? '-' }}</p>
                                </div>
                                <p class="text-right text-zinc-700">{{ $item['quantity'] }}</p>
                                <p class="text-right text-zinc-700">₱{{ number_format($item['unit_price'], 2) }}</p>
                                <p class="text-right font-semibold text-zinc-900">₱{{ number_format($item['subtotal'], 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($selectedVoidedSale->action === 'modified' && $selectedVoidedSale->modified_items)
                    <div class="overflow-hidden rounded-xl border border-zinc-200">
                        <div class="border-b border-zinc-200 bg-zinc-50 px-4 py-3 text-xs font-semibold uppercase tracking-[0.22em] text-zinc-500">
                            Modified Items — Total: ₱{{ number_format($selectedVoidedSale->modified_total_amount, 2) }}
                        </div>
                        <div class="max-h-64 divide-y divide-zinc-200 overflow-y-auto">
                            @foreach($selectedVoidedSale->modified_items as $item)
                                <div class="grid grid-cols-[minmax(0,1fr)_90px_110px_110px] gap-3 px-4 py-3 text-sm" wire:key="modified-item-{{ $loop->index }}">
                                    <div>
                                        <p class="font-medium text-zinc-900">{{ $item['product_name'] ?? 'Unknown' }}</p>
                                        <p class="text-xs text-zinc-500">ID: {{ $item['product_id'] ?? '-' }}</p>
                                    </div>
                                    <p class="text-right text-zinc-700">{{ $item['quantity'] }}</p>
                                    <p class="text-right text-zinc-700">₱{{ number_format($item['unit_price'], 2) }}</p>
                                    <p class="text-right font-semibold text-zinc-900">₱{{ number_format($item['subtotal'], 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost" wire:click="closeVoidedDetails">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        @endif
    </flux:modal>
</div>
