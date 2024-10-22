let mix = require('laravel-mix');

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

// mix.js('resources/assets/js/app.js', 'public/js')
    // mix.sass('resources/assets/sass/bootstrap.scss', 'public/css');

mix.js('resources/js/app.js', 'public/js')
	.copy('node_modules/jquery/dist/jquery.min.js', 'public/js');

mix.styles([
    'public/adminlte/css/style.css', 
], 'public/adminlte/css/style.min.css');

// mix.styles([
//     'public/adminlte/dist/css/AdminLTE.css', 
// ], 'public/adminlte/css/AdminLTE.min.css');

// mix.styles([
//     // 'public/adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css',
//     // 'public/adminlte/bower_components/font-awesome/css/font-awesome.min.css',
//     // 'public/adminlte/bower_components/Ionicons/css/ionicons.min.css',
//     // 'public/adminlte/dist/css/AdminLTE.css',
//     // 'public/adminlte/dist/css/skins/_all-skins.min.css',
//     // 'public/adminlte/bower_components/morris.js/morris.css',
//     // 'public/adminlte/bower_components/jvectormap/jquery-jvectormap.css',
    
//     // 'public/adminlte/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css',
//     // 'public/fonts/googlefont.css',
//     // 'public/adminlte/dist/css/skins/_all-skins.min.css',

//     'public/adminlte/css/all.css',
// ], 'public/adminlte/css/all.min.css');

mix.scripts([
	'public/adminlte/js/script.js',
	'public/adminlte/js/booking.js',
	'public/adminlte/js/uploadfile.js',
	'public/adminlte/js/apply_room.js',
	], 
	'public/adminlte/js/script.min.js');

// mix.scripts([
// 	// 'public/adminlte/bower_components/jquery/dist/jquery.min.js',
// 	// 'public/adminlte/bower_components/jquery-ui/jquery-ui.min.js',
//  //    'public/adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js',
//  //    'public/adminlte/bower_components/raphael/raphael.min.js',
//  //    'public/adminlte/bower_components/morris.js/morris.min.js',
//  //    'public/adminlte/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js',
//  //    'public/adminlte/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js',
//  //    'public/adminlte/plugins/jvectormap/jquery-jvectormap-world-mill-en.js',

//  //    'public/adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js',
//  //    'public/adminlte/bower_components/fastclick/lib/fastclick.js',
//  //    'public/adminlte/dist/js/adminlte.min.js',
//  //    'public/adminlte/dist/js/pages/dashboard.js',
//  //    'public/js/dashboard.js',
//     'public/adminlte/js/all.js',
// ], 'public/adminlte/js/all.min.js');

