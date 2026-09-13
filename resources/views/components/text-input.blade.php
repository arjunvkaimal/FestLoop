@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:ring-indigo-400 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm text-sm']) }}>
