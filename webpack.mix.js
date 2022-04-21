const mix = require('laravel-mix');

mix.ts('resources/js/app.ts', 'public/js')
  .vue({
    version : 3,
  })
  .postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('tailwindcss'),
  ])
  .postCss('resources/js/SVWS-Server/svws-ui-components/dist/style.css', 'public/css/svws-ui-framework.css', [
    require('postcss-import'),
  ])
  .alias({
    '@': 'resources/js',
  })
  .webpackConfig(require('./webpack.config'));

if (mix.inProduction()) {
  mix.version();
}

mix.disableNotifications();
 

mix.browserSync({
  proxy: '127.0.0.1:8000',
});