const fs = require('fs-extra');
const path = require('path');
const util = require('gulp-util');
const glob = require('glob');

const buildDir = path.join(__dirname, 'public');
const assetFile = path.join(__dirname, 'assets.json');

const build_hash = require('crypto').createHash('md5').update(Math.random().toString()).digest("hex");
const build_date = Math.round(new Date().getTime() / 1000);
const pkg = require('./package.json');
const version = pkg.version;
const version_major = 'v' + version.substring(0, 1);

util.log('Compiling manifests into assets.json...');

glob(`${buildDir}/**/manifest.json`, (err, files) => {
	if (err) throw new Error(err);

	let assets = {
		build: `${version_major}:${build_hash}:${build_date}`,
		files: {}
	};

	for (let i = 0; i < files.length; i++) {
		const file = files[i];

		/**
		* /assets/foo/bar/manifest.json
		*/
		const manifestFile = file.replace(buildDir.replace(/\\/g, "/"), '');

		/**
		* /assets/foo/bar/
		*/
		const manifestPath = manifestFile.replace('manifest.json', '');

		fs.readFile(file, (err, data) => {
			let manifestData = JSON.parse(data);

			Object.keys(manifestData).forEach((key) => {
				const nKey = `${manifestPath + key}`;
				const nValue = `${manifestPath + manifestData[key]}`;
				assets.files[nKey] = nValue;
			});

			fs.writeFile(assetFile, JSON.stringify(assets), (err) => {
				if (err) throw new Error(err);
			});
		});
	}
});