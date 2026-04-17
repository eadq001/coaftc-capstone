<div class="w-full">
    <form wire:submit="save">

    <flux:input label="name" wire:model="name"/>
    <flux:input type="email" label="email" wire:model="email"/>
    <flux:input type="password" label="password" wire:model="password"/>
        <button>
            save
        </button>
    </form>
</div>
