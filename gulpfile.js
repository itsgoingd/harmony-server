var gulp = require('gulp'),
	coffeeify = require('gulp-coffeeify'),
	sass = require('gulp-sass'),
	watch = require('gulp-watch'),
	sourcemaps = require('gulp-sourcemaps'),
	concat = require('gulp-concat'),
	rev = require('gulp-rev'),
	revDel = require('rev-del')
	util = require('gulp-util')
	runSequence = require('run-sequence');

var paths = {
	public: './public/assets',
	scripts: {
		source: './resources/assets/javascripts/app.coffee',
		watch: './resources/assets/javascripts/**/*.coffee'
	},
	styles: {
		source: [ './resources/assets/stylesheets/app.scss', './resources/assets/stylesheets/email.scss' ],
		watch: './resources/assets/stylesheets/**/*.scss'
	},
	vendor: {
		scripts: [
			'./resources/assets/vendor/jquery/dist/jquery.min.js',
			'./resources/assets/vendor/prism/prism.js',
			'./resources/assets/vendor/prism/components/prism-php.min.js',
			'./resources/assets/vendor/prism/plugins/line-numbers/prism-line-numbers.min.js',
			'./resources/assets/vendor/prism/plugins/line-highlight/prism-line-highlight.min.js',
			'./resources/assets/vendor/prism/plugins/normalize-whitespace/prism-normalize-whitespace.min.js'
		],
		styles: [
			'./resources/assets/vendor/prism/themes/prism.css',
			'./resources/assets/vendor/prism/plugins/line-numbers/prism-line-numbers.css',
			'./resources/assets/vendor/prism/plugins/line-highlight/prism-line-highlight.css'
		]
	}
};

var onError = function (err) {
	util.log(util.colors.red("ERROR"), err);
	this.emit("end", new util.PluginError("ERROR", err, { showStack: true }));
};

gulp.task('scripts', function()
{
	return gulp.src(paths.scripts.source)
		.pipe(coffeeify({
			options: { debug: util.env.production !== undefined ? false : true }
		}).on('error', onError))
		.pipe(gulp.dest(paths.public))
		.pipe(rev())
		.pipe(gulp.dest(paths.public))
		.pipe(rev.manifest(paths.public+'/rev-manifest.json', {base: process.cwd()+paths.public, merge: true}))
		.pipe(revDel({oldManifest: paths.public+'/rev-manifest.json', dest:paths.public}))
		.pipe(gulp.dest(paths.public));
});

gulp.task('styles', function()
{
	return gulp.src(paths.styles.source)
		.pipe(sourcemaps.init())
		.pipe(sass().on('error', onError))
		.pipe(gulp.dest(paths.public))
		.pipe(rev())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(paths.public))
		.pipe(rev.manifest(paths.public+'/rev-manifest.json', {base: process.cwd()+paths.public, merge: true}))
		.pipe(revDel({oldManifest: paths.public+'/rev-manifest.json', dest:paths.public}))
		.pipe(gulp.dest(paths.public));
});

gulp.task('vendorScripts', function()
{
	return gulp.src(paths.vendor.scripts)
		.pipe(sourcemaps.init())
		.pipe(concat({ path: 'vendor.js', cwd: '' }))
		.pipe(gulp.dest(paths.public))
		.pipe(rev())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(paths.public))
		.pipe(rev.manifest(paths.public+'/rev-manifest.json', {base: process.cwd()+paths.public, merge: true}))
		.pipe(revDel({oldManifest: paths.public+'/rev-manifest.json', dest:paths.public}))
		.pipe(gulp.dest(paths.public));
});

gulp.task('vendorStyles', function()
{
	return gulp.src(paths.vendor.styles)
		.pipe(sourcemaps.init())
		.pipe(concat({ path: 'vendor.css', cwd: '' }))
		.pipe(gulp.dest(paths.public))
		.pipe(rev())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(paths.public))
		.pipe(rev.manifest(paths.public+'/rev-manifest.json', {base: process.cwd()+paths.public, merge: true}))
		.pipe(revDel({oldManifest: paths.public+'/rev-manifest.json', dest:paths.public}))
		.pipe(gulp.dest(paths.public));
});

gulp.task('default', function()
{
	runSequence('scripts', 'styles', 'vendorScripts', 'vendorStyles');
});

gulp.task('watch', function() {
	gulp.watch(paths.scripts.watch, [ 'scripts' ]);
	gulp.watch(paths.styles.watch, [ 'styles' ]);
});
