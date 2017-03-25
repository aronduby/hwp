var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

var inProduction = elixir.config.production;

// Add factor-bundle to browserify plugins
elixir.config.js.browserify.plugins.push(
    {
        name: 'factor-bundle',
        options: {
            outputs: [
                'public/js/main.js',
                'public/js/home.js',
                'public/js/schedule.js',
                'public/js/player.js',
                'public/js/recap.js',
                'public/js/statEdit.js'
            ]
        }
    }
);

elixir.config.js.browserify.paths = [
    './node_modules',
    './resources/assets/js'
];

elixir(function (mix) {
    mix.sass('main.scss');
    // mix.browserify('main.js');

    mix.browserify(
        // Entry points need to be in the same order as the factor bundle outputs
        ['main.js', 'home.js', 'schedule.js', 'player.js', 'recap.js', 'statEdit.js'],
        'public/js/components.js'
    );


    var versionedFiles = [
        'js/main.js',
        'js/home.js',
        'js/schedule.js',
        'js/player.js',
        'js/components.js',
        'js/recap.js',
        'js/statEdit.js'
    ];
    if (inProduction) {
        versionedFiles.push('css/main.css');
    }
    mix.version(versionedFiles);

    mix
        .copy('bower_components/font-awesome/fonts', 'public/fonts/font-awesome')
        .copy('bower_components/league-gothic/webfonts/leaguegothic-regular-webfont.*', 'public/fonts/league-gothic');


    mix.browserSync({
        proxy: 'hudsonvillewaterpolo.local'
    });
});
