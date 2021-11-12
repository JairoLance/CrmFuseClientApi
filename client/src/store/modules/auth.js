import { UserService, AuthenticationError } from "@/services/user.service";
import { TokenService } from "@/services/storage.service";
import ApiService from "../../services/api.service";
import router from "@/router";
import store from "@/store/index.js";

const state = {
  authenticating: false,
  accessToken: TokenService.getToken(),
  authenticationErrorCode: 0,
  authenticationError: "",
  session: TokenService.getSession() || {},
  IsOpenCash: false,
  ishayterminales: false,
  list_empresa: [],
  loadingRegistro: false,
  FormRegister: {
    rusername: "",
    rpassword: "",
    reppassword: "",
    rempresa: null,
    rcorreo: "",
    rnombres: ""
  }
};

const getters = {
  loggedIn: state => {
    return state.accessToken ? true : false;
  },
  authenticationErrorCode: state => {
    return state.authenticationErrorCode;
  },
  authenticationError: state => {
    return state.authenticationError;
  },
  authenticating: state => {
    return state.authenticating;
  },
  terminales: state => {
    state.select_terminal = TokenService.getTerminal() || {};
    return TokenService.getTerminal() || {};
  },
  GET_AUTH_SESSION: state => {
    if (!state.session) {
      try {
        const session = TokenService.getSession();
        state.session = session;
      } catch (e) {
        console.error(e);
      }
    }
    return state.session;
  }
};

const actions = {
  async login({ commit }, { username, password }) {
    commit("loginRequest");
    try {
      const token = await UserService.login(username, password);
      if (token.type != "error" && token.access_token) {
        commit("loginSuccess", token);
        commit("SessionSuccess");
        router.push(router.history.current.query.redirect || "/");
      } else {
        return token;
      }
      // console.log("Url visita :"+router.history.current.query.redirect)

      // Redirect the user to the page he first tried to visit or to the home view
    } catch (e) {
      if (e instanceof AuthenticationError) {
        commit("loginError", {
          errorCode: e.errorCode,
          errorMessage: e.message
        });
      }

      return false;
    }
  },
  async register({ commit, state }) {
    try {
      commit("SET_STATE", {
        loadingRegistro: true
      });
      var response;
      response = ApiService.post("/auth/register", state.FormRegister);
      response
        .then(res => {
          let data = res.data;
          if (data) {
            store.dispatch("notifications/NotificationToast", {
              type: data.type,
              title: "Mensaje",
              message: data.content
            });
            setTimeout(function() {
              commit("SET_STATE", {
                loadingRegistro: false
              });
            }, 500);
            if (data.type != "error") {
              let to = {
                name: "login"
              };
              router.push(to);
            }
          } else {
          }
        })
        .catch(err => {
          setTimeout(function() {
            commit("SET_STATE", {
              loadingRegistro: false
            });
          }, 500);
        });
      return response;
    } catch (e) {
      if (e instanceof AuthenticationError) {
        commit("loginError", {
          errorCode: e.errorCode,
          errorMessage: e.message
        });
      }

      return false;
    }
  },

  async GetEmpresas({ commit, state }) {
    //  store.dispatch("loading/toggleLoading", { loading: true });
    commit("SET_STATE", {
      loading: true
    });
    var response;
    response = ApiService.post("/auth/getAllEmpresas", {});
    response
      .then(res => {
        let data = res.data;
        if (data) {
          commit("SET_STATE", {
            list_empresa: data.data,
            loading: false
          });
        } else {
        }
      })
      .catch(err => {
        setTimeout(function() {
          // store.dispatch("loading/toggleLoading", { loading: false });
        }, 500);
      });
    return response;
  },

  logout({ commit }) {
    UserService.logout();
    commit("logoutSuccess");
    router.push("/login");
  }
};

const mutations = {
  loginRequest(state) {
    state.authenticating = true;
    state.authenticationError = "";
    state.authenticationErrorCode = 0;
  },
  SET_CHANGE_COLOR_CORPORATIVO(state, color) {
    TokenService.ChangeUpdateSessions("org_color_corporativo", color);
    state.session.org_color_corporativo = color;
  },
  loginSuccess(state, accessToken) {
    state.accessToken = accessToken;
    state.authenticating = false;
  },

  SessionSuccess(state) {
    state.session = TokenService.getSession();
    state.authenticating = false;
  },

  loginError(state, { errorCode, errorMessage }) {
    state.authenticating = false;
    state.authenticationErrorCode = errorCode;
    state.authenticationError = errorMessage;
  },

  logoutSuccess(state) {
    state.accessToken = "";
    state.session = [];
  },
  SET_STATE(state, payload) {
    Object.assign(state, {
      ...payload
    });
  },
  IsOpenCashSuccess(state, est_IsOpenCash) {
    state.IsOpenCash = est_IsOpenCash;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
