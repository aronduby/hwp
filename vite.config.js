import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fileURLToPath } from 'url';
import path from 'path';
import { google, local } from 'laravel-vite-plugin/fonts';
// import tailwindcss from '@tailwindcss/vite';

// Workaround for __dirname in ESM environments
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // region SASS Files
                'resources/sass/main.scss',
                'resources/sass/picker.scss',
                // endregion
                // region JS Files
                'resources/js/main.js',
                'resources/js/home.js',
                'resources/js/schedule.js',
                'resources/js/player.js',
                'resources/js/recap.js',
                'resources/js/statEdit.js',
                'resources/js/jobs.js',
                'resources/js/stats.js',
                'resources/js/scavenger/step3.js',
                'resources/js/scavenger/step4.js',
                'resources/js/scavenger/step6.js',
                'resources/js/scavenger/step8.js',
                'resources/js/porter.js',
                'resources/js/declan.js',
                'resources/js/firebase-messaging-sw.js'
                // endregion
            ],
            refresh: true,
            fonts: [
                google('Play', {
                    weights: [400, 700],
                    styles: ['normal'],
                    subsets: ['latin'],
                    display: 'swap',
                    preload: [
                        { weight: 400 },
                        { weight: 700 },
                    ],
                    fallbacks: ['system-ui', 'sans-serif'],
                }),
                local('League Gothic', {
                    src: 'resources/fonts/league-gothic/'
                }),
            ],
        }),
        // tailwindcss(),
    ],
    resolve: {
        alias: {
            '~': path.resolve(__dirname, './node_modules'),
        },
    },
    css: {
        lightningcss: {
            errorRecovery: true,
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
