const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');
const path = require('path');

// Trỏ đúng về thư mục public của Laravel
mix.setPublicPath(path.resolve(__dirname, '../../public'))
    .postCss('Resources/css/app.css', 'theme.css', [
        tailwindcss(path.resolve(__dirname, 'tailwind.config.js')),
    ])
    .options({
        processCssUrls: false,
    });
