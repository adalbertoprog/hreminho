import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    build: {
        emptyOutDir: false,
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/employees.js',
                'resources/js/pages/trainings.js',
                'resources/js/pages/reports.js',
                'resources/js/pages/projects.js',
                'resources/js/pages/calendar.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
