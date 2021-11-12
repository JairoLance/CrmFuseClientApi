"use strict";
const merge = require("webpack-merge");
const prodEnv = require("./prod.env");
const urls = "http://localhost/CRM/crm_fuse/api/";
const iplocal = "localhost";
//const iplocal = "192.168.1.5";
module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  URL: '"http://' + iplocal + '/CRM/crm_fuse/api/public/"',
  PUBLIC_IMAGES:'"http://' + iplocal + '/permisos/public/template/desktop/images/archivos/referencias/"'
});
