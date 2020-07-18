const mix = require('laravel-mix');

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

mix.styles([
    'resources/template/css/bootstrap.min.css',
    'resources/template/css/style.css',
    'resources/template/css/font-awesome.min.css',
    'resources/template/css/streamline-icon.css',
    'resources/template/css/header.css',
    'resources/template/css/portfolio.css',
    'resources/template/css/blog.css',
    'resources/template/css/v-animation.css',
    'resources/template/css/v-bg-stylish.css',
    'resources/template/css/font-icons.css',
    'resources/template/css/shortcodes.css',
    'resources/template/css/utilities.css',
    'resources/template/css/theme-responsive.css',
    'resources/template/plugins/aos/aos.css',
    'resources/template/plugins/owl-carousel/owl.theme.css',
    'resources/template/plugins/owl-carousel/owl.carousel.css',
    'resources/template/css/skin/default.css',
    'resources/template/plugins/jquery-growl/stylesheets/jquery.growl.css',
    'resources/template/css/custom.css',
    'resources/template/css/main.css',
], 'public/css/site.css');
mix.scripts([
    'resources/template/js/jquery.min.js',
    'resources/template/js/popper.js',
    'resources/template/js/bootstrap.min.js',
    'resources/template/js/jquery.flexslider-min.js',
    'resources/template/js/jquery.easing.js',
    'resources/template/js/jquery.fitvids.js',
    'resources/template/js/jquery.carouFredSel.min.js',
    'resources/template/js/jquery.validate.js',
    'resources/template/js/theme-plugins.js',
    'resources/template/js/jquery.isotope.min.js',
    'resources/template/js/imagesloaded.js',
    'resources/template/js/view.min.js',
    'resources/template/plugins/aos/aos.js',
    'resources/template/js/theme-core.js',
    'resources/template/js/theme.js',
    'resources/template/js/theme.init.js',
    'resources/template/plugins/jquery-growl/javascripts/jquery.growl.js',
    'resources/template/js/main.js',
], 'public/js/site.js');
mix.copy('resources/template/css/fonts', 'public/css/fonts');
mix.copy('resources/template/css/font', 'public/css/font');
mix.copy('resources/template/img', 'public/img');
