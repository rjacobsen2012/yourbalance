const path = require('path')
const mix = require('laravel-mix');

mix.webpackConfig({
    resolve: {
        alias: {
            '@components': path.resolve('resources/js/components'),
            '@mixins': path.resolve('resources/js/mixins'),
            '@plugins': path.resolve('resources/js/plugins'),
            '@views': path.resolve('resources/js/views'),
            '@util': path.resolve('resources/js/util'),
            '@vendor': path.resolve('resources/js/vendor'),
            'sass': path.resolve('resources/sass'),
        },
    },
})

if (Mix.isUsing('hmr')) {
    mix.webpackConfig({
        devServer: {
            disableHostCheck: true,
        },
    })
}

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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css', {
        implementation: require('node-sass'),
    });

if (mix.inProduction()) {
    mix.version();
}
