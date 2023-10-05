let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
    resolve: {
        modules: [
            'node_modules'
        ],
        alias: {

        }
    }
});

mix.autoload({
    jquery: ['$', 'window.jQuery', 'jQuery'],
});

mix
    .sourceMaps(true, 'source-map')
    .sass('resources/assets/sass/main.scss', 'public/css')
    .sass('resources/assets/sass/picker.scss', 'public/css')

    .js('resources/assets/js/main.js', 'public/js/main.js')
    .js('resources/assets/js/home.js', 'public/js/home.js')
    .js('resources/assets/js/schedule.js', 'public/js/schedule.js')
    .js('resources/assets/js/player.js', 'public/js/player.js')
    .js('resources/assets/js/recap.js', 'public/js/recap.js')
    .js('resources/assets/js/statEdit.js', 'public/js/statEdit.js')
    .js('resources/assets/js/jobs.js', 'public/js/jobs.js')
    .js('resources/assets/js/stats.js', 'public/js/stats.js')
    .js('resources/assets/js/scavenger/step3.js', 'public/js/scavenger/step3.js')
    .js('resources/assets/js/scavenger/step4.js', 'public/js/scavenger/step4.js')
    .js('resources/assets/js/scavenger/step6.js', 'public/js/scavenger/step6.js')
    .js('resources/assets/js/scavenger/step8.js', 'public/js/scavenger/step8.js')
    .js('resources/assets/js/porter.js', 'public/js/porter.js')
    .js('resources/assets/js/declan.js', 'public/js/declan.js')
;

if (mix.inProduction()) {
    mix.version();
}

mix.browserSync({
    proxy: 'hudsonvillewaterpolo.local'
});