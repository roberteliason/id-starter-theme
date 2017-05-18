var
	gulp        = require('gulp'),
	plumber     = require('gulp-plumber'),
	sass        = require('gulp-sass'),
	sourcemaps  = require('gulp-sourcemaps'),
	source      = require('vinyl-source-stream'),
	buffer      = require('vinyl-buffer'),
	browserify  = require('browserify'),
	watchify    = require('watchify'),
	uglify      = require('gulp-uglify'),
	notify      = require('gulp-notify'),
	browserSync = require('browser-sync').create();


function handleErrors() {
	var args = Array.prototype.slice.call(arguments);
	notify.onError({
		title: 'Compile Error',
		message: '<%= error.message %>'
	}).apply(this, args);

	this.emit('end'); // Keep gulp from hanging on this task
}


function buildScript(watch) {
	var props = {
		entries: [ 'resources/js/all/main.js' ],
		debug : false,
		cache: {},
		packageCache: {},
		fullPaths: true
	};

	// watchify() if watch requested, otherwise run browserify() once
	var bundler = ( true === watch ) ? watchify(browserify(props)) : browserify(props);

	function rebundle() {
		var stream = bundler.bundle();
		return stream
			.on('error', handleErrors)
			.pipe(source('app.js'))
			.pipe(buffer())
			.pipe(sourcemaps.init({loadMaps: true}))
			.pipe(uglify())
			.pipe(sourcemaps.write('./'))
			.pipe(gulp.dest('./resources/js/'))
			.pipe(notify('JS bundled.'))
			.pipe(browserSync.stream());
	}

	// listen for an update and run rebundle
	bundler.on('update', function() {
		rebundle();
	});

	// run it once the first time buildScript is called
	return rebundle();
}


// run once
gulp.task('scripts', function() {
	return buildScript(false);
});


gulp.task('default', function() {
	gulp.watch('./resources/sass/**/*.scss', ['sass']);

	browserSync.init({
		proxy: "namaste.dev"
	});
	gulp.watch(['*.php']).on('change', browserSync.reload);

	buildScript(false);
});


gulp.task('sass', function() {
	return gulp.src(['./resources/sass/**/*.scss', './resources/sass/**/**/*.scss'])
		.pipe(plumber({errorHandler: handleErrors}))
		.pipe(sourcemaps.init())
		.pipe(sass())
		.pipe(sourcemaps.write())
		.pipe(plumber.stop())
		.pipe(gulp.dest('./resources/css'))
		.pipe(notify('Sass compiled and reloaded'))
		.pipe(browserSync.stream());
});
