@props([
    "value" => "",
    "name" => $attributes->whereStartsWith("wire:model")->first(),
    "disabled" => false,
    "required" => false,
    "checked" => false,
    "label",
])

<?php
$field_name = $name;
$field_lable = $label == "" ? label_case($field_name) : $label;
?>

<fieldset>
    <legend class="sr-only">Checkbox</legend>

    <div class="flex items-center">
        <input
            wire:model="{{ $field_name }}"
            id="{{ $field_name }}"
            type="checkbox"
            value=""
            class="h-4 w-4 rounded-sm border-gray-300 bg-white text-[#A6128D] focus:ring-2 focus:ring-[#A6128D]"
            {{ $disabled ? "disabled" : "" }}
            {{ $required ? "required" : "" }}
            {{ $attributes->merge(["wire:model" => $name]) }}
            {{ $checked ? "checked" : "" }}
        />
        <label for="{{ $field_name }}" class="ms-2 text-sm font-semibold tracking-widest text-gray-900">
            {{ $label }}
        </label>
    </div>
</fieldset>
