import store from "../../index";
import ApiService from "../../../services/api.service";

export function fetchReadUsuarios() {
  return ApiService.post("/usuarios/list", "");
}

export function fetchReadUsuariosTipos() {
  return ApiService.post("/usuarios/list_tipo_proyecto", "");
}

export function fetchOrdenUsuarios(data) {
  return ApiService.post(+"/usuarios/orden", data);
}

export function fetchCreateUsuarios() {
  let state = store.state.usuarios;
  return ApiService.post("/usuarios/insert", {
    us_id: state.selectedItem.us_id,
    us_usuario: state.selectedItem.us_usuario,
    us_clave: state.selectedItem.us_clave,
    us_nombres: state.selectedItem.us_nombres,
    us_email: state.selectedItem.us_email,
    us_rol_id: state.selectedItem.us_rol_id,
    us_imagen: state.selectedItem.us_imagen,
    us_estado: state.selectedItem.us_estado
  });
}

export function fetchUpdateUsuarios() {
  let state = store.state.usuarios;
  return ApiService.post("/usuarios/update", {
    us_id: state.selectedItem.us_id,
    us_usuario: state.selectedItem.us_usuario,
    us_clave: state.selectedItem.us_clave,
    us_nombres: state.selectedItem.us_nombres,
    us_email: state.selectedItem.us_email,
    us_rol_id: state.selectedItem.us_rol_id,
    us_imagen: state.selectedItem.us_imagen,
    us_estado: state.selectedItem.us_estado
  });
}

export function fetchDeleteProjects() {
  let state = store.state.usuarios;
  return ApiService.post("/usuarios/update", {
    us_id: state.selectedItem.us_id
  });
}
