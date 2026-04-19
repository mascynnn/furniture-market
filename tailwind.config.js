import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                wood: {
                    DEFAULT: '#8B5E3C',
                    light: '#F5F5DC',
                    surface: '#FFFFFF',
                    text: '#333333',
                    soft: '#EDE4DA',
                },
            },
            boxShadow: {
                soft: '0 18px 45px rgba(51, 51, 51, 0.08)',
                card: '0 16px 32px rgba(139, 94, 60, 0.08)',
            },
        },
    },

    plugins: [forms],
};
