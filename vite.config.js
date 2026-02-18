import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@phosphor-icons/web/regular': path.resolve(__dirname, 'node_modules/@phosphor-icons/web/src/regular/style.css'),
            '@phosphor-icons/web/bold': path.resolve(__dirname, 'node_modules/@phosphor-icons/web/src/bold/style.css'),
            '@phosphor-icons/web/fill': path.resolve(__dirname, 'node_modules/@phosphor-icons/web/src/fill/style.css'),
        }
    }
});
