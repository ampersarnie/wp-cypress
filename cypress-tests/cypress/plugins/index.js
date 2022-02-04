const wpCypressPlugin = require('@bigbite/wp-cypress/lib/cypress-plugin');

module.exports = async (on, config) => wpCypressPlugin(on, config);
