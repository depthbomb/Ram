const fs = require('fs-extra');
const util = require('gulp-util');
const childProcess = require('child_process');

runScript = (scriptPath, callback) => {
	const process = childProcess.fork(scriptPath);
	let invoked = false;

	process.on('error', (err) => {
		if (invoked) return;
		invoked = true;
		callback(err);
	});

	process.on('exit', (code) => {
		if (invoked) return;
		invoked = true;
		const err = code === 0 ? null : new Error('exit code ' + code);
		callback(err);
	});
};

check = () => {
	try {
		const ASSETS = fs.readFileSync('./assets.json');
		JSON.parse(ASSETS);
		return util.log('assets.json seems to be valid!');
	} catch (error) {
		util.log('assets.json seems to be invalid, re-running manifest.js');
		runScript('./manifest.js', (err) => {
			if (err) throw err;
			util.log('Re-ran manifest.js, checking again...');
			check();
		});
	}
};

check();