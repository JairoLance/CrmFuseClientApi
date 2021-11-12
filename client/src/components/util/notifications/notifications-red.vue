<template>
  <v-snackbar v-model="Notificaciones.notification" color="error" :top="'top'">
    {{ Notificaciones.message }}

    <v-btn dark text @click.native="Logout"> Cerrar session </v-btn>
    <!-- <v-btn color="default" dark text> Iniciar session </v-btn> -->
  </v-snackbar>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
export default {
  name: "NotificationsRed",
  computed: {
    ...mapState({
      Notificaciones: (state) => state.notifications,
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