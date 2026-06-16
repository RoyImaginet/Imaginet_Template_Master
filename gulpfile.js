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
const autoprefixer = require('gulp-autoprefixer').default; // Added .default fallback if needed
const gap = require('gulp-append-prepend');
const clean = require('gulp-clean');

// Path Map Definitions
const assetsBase = './wordpress/wp-content/themes/imaginet/assets';
const templateDir = './wordpress/wp-content/themes/imaginet';
const cleanUpDirs = ['./downloads/', './imaginet', './wordpress/wp-content/themes/twenty*'];

// Core Production Asset Compilation Modules
const rtlcss = require('gulp-rtlcss');
const rename = require('gulp-rename');

const cssHeader = `/*\n\tTheme Name: Imaginet Starter Template\n\tVersion: 2.01\n\tAuthor: Imaginet Studio\n*/`;

// ==========================================================================
// 1. Core Production Asset Compilation Tasks
// ==========================================================================

// Custom SCSS compiles into its own folder AND creates an RTL version automatically
function compileSass() {
	return gulp
		.src(`${assetsBase}/scss/**/*.scss`)
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
		.pipe(autoprefixer({ cascade: false }))
		
		// 1. Save out your normal custom style.css (Un-minified first to keep semicolons)
		.pipe(gulp.dest(`${assetsBase}/scss`)) 
		
		// 2. Process raw style.css for RTL transformation without crashing
		.pipe(gulp.src(`${assetsBase}/scss/style.css`, { allowEmpty: true }))
		.pipe(rtlcss()) 
		.pipe(rename({ suffix: '-rtl' })) 
		.pipe(gulp.dest(`${assetsBase}/scss`)) // Saves un-minified style-rtl.css
		
		// 3. Minify all compiled production stylesheets together
		.pipe(gulp.src(`${assetsBase}/scss/*.css`))
		.pipe(cleanCSS())
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(`${assetsBase}/scss`));
}

// Framework CSS Libraries compile directly into the root style.css (Run Once)
function bundleVendorCss() {
	const coreCssResources = [`${assetsBase}/bootstrap/css/bootstrap.min.css`];
	return gulp
		.src(coreCssResources, { allowEmpty: true })
		.pipe(concat('style.css'))
		.pipe(cleanCSS())
		.pipe(gap.prependText(cssHeader))
		.pipe(gulp.dest(templateDir));
}

// ==========================================================================
// 2. Automated Workspace Downloader & Provision Engine
// ==========================================================================

// FIXED: Restored downloadWP from the duplicate definition override
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

function unzipWP(cb) {
	const unzip = require('gulp-unzip');
	gulp.src('./downloads/latest.zip').pipe(unzip()).pipe(gulp.dest('./')).on('finish', cb);
}

function setStarterTemplateInWpContent(cb) {
	gulp.src('./imaginet/**')
		.pipe(gulp.dest('./wordpress/wp-content/themes/imaginet'))
		.on('finish', cb);
}

function cleanGarbage(cb) {
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
	setStarterTemplateInWpContent,
	parallel(compileSass, bundleVendorCss),
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
