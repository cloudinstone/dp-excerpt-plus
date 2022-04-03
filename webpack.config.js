const path = require('path');

const defaultConfig = require('@wordpress/scripts/config/webpack.config');

// console.log(defaultConfig);

module.exports = {
	...defaultConfig,
	entry: {
		// ...defaultConfig.entry, // exclude default entry.
		['admin']: path.resolve(process.cwd(), 'src', 'admin.js'),
	},
};
