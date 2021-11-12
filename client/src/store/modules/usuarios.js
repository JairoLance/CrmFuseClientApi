//import { fetchReadProjects,post,PostD,get } from "../../services/api.service";
import ApiService from "../../services/api.service";
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
    us_id: null,
    us_usuario: "",
    us_clave: "",
    us_nombres: "",
    us_email: "",
    us_rol_id: null,
    us_imagen: "",
    us_estado: 0
  },
  SelectProject: "",
  isEditMode: false,
  dataDialog: false,
  deleteDialog: false,
  listUsuario: [],
  listUsuarioLoading: false
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
  async GetListUsuarios({ commit, state }) {
    var response;

    commit("SET_STATE", {
      listUsuarioLoading: true
    });
    response = ApiService.post("/Crm_usuarios/getAll", {});
    response
      .then(res => {
        let data = res.data.data; 
        if (data) {
          commit("SET_STATE", {
            listUsuario: data,
            listUsuarioLoading: false
          });
        } else {
        }
      })
      .catch(err => {});
    return response;
  },

  addOrEdit({ state, commit, dispatch }) {
    if (state.isEditMode === true) {
      dispatch("edit");
    } else {
      dispatch("add");
    }
  },
  readUsuarios({ commit }) {
    commit("fetchingUsuarios");
    fetchReadProjects().then(jsonResponse => {
      commit("setUsuarios", jsonResponse.data.data);
    });
  },
  readUsuariosTipos({ commit }) {
    fetchReadUsuariosTipos().then(jsonResponse => {
      commit("setUsuariosTipos", jsonResponse.data.data);
    });
  },

  add({ commit, dispatch }) {
    commit("setPending", true);
    fetchCreateUsuarios().then(() => {
      commit("setPending", false);
      commit("hideDataDialog");
      dispatch("readUsuarios");
    });
  },
  edit({ commit, dispatch }) {
    commit("setPending", true);
    fetchUpdateUsuarios().then(() => {
      commit("setPending", false);
      commit("hideDataDialog");
      dispatch("readUsuarios");
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
