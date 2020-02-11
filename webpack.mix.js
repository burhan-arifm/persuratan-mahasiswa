const mix = require('laravel-mix');
const path = require('path');

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
      alias: {
         ziggy: path.resolve('vendor/tightenco/ziggy/dist/js/route.js')
      }
   }
})

mix.js('resources/js/app.js', 'public/js')
   .scripts([
      'resources/js/datetimepicker.js',
      'resources/js/form-masking.js',
      'resources/js/icon-toggle.js',
      'resources/js/logout-trigger.js',
      'resources/js/select.js'
   ], 'public/js/custom.js')
   .sass('resources/sass/app.scss', 'public/css')
   .styles([
      'resources/css/admin.css'
   ], 'public/css/style.css');
