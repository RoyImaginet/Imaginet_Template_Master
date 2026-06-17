const gulp = require('gulp');
const { series, parallel, watch } = require('gulp');
const fs = require('fs');

// Core Compilation Modules
const concat = require('gulp-concat');
const plumber = require('gulp-plumber');
const uglify = require('gulp-uglify');
const cleanCSS = require('gulp-clean-css');
const sass = require('gulp-sass')(require('sass')); 
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer').default; 
const gap = require('gulp-append-prepend');
const clean = require('gulp-clean');

// Path Map Definitions
const assetsBase = './wordpress/wp-content/themes/imaginet/assets';
const templateDir = './wordpress/wp-content/themes/imaginet';
const cleanUpDirs = ['./downloads/', './imaginet', './wordpress/wp-content/themes/twenty*'];

// Core Production Asset Compilation Modules
const rtlcss = require('gulp-rtlcss');
const rename = require('gulp-rename');

const cssHeader = `/*\n\tTheme Name: Imaginet Starter Template\n\tVersion: 3.0\n\tAuthor: Imaginet Studio\n*/`;

// ==========================================================================
// 1. Core Production Asset Compilation Tasks
// ==========================================================================

// Custom SCSS compiles into its own folder AND creates an RTL version automatically
function compileSass() {
	return gulp
		.src(`${assetsBase}/scss/*.scss`) // ◄ Targets all root SCSS files in the folder
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
		.pipe(autoprefixer({ cascade: false }))
		.pipe(cleanCSS()) // Minifies both style.css and style-rtl.css natively
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(`${assetsBase}/scss`)); // ◄ Outputs both directly into your assets folder
}

// Framework CSS Libraries compile directly into the root style.css
function bundleVendorCss(cb) {
	const targetFile = `${templateDir}/style.css`;
	
	// If it doesn't exist locally, stream it straight from the official Bootstrap CDN!
	const download = require('gulp-download-files');
	download('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css')
		.pipe(concat('style.css'))
		.pipe(cleanCSS())
		.pipe(gap.prependText(cssHeader))
		.pipe(gulp.dest(templateDir))
		.on('finish', () => {
			if (typeof cb === 'function') cb();
		});
}

// Keep this wrapper helper right below it
function bundleVendorCssStandalone(cb) {
	bundleVendorCss(cb);
}

// ==========================================================================
// 2. Automated Workspace Downloader & Provision Engine
// ==========================================================================

function downloadWP(cb) {
	if (!fs.existsSync('./downloads/latest.zip')) {
		const download = require('gulp-download-files');
		download('https://wordpress.org/latest.zip')
			.pipe(gulp.dest('./downloads/'))
			.on('finish', cb);
		return;
	}
	cb();
}

function unzipWP() {
	// Added return statement to handle asynchronous completion
	return gulp.src('./downloads/latest.zip')
		.pipe(require('gulp-unzip')())
		.pipe(gulp.dest('./'));
}

function setStarterTemplateInWpContent() {
	// Added return statement to explicitly track asset migration completion
	return gulp.src('./imaginet/**')
		.pipe(gulp.dest('./wordpress/wp-content/themes/imaginet'));
}

function cleanGarbage() {
	return gulp.src(cleanUpDirs, { read: false, allowEmpty: true }).pipe(clean({ force: true }));
}

function createUploadsHtaccess(cb) {
	const htaccessPath = './wordpress/wp-content/uploads';
	if (!fs.existsSync(htaccessPath)) {
		fs.mkdirSync(htaccessPath, { recursive: true });
	}
	fs.writeFile(`${htaccessPath}/.htaccess`, 'Options -Indexes', cb);
}

// ==========================================================================
// 3. Automation Task Event Listeners (Watch)
// ==========================================================================

function watchFiles() {
	watch(`${assetsBase}/scss/**/*.scss`, compileSass);
}

// Task Pipelines Definitions
const setupWorkspace = series(
	downloadWP,
	unzipWP,
	setStarterTemplateInWpContent, // 1. Moves the files into place completely first
	compileSass,                   // 2. Compiles your custom styling
	bundleVendorCss,               // 3. Generates root style.css from Bootstrap cleanly
	cleanGarbage,
	createUploadsHtaccess,
	(cb) => {
		console.log('\x1b[32m%s\x1b[0m', '► Core Workspace successfully generated! Run "gulp" to develop.');
		cb();
	}
);

// Module Exports mapping directly to package.json scripts
exports.setup = setupWorkspace;
exports.init = setupWorkspace;
exports.compile = parallel(compileSass, bundleVendorCss);
exports.default = series(compileSass, watchFiles);
