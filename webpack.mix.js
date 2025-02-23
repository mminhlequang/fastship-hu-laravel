const { mix } = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js/backend.js')
    .combine([
        'public/js/backend.js',
        'public/plugins/select2.min.js',
        'public/plugins/datepicker/bootstrap-datepicker.min.js',
        'resources/assets/js/bootstrap.min.js',
        'public/plugins/toastr/toastr.min.js',
        'public/plugins/pace/pace.min.js',
    ], 'public/js/app.js')
    .sourceMaps()
    .combine([
        'public/plugins/datepicker/bootstrap-datepicker.min.css',
        'node_modules/admin-lte/dist/css/AdminLTE.min.css',
        'resources/assets/css/font-awesome.min.css',
        'node_modules/icheck/skins/square/blue.css',
        'resources/assets/css/bootstrap.min.css',
        'public/plugins/toastr/toastr.min.css',
        'public/plugins/_all-skins.min.css',
        'public/plugins/select2.min.css',
        'public/css/mystyle.css',
        'public/css/itmestyle.css'
    ], 'public/css/all.css')

//APP RESOURCES
.options({ processCssUrls: false });

if (mix.config.inProduction) {
    mix.version();
}