import Vue from "vue";
import axios from "axios";
import { TokenService } from "../services/storage.service";
import store from "@/store/index.js";

const BASE_URL = process.env.URL;
axios.defaults.baseURL = `${BASE_URL}`;
axios.interceptors.response.use(
  function(config) {
    let { message, numberError } = JSON.parse(config.request.response);

    if (numberError == 401) {
      store.dispatch(
        "notifications/toggleNotification",
        JSON.parse(config.request.response)
      );
    }
    return config;
  },
  function(err) {
    // "Es posible que el tokens ya expiro , vuelve a entrar !"
    const msn = {
      message: "Es posible que el tokens ya expiro , vuelve a entrar !"
    };
    store.dispatch("notifications/toggleNotification", msn);
    return Promise.reject(err);
  }
);

const ApiService = {
  PathUrl() {
    return BASE_URL;
  },
  init(baseURL) {
    axios.defaults.baseURL = baseURL;
  },
  mount401Interceptor() {
    this._401interceptor = axios.interceptors.response.use(
      response => {
        return response;
      },
      async error => {
        if (error.request.status == 401) {
          if (error.config.url.includes("/o/token/")) {
            // Refresh token has failed. Logout the user
            store.dispatch("Auth/logout");
            throw error;
          } else {
            // Refresh the access token
            try {
              await store.dispatch("Auth/refreshToken");
              // Retry the original request
              return this.customRequest({
                method: error.config.method,
                url: error.config.url,
                data: error.config.data
              });
            } catch (e) {
              // Refresh has failed - reject the original request
              throw error;
            }
          }
        }

        // If error was not 401 just reject as is
        throw error;
      }
    );
  },

  unmount401Interceptor() {
    // Eject the interceptor
    axios.interceptors.response.eject(this._401interceptor);
  },

  setHeader() {
    //console.log(`Bearer ${TokenService.getToken()}`)
    axios.defaults.headers.common["Access-Control-Allow-Origin"] = "*";
    axios.defaults.headers.common["content-type"] =
      "application/json; charset=utf-8;";
    axios.defaults.headers.common[
      "Authorization"
    ] = `Bearer ${TokenService.getToken()}`;
  },

  removeHeader() {
    axios.defaults.headers.common = {};
  },

  async get(resource) {
    return axios.get(resource);
  },

  async post(resource, data) {
    var h = {
      usuario: store.state.auth.session.us_idusuario,
      empresa: store.state.auth.session.us_empresa
    };

    var obj = Object.assign(h, data);

    return axios.post(resource, obj);
  },

  put(resource, data) {
    return axios.put(resource, data);
  },

  delete(resource) {
    return axios.delete(resource);
  },

  /**
   * Perform a custom Axios request.
   *
   * data is an object containing the following properties:
   *  - method
   *  - url
   *  - data ... request payload
   *  - auth (optional)
   *    - username
   *    - password
   **/
  customRequest(data) {
    return axios(data);
  }
};

export default ApiService;
