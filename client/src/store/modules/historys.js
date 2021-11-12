import router from "@/router";
import ApiService from "../../services/api.service";
import store from "@/store/index.js";

const state = {
  lists: [],
  list: [],
  response: "",
  loading: true,
  project_sel: [],
  form: {
    hi_idhi: null,
    hi_nombre: "",
    hi_descripcion: "",
    hi_proyecto_id: null,
    hi_usuario_asignado_id: null
  }
};

const getters = {
  lists: state => state.lists
};

const actions = {
  async GetProjectId({ commit, state }) {
    var id = router.currentRoute.params.projectId;
    var response = ApiService.post("/Crm_proyectos/getById", { id: id });
    response
      .then(res => {
        let data = res.data;
        if (data) {
          commit("SET_STATE", {
            project_sel: data.data
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
  async GetList({ commit, state }) {
    //  store.dispatch("loading/toggleLoading", { loading: true });
    commit("SET_STATE", {
      loading: true
    });
    var response;
    state.form.hi_proyecto_id = router.currentRoute.params.projectId;
    response = ApiService.post("/Crm_historias/getAll", state.form);
    response
      .then(res => {
        let data = res.data;
        if (data) {
          commit("SET_STATE", {
            list: data.data
          });
          setTimeout(function() {
            commit("SET_STATE", {
              loading: false
            });
          }, 500);
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
  async GUARDAR({ commit, dispatch, state }) {
    var vm = this;
    // store.dispatch("loading/toggleLoading", { loading: true });
    if (router.currentRoute.params.projectId) {
      var crud =
        state.form.hi_idhi > 0 && state.form.hi_idhi != null
          ? "update"
          : "create";
      state.form.hi_proyecto_id = router.currentRoute.params.projectId;
      return await ApiService.post("/Crm_historias/" + crud + "", state.form)
        .then(response => {
          if (response) {
            let data = response.data;
            if (data) {
              store.dispatch("notifications/NotificationToast", {
                type: data.type,
                title: "Mensaje",
                message: data.content
              });
              //   store.dispatch("loading/toggleLoading", { loading: false });
              dispatch("GetList");
            }
          }
          return false;
        })
        .catch(err => console.log(err));
    } else {
      store.dispatch("notifications/NotificationToast", {
        type: "error",
        title: "Proyectos",
        message: "Seleccione un tipo de proyecto !"
      });
    }
  },
  async DELETE({ commit, dispatch }) {
    var vm = this;
    // store.dispatch("loading/toggleLoading", { loading: true });
    return await ApiService.post("/Crm_historias/delete", state.form)
      .then(response => {
        if (response) {
          let data = response.data;
          if (data) {
            store.dispatch("notifications/NotificationToast", {
              type: data.type,
              title: "Mensaje",
              message: data.content
            });
            dispatch("GetList");
          }
        }
        return false;
      })
      .catch(err => console.log(err));
  }
};

const mutations = {
  SET_STATE(state, payload) {
    Object.assign(state, {
      ...payload
    });
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
