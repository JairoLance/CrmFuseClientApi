"use strict";
const merge = require("webpack-merge");
const prodEnv = require("./prod.env");
const urls = "http://crmapi.presstaya.com/";
const iplocal = "http://crmapi.presstaya.com";
module.exports = {
  NODE_ENV: '"production"',
  URL: '"http://crmapi.presstaya.com/"',
  PUBLIC_IMAGES:
    '"http://' +
    iplocal +
    '/permisos/public/template/desktop/images/archivos/referencias/"'
};
