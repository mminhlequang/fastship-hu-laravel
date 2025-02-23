const {
    mix
} = require('laravel-mix');
require('laravel-mix-merge-manifest');


mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/theme-web.js')
    .combine([
        '../../public/js/theme-web.js',
        __dirname + '/Resources/assets/js/common-js.js',
    ], '../../public/js/web.js',)
    .sass(__dirname + '/Resources/assets/sass/products.scss', 'css/products.css')
    .sass(__dirname + '/Resources/assets/sass/app.scss', 'css/web-app.css')
    .combine([
        '../../public/css/owl.carousel.min.css',
        '../../public/css/style.css',
        '../../public/css/products.css',
        '../../public/css/web-app.css',
    ], '../../public/css/web.css')
    .copy(__dirname + '/Resources/assets/webfonts/', '../../public/webfonts')
    .copy(__dirname + '/Resources/assets/img/', '../../public/img');
if (mix.inProduction()) {
    mix.version();
}