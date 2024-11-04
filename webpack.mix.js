const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .react() // Enables React support
    .postCss('resources/css/app.css', 'public/css') // Use postCss instead of sass
    .options({
        processCssUrls: false,
    });
