@props(["value" => "", "name" => $attributes->whereStartsWith("wire:model")->first(), "label" => "", "label_show" => true, "disabled" => false, "required" => false, "options" => "", "placeholder" => "-- Select an option --"])

<?php
$field_name = $name;
$field_lable = $label == "" ? label_case($field_name) : $label;
$field_placeholder = $placeholder == "" ? label_case($field_lable) : $placeholder;
?>

<div class="group w-full">
    @if ($label_show)
        {{ html()->label($field_lable, $field_name)->class("block-inline text-sm font-semibold tracking-widest text-gray-700") }}
        {!! field_required($required) !!}
    @endif

    <select
        wire:model="{{ $field_name }}"
        {{ $disabled ? "disabled" : "" }}
        {{ $required ? "required" : "" }}
        {!! $attributes->merge(["class" => "soh-input"]) !!}
    >
        <option value="">{{ $field_placeholder }}</option>
        @foreach ($options as $k => $v)
            <option value="{{ $k }}" {{ $value == $k ? "selected" : "" }}>{{ $v }}</option>
        @endforeach
    </select>

    @foreach ($errors->get($field_name) as $message)
        <div class="mt-2 text-sm text-red-600 dark:text-red-500">
            <span class="font-medium"><i class="fa fa-exclamation-triangle"></i></span>
            {{ $message }}
        </div>
    @endforeach
</div>
