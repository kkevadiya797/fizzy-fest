const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/assets/js');
mix.js('resources/js/app-web.js', 'public/assets/js');
// .sass('resources/sass/app.scss', 'public/assets/css');

mix.webpackConfig({
	resolve: {
		alias: {
			"@": path.resolve(__dirname, "resources/js/components/"),
		}
	}
});