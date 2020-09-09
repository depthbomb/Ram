const fs = require('fs-extra');
const path = require('path');
const util = require('gulp-util');
const gulp = require('gulp');
const csso = require('gulp-csso');
const imagemin = require('gulp-imagemin');
const header = require('gulp-header');
const sass = require('gulp-sass');
const autoprefix = require('gulp-autoprefixer');
const replace = require('gulp-replace');
const rev = require('gulp-rev');
const revReplace = require('gulp-rev-replace');
const glob = require('glob');
const through = require('through2');
const modify = require('modify-filename');

const PRODUCTION = util.env.env === 'prod' ? true : false;
const settings = {
	imageQuality: PRODUCTION ? 5 : 0
};

const resourcesDir = path.join(__dirname, 'resources', 'assets');
const buildDir = path.join(__dirname, 'public');
const build_hash = require('crypto').createHash('md5').update(Math.random().toString()).digest("hex");
const build_date = Math.round(new Date().getTime() / 1000);
const pkg = require('./package.json');
const version = pkg.version;
const version_major = 'v' + version.substring(0, 1);

const license = [
	"/*",
	"* This work is licensed under the Creative Commons Attribution-NonCommercial 4.0 International License. ",
	"* To view a copy of this license, visit http://creativecommons.org/licenses/by-nc/4.0/.",
	`* Copyright (c) 2014 - ${(new Date()).getFullYear()} Caprine Softworks`,
	"*/",
	""
].join("\n");

const buildConfig = {
	baseUrl: path.join(buildDir, '/')
};

gulp.task('license', () => {
	return gulp.src([
		buildDir + '/assets/js/*.js',
		buildDir + '/assets/css/*.css',
	], { base: buildDir + '/assets' })
	.pipe(header(license))
	.pipe(gulp.dest(buildDir + '/assets'));
});


gulp.task('sass', function() {
	gulp.src([
		resourcesDir + '/sass/app.scss',
	])
	.pipe(sass().on('error', sass.logError))
	.pipe(autoprefix({ browsers: '> 0.5%, last 2 versions, Firefox ESR, not dead' }))
	.pipe(csso())
	.pipe(rev())
	.pipe(through.obj((file, enc, cb) => {
		file.path = modify(file.revOrigPath, (name, ext) => {
			const hash = require('crypto').createHash('md5').update(Math.random().toString()).digest("hex");
			return `${name}-${hash}${ext}`;
		});
		cb(null, file);
	}))
	.pipe(gulp.dest(buildDir + '/assets/css'))
	.pipe(rev.manifest('manifest.json'))
	.pipe(gulp.dest(buildDir + '/assets/css'));
});


gulp.task('images', () => {
	gulp.src([
		resourcesDir + '/img/**/*.{jpeg,jpg,gif,png,bmp,svg}'
	])
	.pipe(PRODUCTION ? imagemin([
		imagemin.gifsicle({ interlaced: true }),
		imagemin.jpegtran({ progressive: true }),
		imagemin.optipng({ optimizationLevel: settings.imageQuality }),
		imagemin.svgo({
			plugins: [
				{ removeViewBox: true },
				{ cleanupIDs: false }
			]
		})
	], { verbose: true }) : util.noop())
	.pipe(rev())
	.pipe(through.obj((file, enc, cb) => {
		file.path = modify(file.revOrigPath, (name, ext) => {
			const hash = require('crypto').createHash('md5').update(Math.random().toString()).digest("hex");
			return `${name}-${hash}${ext}`;
		});
		cb(null, file);
	}))
	.pipe(gulp.dest(buildDir + '/assets/img'))
	.pipe(rev.manifest('manifest.json'))
	.pipe(gulp.dest(buildDir + '/assets/img'));
});


gulp.task('font', () => {
	gulp.src([
		resourcesDir + '/font/**/*.{ttf,woff,woff2,eot,svg,otf}'
	])
	.pipe(rev())
	.pipe(through.obj((file, enc, cb) => {
		file.path = modify(file.revOrigPath, (name, ext) => {
			const hash = require('crypto').createHash('md5').update(Math.random().toString()).digest("hex");
			return `${name}-${hash}${ext}`;
		});
		cb(null, file);
	}))
	.pipe(gulp.dest(buildDir + '/assets/font'))
	.pipe(rev.manifest('manifest.json'))
	.pipe(gulp.dest(buildDir + '/assets/font'));
});


gulp.task('media', () => {
	gulp.src([
		resourcesDir + '/media/**/*.{mp3,wav,flac,webm,mp4,avi}'
	])
	.pipe(rev())
	.pipe(through.obj((file, enc, cb) => {
		file.path = modify(file.revOrigPath, (name, ext) => {
			const hash = require('crypto').createHash('md5').update(Math.random().toString()).digest("hex");
			return `${name}-${hash}${ext}`;
		});
		cb(null, file);
	}))
	.pipe(gulp.dest(buildDir + '/assets/media'))
	.pipe(rev.manifest('manifest.json'))
	.pipe(gulp.dest(buildDir + '/assets/media'));
});


gulp.task('cleanup', ['cleanupThumbsDB'], () => {
	glob(`${buildDir}/assets/**/*.{jpg,gif,png,svg,mp3,db,css,js,json,mp4,webm,wav,ogg,otf,ttf,eot,woff,woff2,map}`, (e, f) => {
		f.forEach(file => {
			fs.unlink(path.resolve('./', file), (err) => {
				if (err && err.code != 'ENOENT') throw new Error(err);
				return util.log(`Cleared built file:`, file);
			});
		});
	});
});


gulp.task('cleanupThumbsDB', () => {
	glob(`${resourcesDir}/**/*.db`, (e, f) => {
		f.forEach(file => {
			fs.unlink(path.join('./', file), (err) => {
				if (err && err.code != 'ENOENT') throw new Error(err);
				return util.log(`Deleted DB file:`, file);
			});
		});
	});

	glob(`./public/**/*.db`, (e, f) => {
		f.forEach(file => {
			fs.unlink(path.join('./', file), (err) => {
				if (err && err.code != 'ENOENT') throw new Error(err);
				return util.log(`Deleted DB file:`, file);
			});
		});
	});
});


gulp.task('rev', () => {
	const manifest = gulp.src([
		buildDir + '/assets/assets.json',
		buildDir + '/assets/**/manifest.json'
	]);

	return gulp.src([
		`${buildDir}/**/*.{css,js}`
	])
	.pipe(revReplace({ manifest }))
	.pipe(gulp.dest(buildDir));
});


gulp.task('rev_js', () => {
	return gulp.src([
		buildDir + '/assets/js/app.js'
	])
	.pipe(rev())
	.pipe(through.obj((file, enc, cb) => {
		file.path = modify(file.revOrigPath, (name, ext) => {
			const hash = require('crypto').createHash('md5').update(Math.random().toString()).digest("hex");
			return `${name}-${hash}${ext}`;
		});
		cb(null, file);
	}))
	.pipe(gulp.dest(buildDir + '/assets/js'))
	.pipe(rev.manifest('manifest.json'))
	.pipe(gulp.dest(buildDir + '/assets/js'));
});


gulp.task('post_build', () => {
	return glob(`./public/assets/js/app.js`, (e, f) => {
		f.forEach(file => {
			fs.unlink(path.join('./', file), (err) => {
				if (err && err.code != 'ENOENT') throw new Error(err);
				return util.log(`Deleted old JS file:`, file);
			});
		});
	});
});


gulp.task('default', ['sass', 'media', 'images', 'font']);