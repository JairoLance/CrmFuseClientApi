import Vue from "vue";
import Vuex from "vuex";

import modules from "./modules";

Vue.use(Vuex);

const store = new Vuex.Store({
  modules:modules
})


export default store
// import Vue from 'vue'
// import Vuex from 'vuex'


// Vue.use(Vuex)

// const store = new Vuex.Store({
//   state: {
//     drawer : null,
//     toolbarTitle: null,  
//   },
//   getters: {  // send state to a component
//     toggleDrawer: state => {
//       return state.drawer
//     }
//   },
//   mutations: {
//     setToolbarTitle (state, text) {
//       state.toolbarTitle = text
//     },
//     navDrawer: (state, command) => {
//       command === 'open' ? state.drawer = true : state.drawer = false;
//     },
//     toggleDrawer: state => {
//       state.drawer = !state.drawer  
//       console.log(state.drawer)    
//     },
//   },
//   actions: { // modify state aschronously
//     navDrawer: ({commit}, command) => {
//       commit('navDrawer', command)
//     }
//   }
// })

// export default store
