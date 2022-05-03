const path = require('path');
const { DuplicatesPlugin } = require("inspectpack/plugin");

module.exports = {
  resolve: {
    alias: {
      '@': path.resolve('resources/js'),
    },
  },
  plugins: [
    new DuplicatesPlugin({
      // Emit compilation warning or error? (Default: `false`)
      emitErrors: false,
      // Display full duplicates information? (Default: `false`)
      verbose: false
    })
  ]
};