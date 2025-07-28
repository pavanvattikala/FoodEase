@props(['active'])

@php
    $classes =
        $active ?? false
            ? ' m-2 px-4 py-2 mt-2 text-sm text-grey-900 font-semibold bg-gray-200 rounded-lg hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline'
            : 'm-2 px-4 py-2 mt-2 text-sm text-white font-semibold bg-transparent rounded-lg 0 hover:text-gray-900 focus:text-gray-900 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none focus:shadow-outline';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
