@props(['disabled' => false])

@php
    $isPassword = ($attributes->get('type') ?? '') === 'password';
@endphp

@if($isPassword)
    <div class="input-group flex w-full rounded-md border border-gray-300" x-data="{ visible: false }">
        <input
            x-bind:type="visible ? 'text' : 'password'"
            @disabled($disabled)
            {{ $attributes->except('type')->merge(['class' => 'rounded-l-md focus:border-indigo-500 focus:ring-indigo-500 flex-1 min-w-0 py-2 px-3']) }}
        >
        <div class="input-group-addon flex items-center self-stretch border-l border-transparent rounded-r-md px-3 py-2 bg-gray-100">
            <a href="#" type="button" tabindex="-1" aria-label="{{ __('Toggle password visibility') }}" @click.prevent="visible = !visible" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                <i class="fas fa-eye" x-show="!visible" x-cloak aria-hidden="true"></i>
                <i class="fas fa-eye-slash" x-show="visible" x-cloak aria-hidden="true"></i>
            </a>
        </div>
    </div>
@else
    <input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) }}>
@endif
