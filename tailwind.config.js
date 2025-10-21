import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
   
    safelist: [
        'bg-purple-500',
        'bg-green-500',
        'bg-red-500',
        'bg-gray-100',
        'text-white',
        'text-gray-800',
        'bg-brown-500',
        'bg-brown-600',
        'bg-brown-700',
        'text-brown-700',
        'text-brown-800',
    ],
    
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brown: {
                    50: '#f9f5f2',
                    100: '#f3ebe3',
                    200: '#e5d5c8',
                    300: '#d7bfae',
                    400: '#c9a993',
                    500: '#bb9379',
                    600: '#ad7d5f',
                    700: '#9f6745',
                    800: '#914e2b',
                    900: '#823612',
                },
            },
        },
    },

    plugins: [forms],
};