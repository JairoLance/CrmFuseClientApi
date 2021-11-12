//import { fetchReadProjects,post,PostD,get } from "../../services/api.service";
import store from "@/store/index.js";
import {
  fetchReadProjects,
  fetchCreateProjects,
  fetchUpdateProjects,
  fetchOrdenProjects,
  fetchReadProjectsTipos
} from "./models/projects_model";

const state = {
  projects: [],
  projects_tipo: [],
  pending: false,
  activeTab: "tab-2",
  selectedItem: {
    cp_idcp: null,
    cp_nombre: null,
    cp_descripcion: null,
    cp_orden: null,
    cp_estado: null,
    cp_id_tipo_proyecto: null
  },
  SelectProject: "",
  // itemTipoProyecto : [
  //     { pt_idpt : 1 , pt_descripcion : "PROYECTOS PERSONALES" },
  //     { pt_idpt : 2 , pt_descripcion : "PROYECTOS DEL EQUIPO" }
  // ],
  isEditMode: false,
  dataDialog: false,
  deleteDialog: false
};

// getters
const getters = {
  /*Una vez cargado la lista de projectos podemos filtrar por los tipos de proyectos*/
  /*Proyectos de tipo independiente == 1*/
  GettProjectsInd: state => {
    return state.projects.filter(f => f.cp_id_tipo_proyecto === "1");
  },
  /*Proyectos de equipos == 2*/
  GettProjects: state => {
    return state.projects.filter(f => f.cp_id_tipo_proyecto === "2");
  }
};

// actions
const actions = {
  addOrEdit({ state, commit, dispatch }) {
    if (state.isEditMode === true) {
      dispatch("edit");
    } else {
      dispatch("add");
    }
  },
  readProjects({ commit }) {
    commit("fetchingProjects");
    fetchReadProjects().then(jsonResponse => {
      commit("setProjects", jsonResponse.data.data);
    });
  },
  readProjectsTipos({ commit }) {
    fetchReadProjectsTipos().then(jsonResponse => {
      commit("setProjectsTipos", jsonResponse.data.data);
    });
  },
  UpdateOrden({ commit, store }, data) {
    //commit('fetchingProjects');
    fetchOrdenProjects(data).then(jsonResponse => {
      // commit('setProjects', jsonResponse.data.data)
    });
  },
  add({ commit, dispatch }) {
    commit("setPending", true);
    fetchCreateProjects().then(resp => {
      var data = resp.data;
      if (data.response) {
        commit("hideDataDialog");
        dispatch("readProjects");
      } else {
        store.dispatch("notifications/NotificationToast", {
          type: data.type,
          title: data.title,
          message: data.message
        });
      }
      commit("setPending", false);
    });
  },
  edit({ commit, dispatch }) {
    commit("setPending", true);
    fetchUpdateProjects().then(() => {
      commit("setPending", false);
      commit("hideDataDialog");
      dispatch("readProjects");
    });
  },
  SetProject({ commit }) {}
};

// mutations
const mutations = {
  ["fetchingProjects"](state) {
    state.pending = true;
  },
  ["setProjects"](state, projects) {
    setTimeout(function() {
      state.pending = false;
    }, 500);
    state.projects.length = 0;
    state.projects = projects;
  },
  ["setProjectsTipos"](state, projects_tipo) {
    state.projects_tipo = projects_tipo;
  },

  ["setSelectedItem"](state, selectedItem) {
    state.selectedItem = selectedItem;
  },
  ["setNombre"](state, nombre) {
    state.selectedItem.cp_nombre = nombre;
  },
  ["setDescripcion"](state, descripcion) {
    state.selectedItem.cp_descripcion = descripcion;
  },
  ["setOrden"](state, orden) {
    state.selectedItem.cp_orden = orden;
  },
  ["setEstado"](state, estado) {
    state.selectedItem.cp_estado = estado;
  },
  ["setTipoProyecto"](state, tipo) {
    state.selectedItem.cp_id_tipo_proyecto = tipo;
    state.activeTab = "tab-" + tipo;
  },
  ["setEditMode"](state, editMode) {
    state.isEditMode = editMode;
  },
  ["hideDataDialog"](state) {
    state.dataDialog = false;
  },
  ["showDataDialog"](state) {
    state.dataDialog = true;
  },
  ["setProjectsTitle"](state, projects) {
    state.SelectProject = projects;
  }
};

export default {
  state,
  getters,
  actions,
  mutations
};
