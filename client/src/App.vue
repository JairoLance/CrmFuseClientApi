<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      :width="$vuetify.breakpoint.width <= 320 ? 280 : 270"
      absolute
      temporary
      app
      left
      light
      v-if="$store.state.auth.accessToken"
      mini-variant.sync="false"
    >
      <v-list-item dense>
        <v-list-item-content>
          <v-list-item-title class="text-h6">
            {{ $store.state.auth.session.us_usuario }}
          </v-list-item-title>
          <v-list-item-subtitle>
            {{ $store.state.auth.session.us_nombres }}
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense nav>
        <v-list-item
          v-for="item in itemsmenu"
          :key="item.title"
          :to="{ name: item.name }"
          link
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title color="primary">{{
              item.title
            }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      :clipped-left="$vuetify.breakpoint.lgAndUp"
      app
      dense
      color="blue darken-3"
      class="elevation-0"
      dark
      v-if="$store.state.auth.accessToken"
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
      <v-toolbar-title style="width: 300px" class="ml-0 pl-4">
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title
              style="line-height: 14px; margin-top: 5px"
              class="subtitle-1"
              >{{
              $store.state.auth.session.emp_nombre }}</v-list-item-title
            >
            <v-list-item-subtitle class="subtitle-2">{{
              $store.state.auth.session.us_nombres
            }}</v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>

        <!-- <span class="hidden-sm-and-down">Taski</span>
        <span style="display:block" class="hidden-sm-and-down"></span> -->
      </v-toolbar-title>

      <v-text-field
        flat
        dense
        solo-inverted
        hide-details
        prepend-inner-icon="search"
        label="Search"
        class="hidden-sm-and-down"
      ></v-text-field>
      <div class="flex-grow-1"></div>
      <v-btn :to="{ name: 'Projects' }" class="ml-2" icon>
        <v-icon>home</v-icon>
      </v-btn>
      <!-- Task.stateTicketsOpen -->

      <v-badge v-if="Task.stateTicketsOpen <= 0" dot>
        <v-btn :to="{ name: 'task-list' }" small icon>
          <v-icon>mdi-file-document</v-icon>
        </v-btn>
      </v-badge>
      <v-tooltip v-else color="success" bottom>
        <template v-slot:activator="{ on, attrs }">
          <v-badge dense color="success" class="blink" dot left>
            <template v-slot:badge>
              <span>{{ Task.stateTicketsOpen }}</span>
            </template>
            <v-btn
              v-bind="attrs"
              v-on="on"
              :to="{ name: 'task-list' }"
              small
              icon
            >
              <v-icon>mdi-file-document</v-icon>
            </v-btn>
          </v-badge>
        </template>
        <span>Tickets activos ({{ Task.stateTicketsOpen }}) </span>
      </v-tooltip>

      <!-- <v-btn icon large>
        <v-avatar size="32px" item>
          <v-img :src="images.logo" alt="Taski"></v-img>
        </v-avatar>
      </v-btn> -->
      <v-btn
        dense
        style="cursor: pointer"
        @click.native="Salir"
        dark
        icon
        ripple
        ><v-icon left>mdi-power</v-icon></v-btn
      >
    </v-app-bar>

    <v-content>
      <v-container fluid>
        <v-slide-y-transition mode="out-in">
          <v-layout row>
            <v-flex xs12 sm12 md12 pa-4>
              <router-view></router-view>
            </v-flex>
          </v-layout>
        </v-slide-y-transition>
      </v-container>
    </v-content>
    <confirm ref="confirm"></confirm>
    <NotificationRed />
    <NotificationToast />
  </v-app>
</template>

<script>
import confirm from "@/components/util/Confirm";
import { mapActions, mapMutations, mapState } from "vuex";
import NotificationRed from "@/components/util/notifications/notifications-red";
import NotificationToast from "@/components/util/notifications/notifications-toast";
import ProjectsList from "@/components/modules/projects/projects-list";

export default {
  components: {
    ProjectsList,
    confirm,
    NotificationRed,
    NotificationToast,
  },
  props: {
    source: String,
  },
  computed: {
    ...mapState({
      Task: (state) => state.task,
    }),
  },
  created() {
    this.TienesTicketsAbiertos();
  },
  methods: {
    ...mapActions("auth", ["logout"]),
    ...mapActions("task", ["TienesTicketsAbiertos"]),
    toggleFullScreen() {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen();
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        }
      }
    },
    Logout() {
      var vm = this;
      vm.logout();
    },
    Salir() {
      var vm = this;
      var msn = " ";
      msn = "¿ Desea cerrar session ?";
      vm.$refs.confirm
        .open("Mensaje", msn, { color: "primary" })
        .then((confirm) => {
          if (confirm) {
            vm.logout();
          } else {
          }
        });
    },
  },
  mounted: function () {},

  data: () => ({
    itemsmenu: [
      { title: "Proyectos", icon: "mdi-home", name: "Projects" },
      // { title: "Historias", icon: "mdi-file-document", name: "task-list" },
      {
        title: "Tickets",
        icon: "mdi-file-document-multiple-outline",
        name: "task-list",
      },
      {
        title: "Usuarios",
        icon: "mdi-account-multiple-plus-outline",
        name: "task-list",
      },
    ],
    drawer: null,
    images: {
      //create_project: require("./assets/create_project.png")
      create_project: require("@/assets/create_projects_equipo.svg"),
      create_project_ind: require("@/assets/create_projects_ind.svg"),
      logo: require("@/assets/logo3.png"),
    },
    dialog: false,
    drawer: null,
    items: [
      { icon: "contacts", text: "Contacts" },
      { icon: "history", text: "Frequently contacted" },
      { icon: "content_copy", text: "Duplicates" },
      {
        icon: "keyboard_arrow_up",
        "icon-alt": "keyboard_arrow_down",
        text: "Labels",
        model: true,
        children: [{ icon: "add", text: "Create label" }],
      },
      {
        icon: "keyboard_arrow_up",
        "icon-alt": "keyboard_arrow_down",
        text: "More",
        model: false,
        children: [
          { text: "Import" },
          { text: "Export" },
          { text: "Print" },
          { text: "Undo changes" },
          { text: "Other contacts" },
        ],
      },
      { icon: "settings", text: "Settings" },
      { icon: "chat_bubble", text: "Send feedback" },
      { icon: "help", text: "Help" },
      { icon: "phonelink", text: "App downloads" },
      { icon: "keyboard", text: "Go to the old version" },
    ],
  }),
};
</script>


<style>
.v-text-field.v-text-field--solo .v-input__control {
  min-height: 32px;
  padding: 0;
}
.v-tabs-items {
  height: calc(100vh - 138px) !important;
}
.v-item-group {
  margin-bottom: 10px;
}
.v-image {
  max-height: 130px !important;
}
.v-badge--left .v-badge__badge {
  left: -9px;
  right: auto;
}
.v-badge__badge.success {
  animation: blink-animation 1s steps(5, start) infinite;
  -webkit-animation: blink-animation 1s steps(5, start) infinite;
}
@keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
@-webkit-keyframes blink-animation {
  to {
    visibility: hidden;
  }
}
</style>