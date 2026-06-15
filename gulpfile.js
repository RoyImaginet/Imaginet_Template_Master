const gulp = require('gulp');
const { series, parallel, watch } = require('gulp');
const fs = require('fs');
const pkg = require('./package.json'); // Dynamically read package metadata

// Core Compilation Modules
const concat = require('gulp-concat');
const plumber = require('gulp-plumber');
const uglify = require('gulp-uglify');
const cleanCSS = require('gulp-clean-css');
const sass = require('gulp-sass')(require('sass')); // Modern Dart-Sass Compiler Hook
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');
const gap = require('gulp-append-prepend');
const clean = require('gulp-clean');

// Path Map Definitions
const assetsBase = './wordpress/wp-content/themes/imaginet/assets';
const templateDir = './wordpress/wp-content/themes/imaginet';
const cleanUpDirs = ['./downloads/', './imaginet', './wordpress/wp-content/themes/twenty*'];

// Dynamically matches your theme version to your package.json version string
const cssHeader = `/*\n\tTheme Name: Imaginet Starter Template\n\tVersion: ${pkg.version}\n\tAuthor: Imaginet Studio\n*/`;

// ==========================================================================
// 1. Core Production Asset Compilation Tasks
// ==========================================================================

// Compile Custom SCSS into Theme Directory
function compileSass() {
	return gulp
		.src(`${assetsBase}/scss/**/*.scss`)
		.pipe(plumber())
		.pipe(sourcemaps.init())
		.pipe(sass({ outputStyle: 'expanded' }).on('error', sass.logError))
		.pipe(autoprefixer())
		.pipe(cleanCSS())
		.pipe(gap.prependText(cssHeader))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(templateDir));
}

// Combine Framework CSS Libraries directly from node_modules
function bundleVendorCss() {
	const coreCssResources = ['./node_modules/bootstrap/dist/css/bootstrap.min.css'];
	return gulp
		.src(coreCssResources, { allowEmpty: true })
		.pipe(concat('vendor-styles.css'))
		.pipe(cleanCSS())
		.pipe(gulp.dest(`${assetsBase}/css`));
}

// Clean working directory tracking maps
function cleanMaps(cb) {
	if (fs.existsSync(`${templateDir}/style.css.map`)) {
		return gulp.src(`${templateDir}/style.css.map`, { read: false, allowEmpty: true }).pipe(clean({ force: true }));
	}
	cb();
}

// ==========================================================================
// 2. Automated Workspace Downloader & Provision Engine (Modernized)
// ==========================================================================
const axios = require('axios');
const AdmZip = require('adm-zip');

async function downloadWP() {
	if (!fs.existsSync('./downloads/latest.zip')) {
		// Ensure downloads folder exists
		if (!fs.existsSync('./downloads')) fs.mkdirSync('./downloads');
		
		const response = await axios({
			url: 'https://wordpress.org/latest.zip',
			method: 'GET',
			responseType: 'stream'
		});
		
		const writer = fs.createWriteStream('./downloads/latest.zip');
		response.data.pipe(writer);
		
		return new Promise((resolve, reject) => {
			writer.on('finish', resolve);
			writer.on('error', reject);
		});
	}
}

function unzipWP(cb) {
	try {
		const zip = new AdmZip('./downloads/latest.zip');
		zip.extractAllTo('./', true); // Extracts to root, creating /wordpress
		cb();
	} catch (err) {
		cb(err);
	}
}

// ==========================================================================
// 3. Automation Task Event Listeners (Watch)
// ==========================================================================

function watchFiles() {
	// Recompile SCSS instantly whenever style changes are detected
	watch(`${assetsBase}/scss/**/*.scss`, compileSass);
}

// Task Pipelines Definitions
const setupWorkspace = series(
	downloadWP,
	unzipWP,
	setStarterTemplateInWpContent,
	parallel(compileSass, bundleVendorCss),
	cleanGarbage,
	cleanMaps,
	createUploadsHtaccess,
	(cb) => {
		console.log('\x1b[32m%s\x1b[0m', '► Core Workspace successfully generated! Run "gulp" to develop.');
		cb();
	}
);

// Module Exports mapping directly to package.json scripts
exports.setup = setupWorkspace;
exports.compile = parallel(compileSass, bundleVendorCss);
exports.default = series(parallel(compileSass, bundleVendorCss), watchFiles);
