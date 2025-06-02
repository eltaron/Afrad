import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Ensure dark mode is enabled via class strategy
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                themeBlue: {
                  '50': '#eff6ff',
                  '100': '#dbeafe',
                  '200': '#bfdbfe',
                  '300': '#93c5fd',
                  '400': '#60a5fa',
                  '500': '#3b82f6', // Primary accent
                  '600': '#2563eb',
                  '700': '#1d4ed8',
                  '800': '#1e40af', // Darker for elements
                  '900': '#1e3a8a', // Dark for backgrounds
                  '950': '#172554', // Deepest dark for main background
                },
              }
        },
    },

    plugins: [forms],
};
