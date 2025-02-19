const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .postCss('resources/css/admin.css', 'public/css');

if (mix.inProduction()) {
    mix.version();
}
