import store from "../../index";
import ApiService from "../../../services/api.service";

export function fetchReadProjects() {
  return ApiService.post("/projects/list", "");
}

export function fetchReadProjectsTipos() {
  return ApiService.post("/projects/list_tipo_proyecto", "");
}

export function fetchOrdenProjects(data) {
  return ApiService.post("/projects/orden", data);
}

export function fetchCreateProjects() {
  let state = store.state.projects;
  return ApiService.post("/projects/insert", {
    cp_nombre: state.selectedItem.cp_nombre,
    cp_descripcion: state.selectedItem.cp_descripcion,
    cp_orden: state.selectedItem.cp_orden || "ultimo",
    cp_estado: state.selectedItem.cp_estado,
    cp_id_tipo_proyecto: state.selectedItem.cp_id_tipo_proyecto
  });
}

export function fetchUpdateProjects() {
  let state = store.state.projects;
  return ApiService.post("/projects/update", {
    cp_idcp: state.selectedItem.cp_idcp,
    cp_nombre: state.selectedItem.cp_nombre,
    cp_descripcion: state.selectedItem.cp_descripcion,
    cp_orden: state.selectedItem.cp_orden || "ultimo",
    cp_estado: state.selectedItem.cp_estado,
    cp_id_tipo_proyecto: state.selectedItem.cp_id_tipo_proyecto
  });
}

export function fetchDeleteProjects() {
  let state = store.state.projects;
  return ApiService.post("/projects/update", {
    cp_idcp: state.selectedItem.cp_idcp
  });
}
