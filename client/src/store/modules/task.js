import router from "@/router";
import ApiService from "../../services/api.service";
import store from "@/store/index.js";
import { fetchReadTasks, fetchMoveTasks } from "./models/task_model";
const moment = require("moment");

let now = moment();

const state = {
  tasks: [],
  list: [],
  loading: false,
  pending: false,
  stateTicketsOpen: [],
  selectedItem: {
    proyecto_id: null
  },
  statuses: [
    { id: 1, name: "on-hold", text: "ACTIVO" },
    { id: 2, name: "in-progress", text: "EN DESARROLLO" },
    { id: 3, name: "needs-review", text: "FINALIZADO" }
  ],
  isEditMode: false,
  dataDialog: false,
  deleteDialog: false,
  history_sel: [],
  listTask: [],
  form: {
    ta_idta: null,
    ta_historia_id: null,
    ta_usuario_id: null,
    ta_tipo_tarea: 1,
    ta_titulo: "",
    ta_comentarios: "",
    ta_fecha_vencimiento: now.format("YYYY-MM-DD HH:mm:ss"),
    ta_hora: now.format("YYYY-MM-DD HH:mm:ss"),
    ta_descripcion: "",
    ta_asignar_a: 0,
    ta_vincular_a: 0,
    ta_visible_usuarios: 0,
    ta_prioridad: 1,
    ta_estado: 0,
    ta_recordatorio: "NO",
    ta_recordatorio_fecha: now.format("YYYY-MM-DD HH:mm:ss"),
    ta_archivado: 0,
    ta_fecha_actualizacion: null,
    ta_orden: 1
  }
};

// getters
const getters = {
  /*Una vez cargado la lista de projectos podemos filtrar por los tipos de proyectos*/
  /*Proyectos de tipo independiente == 1*/
  GettTask: state => {
    return state.tasks.filter(f => f.ta_archivado === "0");
  },
  /*Proyectos de equipos == 2*/
  GettProjects: state => {
    return state.tasks.filter(f => f.cp_id_tipo_proyecto === "2");
  }
};

// actions stateTicketsOpen
const actions = {
  ///Crm_tareas/TienesTicketsAbiertos

  async TienesTicketsAbiertos({ commit, state }) {
    var _tick = 0;
    try {
      var response = ApiService.post("/Crm_tareas/TienesTicketsAbiertos", {});
      response.then(res => {
        if (res.data.data[0].total) {
          _tick = res.data.data[0].total;
        } else {
          _tick = 0;
        }
        state.stateTicketsOpen = _tick;
        // commit("SET_STATE", {
        //   stateTicketsOpen: _tick
        // });
      });

      return response;
    } catch (e) {
      if (e instanceof AuthenticationError) {
        // commit('loginError', {errorCode: e.errorCode, errorMessage: e.message})
      }
      return false;
    }
  },
  async TienesTicketsAbiertosList({ commit }, estado) {
    estado = typeof estado === "undefined" ? 1 : estado;
    try {
      var response = ApiService.post("/Crm_tareas/TienesTicketsAbiertosList", {
        estado: estado
      });
      response
        .then(res => {
          let data = res.data;

          if (data.data.length > 0) {
            commit("SET_STATE", {
              listTask: data.data
            });
          } else {
            commit("SET_STATE", {
              listTask: []
            });
          }
        })
        .catch(err => {
          setTimeout(function() {
            // store.dispatch("loading/toggleLoading", { loading: false });
          }, 500);
        });
      return response;
    } catch (e) {
      if (e instanceof AuthenticationError) {
        // commit('loginError', {errorCode: e.errorCode, errorMessage: e.message})
      }
      return false;
    }
  },
  async GetHistoryId({ commit, state }) {
    var id = router.currentRoute.params.historyId;
    var response = ApiService.post("/Crm_historias/getById", { id: id });
    response
      .then(res => {
        let data = res.data;
        if (data) {
          commit("SET_STATE", {
            history_sel: data.data
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
    var blocks = [];
    state.form.ta_historia_id = router.currentRoute.params.historyId;
    response = ApiService.post("/Crm_tareas/getAll", state.form);
    response
      .then(res => {
        let data = res.data;
        if (data.data.length > 0) {
          data.data.forEach(r => {
            var fil = [];
            state.statuses.forEach(function(item, index) {
              if (item.id == r.ta_estado) {
                fil.push(item);
              }
            });
            blocks.push({
              id: r.ta_idta,
              title: r.ta_descripcion,
              name: r.ta_titulo,
              comentarios: r.ta_comentarios,
              status: fil[0]
            });
          });
          commit("SET_STATE", {
            list: blocks
          });
          setTimeout(function() {
            commit("SET_STATE", {
              loading: false,
              list: blocks
            });
          }, 500);
        } else {
          commit("SET_STATE", {
            loading: false,
            list: blocks
          });
        }
      })
      .catch(err => {
        setTimeout(function() {
          commit("SET_STATE", {
            loading: false
          });
        }, 500);
      });
    return response;
  },
  async GUARDAR({ commit, dispatch, state }) {
    var vm = this;
    // store.dispatch("loading/toggleLoading", { loading: true });
    if (router.currentRoute.params.historyId) {
      var crud =
        state.form.ta_idta > 0 && state.form.ta_idta != null
          ? "update"
          : "create";
      state.form.ta_historia_id = router.currentRoute.params.historyId;
      state.form.ta_usuario_id = state.history_sel.us_idusuario;
      return await ApiService.post("/Crm_tareas/" + crud + "", state.form)
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
    } else {
      store.dispatch("notifications/NotificationToast", {
        type: "error",
        title: "Historia",
        message: "Seleccione un tipo de historia !"
      });
    }
  },
  async ELIMINAR({ commit, dispatch, state }) {
    var vm = this;
    if (router.currentRoute.params.historyId) {
      state.form.ta_historia_id = router.currentRoute.params.historyId;
      state.form.ta_usuario_id = state.history_sel.us_idusuario;
      return await ApiService.post("/Crm_tareas/delete", state.form)
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
    } else {
      store.dispatch("notifications/NotificationToast", {
        type: "error",
        title: "Historia",
        message: "Seleccione un tipo de historia !"
      });
    }
  },
  async moveTask({ commit }, form) {
    commit("fetchingTask");

    return await ApiService.post("/Crm_tareas/move", form)
      .then(response => {
        if (response) {
          let data = response.data;
          commit("setTask", false);
        }
        return false;
      })
      .catch(err => console.log(err));
  },

  readTask({ commit }, data) {
    commit("fetchingTask");
    fetchReadTasks(data).then(jsonResponse => {
      commit("setTask", jsonResponse.data.data);
    });
  },

  add({ commit, dispatch }) {
    commit("setPending", true);
    fetchCreateTask().then(() => {
      commit("setPending", false);
      commit("hideDataDialog");
      dispatch("readTask");
    });
  },
  edit({ commit, dispatch }) {
    commit("setPending", true);
    fetchUpdateProjects().then(() => {
      commit("setPending", false);
      commit("hideDataDialog");
      dispatch("readTask");
    });
  }
};

// mutations
const mutations = {
  ["fetchingTask"](state) {
    state.pending = true;
  },
  ["setTask"](state, tasks) {
    setTimeout(function() {
      state.pending = false;
    }, 500);
  },

  SET_STATE(state, payload) {
    Object.assign(state, {
      ...payload
    });
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
