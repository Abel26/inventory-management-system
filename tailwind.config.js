import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',

    theme: {
        extend: {
            // Ebara Brand Colors
            colors: {
                // Primary - Ebara Teal
                ebara: {
                    50: '#E6F7F3',
                    100: '#CCEEDB',
                    200: '#9FE2C3',
                    300: '#70D7AB',
                    400: '#41CC93',
                    500: '#009B77',
                    600: '#008065',
                    700: '#006653',
                    800: '#004C40',
                    900: '#00332D',
                    950: '#001A16',
                },
                
                // Secondary - Dark Slate
                slate: {
                    850: '#1A202C',
                    900: '#111827',
                    950: '#0B0F19',
                },
                
                // Background colors
                bg: {
                    primary: '#F3F4F6',
                    secondary: '#FFFFFF',
                    dark: '#1F2937',
                },
                
                // Text colors
                text: {
                    primary: '#111827',
                    secondary: '#6B7280',
                    light: '#9CA3AF',
                },
            },

            // Typography - Inter Font
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['JetBrains Mono', ...defaultTheme.fontFamily.mono],
            },

            // Border radius
            borderRadius: {
                '4xl': '2rem',
            },

            // Box shadows
            boxShadow: {
                'ebara': '0 4px 6px -1px rgba(0, 155, 119, 0.1), 0 2px 4px -1px rgba(0, 155, 119, 0.06)',
                'ebara-lg': '0 10px 15px -3px rgba(0, 155, 119, 0.1), 0 4px 6px -2px rgba(0, 155, 119, 0.05)',
                'sidebar': '4px 0 24px rgba(0, 0, 0, 0.1)',
            },

            // Transitions
            transitionProperty: {
                'height': 'height',
                'spacing': 'margin, padding',
            },

            // Spacing
            spacing: {
                '4.5': '1.125rem',
                '5.5': '1.375rem',
                '13': '3.25rem',
                '15': '3.75rem',
                '17': '4.25rem',
                '18': '4.5rem',
                '21': '5.25rem',
                '22': '5.5rem',
                '25': '6.25rem',
                '26': '6.5rem',
                '28': '7rem',
                '72': '18rem',
                '80': '20rem',
                '84': '21rem',
                '96': '24rem',
            },

            // Z-index layers
            zIndex: {
                '60': '60',
                '70': '70',
                '80': '80',
                '90': '90',
                '100': '100',
            },

            // Animation
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-in': 'slideIn 0.3s ease-out',
                'slide-out': 'slideOut 0.3s ease-in',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideIn: {
                    '0%': { transform: 'translateX(-100%)' },
                    '100%': { transform: 'translateX(0)' },
                },
                slideOut: {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-100%)' },
                },
            },
        },
    },

    plugins: [
        forms,
    ],
};
