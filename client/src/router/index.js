import Vue from "vue";
import Router from "vue-router";
import store from "@/store/index.js";
import { TokenService, TokenTurnos } from "@/services/storage.service";
import ProjectPage from "@/components/modules/projects/projects-list";
import ProjectPanelPage from "@/components/modules/projects/projects-panel";
import TaskPage from "@/components/modules/task/task-page";
import Overview from "@/views/Overview.vue";
import LoginView from "@/views/auth/login.vue";
import RegisterView from "@/views/auth/register.vue";
import HistorysList from "@/components/modules/historys/historys-list";
import TaskList from "@/components/modules/task/task-list";
Vue.use(Router);

// function TienesTicketsAbiertos(to, from, next) {
   
//   //return store.state.auth.IsOpenCash;
//   var bool = false;
//   var resp = store.dispatch("task/TienesTicketsAbiertos");
//   resp
//     .then(response => { 
//       if (response.data.data.length) {
//         bool = true;
//         next("/task-list");
//       } else {
//         bool = false;
//         next();
//       }
//     })
//     .catch(error => {
//       bool = false;
//     });
//   return bool;
// }

const router = new Router({
  routes: [
    {
      path: "/",
      name: "Projects",
      component: ProjectPage,
      // beforeEnter: TienesTicketsAbiertos
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
      meta: {
        public: true, // Allow access to even if not logged in
        onlyWhenLoggedOut: true
      }
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
      meta: {
        public: true, // Allow access to even if not logged in
        onlyWhenLoggedOut: true
      }
    },
    {
      path: "/projects/:projectId",
      name: "ProjectsPanel",
      component: ProjectPanelPage
      //meta: { requiresAuth: true },
      // children: [
      //   {
      //     path: 'task',
      //     name: 'Task',
      //     component: TaskPage
      //   },
      // ]
    },
    {
      path: "/historys/:historyId/tickets",
      name: "tickets",
      component: TaskPage
    },
    {
      path: "/projects/:projectId/historys",
      name: "historys",
      component: HistorysList
    },
    {
      path: "/task-list",
      name: "task-list",
      component: TaskList
    }
  ],
  mode: "history",
  linkActiveClass: "active"
});
//Historias

router.beforeEach((to, from, next) => {
  const isPublic = to.matched.some(record => record.meta.public);
  const onlyWhenLoggedOut = to.matched.some(
    record => record.meta.onlyWhenLoggedOut
  );
  const loggedIn = !!TokenService.getToken();

  if (!isPublic && !loggedIn) {
    return next({
      path: "/login",
      query: { redirect: to.fullPath } // Almacene la ruta completa para redirigir al usuario después de iniciar sesión
    });
  }

  // No permita que el usuario visite la página de inicio de sesión o la página de registro si ha iniciado sesión
  if (loggedIn && onlyWhenLoggedOut) {
    return next("/");
  }

  next();
});

export default router;
