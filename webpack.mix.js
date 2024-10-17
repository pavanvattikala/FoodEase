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

    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ])

    // copy all the required JS files to the public folder
    .copy("resources/js/jquery.min.js", "public/js")
    .copy("resources/js/pos.js", "public/js")
    .copy("resources/js/KOT.js", "public/js")
    .copy("resources/js/chart.js", "public/js")
    .copy("resources/js/datatable/buttons.dataTables.js", "public/js/datatable")
    .copy("resources/js/datatable/buttons.print.min.js", "public/js/datatable")
    .copy("resources/js/datatable/dataTables.buttons.js", "public/js/datatable")
    .copy("resources/js/datatable/dataTables.min.js", "public/js/datatable")
    .copy("resources/js/datatable/html5.min.js", "public/js/datatable")
    .copy("resources/js/datatable/jszip.min.js", "public/js/datatable")
    .copy("resources/js/datatable/pdfmake.min.js", "public/js/datatable")
    .copy("resources/js/datatable/vfs_fonts.js", "public/js/datatable")

    // copy all the required CSS files to the public folder
    .copy("node_modules/flatpickr/dist/flatpickr.css", "public/css")
    .copy("resources/css/pos.css", "public/css")
    .copy("resources/css/datatable/dataTables.min.css", "public/css/datatable")
    .copy(
        "resources/css/datatable/buttons.dataTables.css",
        "public/css/datatable"
    )

    // copy all the required Audio files to the public folder
    .copy("resources/audio/select.wav", "public/audio")

    .version()

    // Enable polling
    .webpackConfig({
        watchOptions: {
            poll: 1000, // Check for changes every 1000 milliseconds
            aggregateTimeout: 300, // Delay before rebuilding
        },
    });
