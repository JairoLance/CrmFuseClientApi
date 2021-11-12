const state = {
  notification: false,
  message: null,
  toast: {
    type: "error",
    title: "",
    message: "",
    estado: false
  }
};

// getters
const getters = {
  sidebarOpen: state => state.sidebarOpen
};

// actions
const actions = {
  toggleNotification({ commit, state }, items) {
    commit("setNotification", items);
  },
  NotificationToast({ commit, state }, items) {
    commit("setNotificationToast", items);
  }
};

// mutations
const mutations = {
  setNotification(state, items) {
    state.message = items.message;
    state.notification = !state.notification;
  },
  setNotificationToast(state, items) {
    state.toast.type = items.type;
    state.toast.title = items.title;
    state.toast.message = items.message;
    state.toast.estado = !state.toast.estado;
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
