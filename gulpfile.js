'use strict';

var gulp = require( 'gulp' );

var babel      = require( "gulp-babel" );
var concat     = require( "gulp-concat" );
var sourcemaps = require( 'gulp-sourcemaps' );

var sass       = require( 'gulp-sass' );
var rename     = require( 'gulp-rename' );
var gulpif     = require( 'gulp-if' );
var notify     = require( "gulp-notify" );
var gulpignore = require( 'gulp-if' );

var plumber                = require( 'gulp-plumber' );
var uglifycss              = require( 'gulp-uglifycss' );
var uglify                 = require( 'gulp-uglify' );
var imagemin               = require( 'gulp-imagemin' );
var pngquant               = require( 'imagemin-pngquant' );
var imageminJpegRecompress = require( 'imagemin-jpeg-recompress' );

var reportError = function (error) {
	notify(
		{
			title: "There's an error",
			message: 'Check the console for more details.'
		}
	).write( error );
	console.log( error.toString() );
	this.emit( 'end' );
}

gulp.task(
	'babel', function() {
		return gulp.src( ["src/reactjs/classes/*.jsx","src/reactjs/explore_babel.jsx"] )
		.pipe( sourcemaps.init() )
		.pipe(
			babel(
				{
					presets: ['react','es2015']
				}
			).on( 'error', reportError )
		)
		.pipe( uglify() )
		.pipe( concat( "explore.min.js" ) )
		.pipe( sourcemaps.write( "." ) )
		.pipe( gulp.dest( "js" ) );
	}
);

gulp.task(
	'sass', function () {
		return gulp.src( ['src/sass/**/*.scss', "!src/sass/**/_*.scss"] )
			.pipe( plumber() )
			.pipe( sourcemaps.init() )
			.pipe(
				sass(
					{
						errLogToConsole: true,
						//outputStyle: 'compressed',
						outputStyle: 'compact',
						// outputStyle: 'nested',
						// outputStyle: 'expanded',
						precision: 10
					}
				).on( 'error', reportError )
			)
			.pipe( sourcemaps.write() )
			.pipe( uglifycss() )
			.pipe( gulp.dest( 'css' ) );
	}
);

gulp.task(
	'img', function () {
		return gulp.src( 'src/images/**/*' )
			.pipe(
				imagemin(
					{
						progressive: true,
						svgoPlugins: [{removeViewBox: false}],
						use: [pngquant(), imageminJpegRecompress( {loops: 3, min: 80} )]
					}
				)
			)
			.pipe( gulp.dest( 'images' ) );

	}
);

gulp.task(
	'js', function () {
		return gulp.src( 'src/js/**/*.js' )
			.pipe( uglify() )
			.pipe( gulp.dest( 'js' ) );

	}
);

gulp.task(
	'watch', function() {

		gulp.watch( 'src/sass/**/*.scss', ['sass'] );

		gulp.watch( 'src/js/**/*.js', ['js'] );

		gulp.watch( 'src/reactjs/**/*.jsx', ['babel'] );

		gulp.watch( 'src/images/**/*', ['img'] );

	}
);

gulp.task( 'default',['sass','js','img','babel'] );
