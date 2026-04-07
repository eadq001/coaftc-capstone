@props([
    'type' => 'text',
    'name' => 'name',
    'placeholder' => '',
    'inputClass' => '',
    'autocomplete' => 'on',
])

<flux:field>
    <flux:label>{{ ucfirst($name) }}</flux:label>
    <flux:input
        {{ $attributes->whereStartsWith('wire:model') }}
        type="{{$type}}"
        name="{{ $name }}"
        value="{{ old($name) }}"
        placeholder="{{ $placeholder }}"
        autofocus
        autocomplete="{{ $autocomplete }}"
        input:class="{{ $inputClass }}"


    />
    <flux:error name="{{ $name }}"/>
</flux:field>
