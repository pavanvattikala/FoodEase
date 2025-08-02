@props(['active', 'openInNewTab' => false])

@php
    $classes = $active
        ? 'block px-4 py-2 mt-2 text-sm font-semibold text-gray-700 bg-gray-200 rounded-lg hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline'
        : 'block px-4 py-2 mt-2 text-sm font-semibold text-gray-100 bg-transparent rounded-lg  hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} @if ($openInNewTab) @target @endif>
    {{ $slot }}
</a>
