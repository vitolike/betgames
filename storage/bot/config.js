require('dotenv').config({path: __dirname+'/./../../.env'});

module.exports = {
	domain: process.env.WHITELIST_IP,
    port: 8443,
    https:true,
    ssl: {
        key: process.env.SSL_KEY_PATH || null,
        cert: process.env.SSL_CERT_PATH || ''
    }
};