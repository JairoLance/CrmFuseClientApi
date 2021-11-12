<template>
  <v-snackbar
    v-model="Notificaciones.estado"
    :color="Notificaciones.type"
    :top="'top'"
    :timeout="6000"
    style="padding: 0px"
  >
    <v-card style="width: 100%; padding: 0px" dark color="transparent" outlined>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title v-text="Notificaciones.title"></v-list-item-title>
          <v-list-item-subtitle
            color="white"
            v-text="Notificaciones.message"
          ></v-list-item-subtitle>
        </v-list-item-content>
        <v-list-item-action>
          <v-btn dark text @click="Notificaciones.estado = false">
            Cerrar
          </v-btn>
        </v-list-item-action>
      </v-list-item>
    </v-card>
  </v-snackbar>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
export default {
  name: "NotificationsToast",
  computed: {
    ...mapState({
      Notificaciones: (state) => state.notifications.toast,
    }),
  },
  methods: {
    ...mapActions("auth", ["logout"]),
    Logout() {
      var vm = this;
      vm.logout();
    },
  },
};
</script>

<style>
</style>