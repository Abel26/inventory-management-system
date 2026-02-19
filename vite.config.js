import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/landing.css', 
                'resources/css/error.css',
                'resources/css/phosphor-icons.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: process.env.NODE_ENV === 'production',
                drop_debugger: process.env.NODE_ENV === 'production'
            }
        }
    }
});
