<template>
  <div>
    <v-container class="grey lighten-5">
      <!-- Stack the columns on mobile by making one full-width and the other half-width -->
      <v-row>
        <v-col cols="12" md="8">
          <v-toolbar class="elevation-0" dense>
            <v-btn to="/" icon>
              <v-icon>mdi-keyboard-backspace</v-icon>
            </v-btn>

            <v-toolbar-title>{{ Project.cp_nombre }}</v-toolbar-title>

            <div class="flex-grow-1"></div>

            <v-btn dense color="primary" @click.native="AddHistory" outlined>
              <v-icon>mdi-plus</v-icon> crear historia
            </v-btn>
          </v-toolbar>

          <!-- Columns are always 50% wide, on mobile and desktop -->
          <v-row
            style="
              display: flex;
              flex-wrap: wrap;
              flex: 1 1 auto;
              margin-right: -12px;
              margin-left: -12px;
              justify-content: flex-start;
            "
          >
            <div class="pa-5" v-for="(items, index) in List" :key="index">
              <v-hover>
                <template v-slot:default="{ hover }">
                  <v-card
                    class="mx-auto"
                    max-width="244"
                    :loading="Historys.loading"
                  >
                    <template slot="progress">
                      <v-overlay
                        absolute
                        class="d-flex flex-column text-center"
                      >
                        <div>
                          <v-progress-circular
                            size="75"
                            color="white"
                            :value="Historys.loading"
                            indeterminate
                          >
                            <span>Cargando</span>
                          </v-progress-circular>
                        </div>
                      </v-overlay>
                    </template>
                    <v-img :src="historyimage"> </v-img>
                    <a href="#" style :class="`details_1`">{{
                      items.activo
                    }}</a>
                    <a href="#" style :class="`details_2`">{{
                      items.desarrollo
                    }}</a>
                    <a href="#" style :class="`details_3`">{{
                      items.finalizado
                    }}</a>

                    <v-card-text style="padding: 5px 16px 0px 16px">
                      <h2 class="text-h5 primary--text mb-1">
                        {{ items.hi_nombre }}
                      </h2>
                      <p class="text-ellps">
                        {{ items.hi_descripcion }}
                      </p>
                    </v-card-text>

                    <v-card-title cl style="padding: 0px 16px 5px 16px">
                      <v-row>
                        <v-col class="text-left pb-0 mt-0 pt-1" cols="10">
                          <small
                            style="font-size: 0.5em; line-height: 10px"
                            class="primary--text mb-0 pt-1"
                            >Asignado</small
                          >
                          <h1 style="line-height: 10px" class="overline pt-0">
                            {{ items.us_nombres }}
                          </h1>
                        </v-col>
                      </v-row>
                      <v-row>
                        <v-col class="text-left pt-2" cols="4">
                          <h1 class="overline">{{ index + 1 }}</h1>
                        </v-col>
                        <v-col class="text-right pt-2" cols="8">
                          <h1 class="overline">
                            {{ items.hi_fecha_creacion }}
                          </h1>
                        </v-col>
                      </v-row>
                    </v-card-title>

                    <v-fade-transition>
                      <v-overlay v-if="hover" absolute color="#036358">
                        <v-btn
                          color="primary"
                          @click.native="EditHistory(items)"
                          class="mb-1"
                          block
                        >
                          <v-icon left>mdi-archive-edit-outline</v-icon> Editar
                        </v-btn>
                        <v-btn
                          color="success"
                          @click.native="GoTickets(items)"
                          class="mb-1"
                          block
                        >
                          <v-icon left>mdi-archive-plus-outline</v-icon> Tickets
                        </v-btn>
                        <v-btn
                          @click.native="DeleteHistory(items)"
                          color="danger"
                          class="mb-1"
                          block
                        >
                          <v-icon left>mdi-delete-sweep</v-icon> Eliminar
                        </v-btn>
                      </v-overlay>
                    </v-fade-transition>
                  </v-card>
                </template>
              </v-hover>
            </div>

            <!-- <v-col v-for="(items, index) in menu_dashboard" :key="index">
              <v-hover>
                <template v-slot:default="{ hover }">
                  <v-card
                    class="v-card v-card--outlined v-sheet theme--light pa-0"
                    style="width: 230px"
                    outlined
                    tile
                  >
                    <div>
                      <router-link
                        :to="'/projects/' + items.to.params.projectId + '/task'"
                        class="
                          v-list-item--doc
                          v-list-item v-list-item--link
                          theme--light
                        "
                        :tabindex="'ind-' + `${index}`"
                      >
                        <v-list-item-avatar :color="items.color">
                          <v-img
                            class="icon_avatar"
                            v-if="items.icon_img"
                            :src="items.icon_img"
                          ></v-img>
                          <v-btn v-else dark icon>
                            <v-icon v-text="items.icon"></v-icon>
                          </v-btn>
                        </v-list-item-avatar>

                        <v-list-item-content>
                          <v-list-item-title class="subtitle-2 mb-1">{{
                            items.titulo
                          }}</v-list-item-title>
                          <v-list-item-subtitle class="caption">{{
                            items.text
                          }}</v-list-item-subtitle>
                        </v-list-item-content>

                        <v-fade-transition>
                          <v-overlay
                            v-if="hover"
                            absolute
                            :color="items.hover_color"
                          >
                            <v-btn
                              :color="items.color"
                              :to="{ name: 'Task' }"
                              >{{ items.to.name }}</v-btn
                            >
                             
                          </v-overlay>
                        </v-fade-transition>
                      </router-link>
                    </div>
                  </v-card>
                </template>
              </v-hover>
            </v-col> -->
          </v-row>
        </v-col>
        <v-col cols="6" md="4">
          <v-card class="mx-auto" width="256" tile>
            <v-navigation-drawer permanent>
              <v-system-bar></v-system-bar>
              <v-list nav dense>
                <v-list-item-group v-model="item" color="primary">
                  <v-list-item v-for="(item, i) in items" :key="i">
                    <v-list-item-icon>
                      <v-icon v-text="item.icon"></v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-title v-text="item.text"></v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>
            </v-navigation-drawer>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <v-form ref="form" v-model="Form">
      <v-dialog
        scrollable
        v-model="dialog"
        max-width="410px"
        transition="dialog-bottom-transition"
        persistent
      >
        <v-card>
          <v-toolbar color="purple darken-2" class="elevation-0" dense dark>
            <v-btn icon @click.native="dialog = false">
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>GESTION DE HISTORIAS</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dense icon flat @click="save">
                <v-icon>cloud_upload</v-icon>
              </v-btn>

              <v-btn @click="$refs.form.reset()" dense icon flat>
                <v-icon>mdi-cancel</v-icon>
              </v-btn>
            </v-toolbar-items>
          </v-toolbar>

          <v-card-text>
            <v-container fluid pa-0 class="mt-0">
              <v-layout pa-4 pt-6 justify-space-between wrap>
                <v-flex style="display: block" row>
                  <v-autocomplete
                    v-model="Form.hi_usuario_asignado_id"
                    :items="Usuario.listUsuario"
                    item-text="us_nombres"
                    item-value="us_idusuario"
                    label="Seleccione el usuario"
                    :rules="[usuarioRules]"
                    required
                    :loading="Usuario.listUsuarioLoading"
                    append-icon="add"
                  ></v-autocomplete>
                </v-flex>

                <v-flex style="display: block" row>
                  <v-text-field
                    v-model="Form.hi_nombre"
                    autofocus
                    ref="nombre"
                    filled
                    :rules="[nombreRules]"
                    color="deep-purple"
                    counter="100"
                    label="Nombre de la historia"
                    style="min-height: 96px"
                    type="text"
                  ></v-text-field>
                </v-flex>

                <v-flex row>
                  <v-textarea
                    v-model="Form.hi_descripcion"
                    ref="descripcion"
                    auto-grow
                    counter="200"
                    filled
                    color="deep-purple"
                    label="Describe la historia"
                    rows="2"
                  ></v-textarea>
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-form>
    <confirm ref="confirm"></confirm>
  </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import confirm from "@/components/util/Confirm";
export default {
  components: {
    confirm,
  },
  mounted() {},
  computed: {
    ...mapState({
      Form: (state) => state.historys.form,
      List: (state) => state.historys.list,
      Usuario: (state) => state.usuarios,
      Project: (state) => state.historys.project_sel,
      Historys: (state) => state.historys,
    }),
    open() {
      return this.$store.state.sidebarOpen;
    },
  },
  created() {
    this.$store.dispatch("historys/GetProjectId");
    this.$store.dispatch("historys/GetList");
    this.$store.dispatch("usuarios/GetListUsuarios");
  },
  data() {
    return {
      item: 0,
      overlay: false,
      dialog: false,
      historyimage: require("@/assets/tasklist.svg"),
      items: [
        { text: "Mis archivos", icon: "mdi-folder" },
        { text: "Shared with me", icon: "mdi-account-multiple" },
        // { text: "Starred", icon: "mdi-star" },
        { text: "Recent", icon: "mdi-history" },
        { text: "Offline", icon: "mdi-check-circle" },
        { text: "Subir", icon: "mdi-upload" },
        { text: "Copias", icon: "mdi-cloud-upload" },
      ],

      nombreRules: (v) => !!v || "El nombre es requerido",
      usuarioRules: (v) => !!v || "El usuario es requerido",
    };
  },
  methods: {
    save() {
      if (this.$refs.form.validate()) {
        this.$store.dispatch("historys/GUARDAR");
      }
    },
    ResetForm() {
      this.$refs.form.reset();
    },
    AddHistory() {
      this.dialog = true;
      this.ResetForm();
    },
    GoTickets(items) {
      let to = {
        // name: "ProjectsPanel",
        name: "tickets",
        params: {
          historyId: items.hi_idhi,
          projectId: this.$route.params.projectId,
        },
      };
      this.$router.push(to);
    },
    DeleteHistory(items) {
      var vm = this;
      const dform = { ...vm.form, ...items };
      Object.assign(vm.Form, dform);
      var msn = " ";
      msn = "¿ Desea eliminar esta historia " + items.hi_nombre + " ?";
      vm.$refs.confirm
        .open("Mensaje", msn, { color: "primary" })
        .then((confirm) => {
          if (confirm) {
            this.$store.dispatch("historys/DELETE");
          } else {
          }
        });
    },
    EditHistory(items) {
      this.dialog = true;
      var $this = this;
      setTimeout(function () {
        const dform = { ...$this.form, ...items };
        Object.assign($this.Form, dform);
      }, 50);
    },
  },
};
</script>

<style>
.icon_avatar .v-image__image {
  height: 26px !important;
  width: 26px;
  top: 5px !important;
  left: 7px;
}

.details_1 {
  height: 23px;
  min-width: 23px;
  line-height: 23px;
  font-size: 14px;
  text-decoration: none;
}

.details_1 {
  position: absolute;
  top: -8px;
  left: -8px;
  color: #65bd77 !important;
  height: 30px;
  min-width: 30px;
  line-height: 29px;
  border: 2px solid #65bd77 !important;
  text-align: center;
  background-color: white;
  border-radius: 50% !important;
  font-size: 12px;
  z-index: 2;
}

.details_2 {
  height: 23px;
  min-width: 23px;
  line-height: 23px;
  font-size: 14px;
  text-decoration: none;
}

.details_2 {
  position: absolute;
  top: 30px;
  left: -8px;
  color: #406be1 !important;
  height: 30px;
  min-width: 30px;
  line-height: 29px;
  border: 2px solid #406be1 !important;
  text-align: center;
  background-color: white;
  border-radius: 50% !important;
  font-size: 12px;
  z-index: 2;
}

.details_3 {
  height: 23px;
  min-width: 23px;
  line-height: 23px;
  font-size: 14px;
  text-decoration: none;
}

.details_3 {
  position: absolute;
  top: 70px;
  left: -8px;
  color: #f23925 !important;

  height: 30px;
  min-width: 30px;
  line-height: 29px;
  border: 2px solid #f23925 !important;

  text-align: center;
  background-color: white;
  border-radius: 50% !important;
  font-size: 12px;
  z-index: 2;
}

.text-ellps {
  display: -webkit-box;
  max-width: 100%;
  margin: 0 auto;
  -webkit-line-clamp: 3;
  /* autoprefixer: off */
  -webkit-box-orient: vertical;
  /* autoprefixer: on */
  overflow: hidden;
  text-overflow: ellipsis;
  height: 70px;
  margin-bottom: 1px !important;
  line-height: 1.23em;
}
</style>

<codepen-resources lang="json">
  {
    "js": [
      "https://cdn.jsdelivr.net/npm/vuelidate/dist/vuelidate.min.js",
      "https://cdn.jsdelivr.net/npm/vuelidate/dist/validators.min.js"
    ]
  }
</codepen-resources>

<codepen-additional>
  const { required, maxLength, email } = validators
  const validationMixin = vuelidate.validationMixin

  Vue.use(vuelidate.default)
</codepen-additional>