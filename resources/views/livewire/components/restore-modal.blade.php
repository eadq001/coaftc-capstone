<flux:modal name="{{ strtolower($modelName) . '-' . $id}}" class="min-w-[22rem]">
    <div class="space-y-6 text-left">
        <div>
            <flux:heading size="lg">Restore product?</flux:heading>
            <flux:text class="mt-2">
                Do you want to restore "{{$itemName}}" to the products inventory
            </flux:text>
        </div>

        <div class="flex gap-2">
            <flux:spacer/>

            <flux:modal.close>
                <flux:button variant="ghost">No</flux:button>
            </flux:modal.close>

            <flux:modal.close>
                <flux:button variant="primary" color="emerald"
                             wire:click.stop="restoreDeletedItem">
                    Yes
                </flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
