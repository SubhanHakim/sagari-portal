@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-lime-400 text-sm font-medium leading-5 text-gray-900 dark:text-lime-300 focus:outline-none focus:border-lime-500 transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-lime-300 hover:border-gray-300 dark:hover:border-lime-400 focus:outline-none focus:text-gray-700 dark:focus:text-lime-300 focus:border-gray-300 dark:focus:border-lime-400 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>