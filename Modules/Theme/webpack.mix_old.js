const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');
require('laravel-mix-purgecss');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/theme-web.js')
    .combine([
        '../../public/js/theme-web.js',
    ], '../../public/js/web.js')
    .combine([
        '../../public/css/style.css',
    ], '../../public/css/web.css')
    .purgeCss({
        enabled: mix.inProduction(), // Chỉ thực hiện trong môi trường production
        content: [
            './resources/views/**/*.blade.php',
            './resources/js/**/*.vue',
            './resources/js/**/*.js',
        ],
        defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
    });


if (mix.inProduction()) {
    mix.version();
}