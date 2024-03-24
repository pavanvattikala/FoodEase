const mix = require("laravel-mix");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/datepicker.js", "public/js")
    .js("resources/js/kitchen.js", "public/js")
    .js("resources/js/app.js", "public/js")
    .js("resources/js/jquery.min.js", "public/js")
    .js("resources/js/menu.js", "public/js")
    .js("resources/js/order.js", "public/js")
    .js("resources/js/pos.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ])
    .css("resources/css/pos.css", "public/css")
    .copy("node_modules/flatpickr/dist/flatpickr.css", "public/css")

    .version();
