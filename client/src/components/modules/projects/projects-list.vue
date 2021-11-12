<template>
  <div>
    <v-container v-if="projects.length <= 0">
      <v-row dense>
        <v-col cols="12">
          <img
            style="height: 55vh; width: 100%"
            :src="require('@/assets/create_projects_equipo.svg')"
          />
        </v-col>

        <v-col class="text-center">
          <v-card
            class="pa-2 text-center"
            elevation="0"
            color="transparent"
            tile
          >
            <p class="h3 blue-black--text font-weight-black mb-0">
              {{ $store.state.auth.session.us_nombres }}
            </p>
            <p class="h6 blue-grey--text font-weight-regular mb-0">
              no tienes proyectos creados.
            </p>
            <p class="h6 blue-grey--text font-weight-regular mb-2">
              comienza creando tus tipos de proyectos ya sea personal o en
              equipos.
            </p>
            <v-btn dense class="ma-0" outlined @click="addItem" color="primary">
              Crear proyecto
            </v-btn>
          </v-card>
        </v-col>
      </v-row>
    </v-container>

    <v-tabs
      v-else
      class="elevation-0"
      v-model="activeTab"
      background-color="white"
    >
      <v-tab :href="`#tab-1`">Proyectos personales</v-tab>
      <v-tab :href="`#tab-2`">Proyectos de equipos</v-tab>
      <!-- https://medium.com/@cmdlhz/vuetify-part-2-of-3-2dd96eb7c926 -->
      <v-tab-item key="1" :value="'tab-1'">
        <v-container text-xs-right fluid pa-10>
          <v-row
            style="
              display: flex;
              flex-wrap: wrap;
              flex: 1 1 auto;
              margin-right: -12px;
              margin-left: -12px;
            "
          >
            <template v-for="(item, index) in GettProjectsInd">
              <v-hover :key="'ind-' + `${item.cp_idcp}` + `${index}`">
                <template v-slot:default="{ hover }">
                  <v-card
                    style="margin-right: 10px; margin-bottom: 5px"
                    class="m-auto elevation-1"
                    min-width="214"
                    max-width="214"
                  >
                    <v-row
                      v-if="$store.state.projects.pending"
                      class="fill-height"
                      align-content="center"
                      justify="center"
                    >
                      <v-col class="subtitle-1 text-center" cols="12"
                        >Cargando...</v-col
                      >
                      <v-col cols="6">
                        <v-progress-linear
                          color="purple lighten-3"
                          indeterminate
                          rounded
                          height="6"
                        ></v-progress-linear>
                      </v-col>
                    </v-row>
                    <template v-else>
                      <v-img
                        aspect-ratio="1"
                        :src="images.create_project"
                      ></v-img>

                      <v-card-text>
                        <h2 class="subtitle-2 primary--text">
                          {{ item.cp_nombre }}
                        </h2>

                        <span
                          v-if="item.cp_descripcion"
                          class="caption grey--text"
                          >{{ item.cp_descripcion }}</span
                        >
                        <span v-else class="caption grey--text">
                          <div
                            style="
                              width: 100%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                          <div
                            style="
                              width: 90%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                          <div
                            style="
                              width: 80%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                          <div
                            style="
                              width: 70%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                          <div
                            style="
                              width: 60%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                        </span>
                      </v-card-text>
                      <v-card-title>
                        <v-rating
                          :value="4"
                          dense
                          color="orange"
                          background-color="orange"
                          hover
                          class="mr-2"
                        ></v-rating>
                        <span class="primary--text subtitle-2">64 Tareas</span>
                      </v-card-title>
                      <v-fade-transition>
                        <v-overlay v-if="hover" absolute color="#036358">
                          <v-btn
                            color="primary"
                            block
                            class="mb-2"
                            @click="editItem(item)"
                            >Editar proyecto</v-btn
                          >
                          <!-- <v-btn color="success" block class="mb-2"
                            >Crear tickets</v-btn
                          > -->
                          <v-btn
                            color="purple"
                            block
                            class="mb-2"
                            @click="RouterAdmin(item)"
                            >Crear historia</v-btn
                          >
                        </v-overlay>
                      </v-fade-transition>
                    </template>
                  </v-card>
                </template>
              </v-hover>
            </template>
          </v-row>
        </v-container>
      </v-tab-item>

      <v-tab-item key="2" :value="'tab-2'">
        <v-container text-xs-right fluid pa-10>
          <v-row
            style="
              display: flex;
              flex-wrap: wrap;
              flex: 1 1 auto;
              margin-right: -12px;
              margin-left: -12px;
            "
          >
            <template v-for="(item, index) in GettProjects">
              <v-hover :key="'pe-' + `${item.cp_idcp}` + `${index}`">
                <template v-slot:default="{ hover }">
                  <v-card
                    style="margin-right: 10px; margin-bottom: 5px"
                    class="m-auto elevation-1"
                    min-width="214"
                    max-width="214"
                  >
                    <v-row
                      v-if="$store.state.projects.pending"
                      class="fill-height"
                      align-content="center"
                      justify="center"
                    >
                      <v-col class="subtitle-1 text-center" cols="12"
                        >Cargando...</v-col
                      >
                      <v-col cols="6">
                        <v-progress-linear
                          color="purple lighten-3"
                          indeterminate
                          rounded
                          height="6"
                        ></v-progress-linear>
                      </v-col>
                    </v-row>
                    <template v-else>
                      <v-img
                        aspect-ratio="1"
                        :src="images.create_project"
                      ></v-img>

                      <v-card-text class="card-text-descripcion">
                        <h2 class="subtitle-2 primary--text">
                          {{ item.cp_nombre }}
                        </h2>

                        <span
                          v-if="item.cp_descripcion"
                          class="caption grey--text text-descripcion"
                        >
                          <div class="text ellipsis">
                            <span class="text-concat">{{
                              item.cp_descripcion
                            }}</span>
                          </div>
                        </span>

                        <span v-else class="caption grey--text">
                          <div
                            style="
                              width: 100%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                          <div
                            style="
                              width: 90%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                          <div
                            style="
                              width: 80%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                          <div
                            style="
                              width: 70%;
                              height: 12px;
                              margin-bottom: 2px;
                              background-color: #e6e6e6;
                              display: block;
                            "
                          ></div>
                        </span>
                      </v-card-text>

                      <v-card-actions class="footer-card">
                        <div class="text-center">
                          <v-badge overlap color="purple darken-1">
                            <template v-slot:badge>
                              <span>{{ item.task_count }}</span>
                            </template>
                            <v-icon large color="purple lighten-3"
                              >mdi-clipboard-check</v-icon
                            >
                          </v-badge>
                        </div>
                        <div class="flex-grow-1"></div>
                        <v-btn icon>
                          <v-icon>mdi-heart</v-icon>
                        </v-btn>
                        <v-btn icon>
                          <v-icon>mdi-share-variant</v-icon>
                        </v-btn>
                      </v-card-actions>
                      <v-fade-transition>
                        <v-overlay v-if="hover" absolute color="#036358">
                          <v-btn
                            color="primary"
                            block
                            class="mb-2"
                            @click="editItem(item)"
                            >Editar proyecto</v-btn
                          >
                          <!-- <v-btn color="success" block class="mb-2"
                            >Crear tickets</v-btn
                          > -->
                          <v-btn
                            color="purple"
                            block
                            class="mb-2"
                            @click="RouterAdmin(item)"
                          >
                            <!-- :to="{name: 'ProjectsPanel', params: { projectId: item.cp_idcp , name : item.cp_nombre }}" -->
                            Crear historia
                          </v-btn>
                        </v-overlay>
                      </v-fade-transition>
                    </template>

                    <!-- <v-divider class="mt-6 mx-4"></v-divider>

                    <v-card-text>
                      <v-chip class="mr-2">
                        <v-icon left>mdi-brightness-5</v-icon>Turn on
                      </v-chip>
                      <v-chip class="mr-2"  >
                        <v-icon left>mdi-alarm-check</v-icon>Tareas
                      </v-chip>
                    </v-card-text>-->
                  </v-card>
                </template>
              </v-hover>
            </template>
          </v-row>
          <!-- </draggable> -->
        </v-container>
      </v-tab-item>
    </v-tabs>

    <v-form ref="form" v-model="form">
      <v-dialog
        scrollable
        v-model="dataDialog"
        max-width="410px"
        transition="dialog-bottom-transition"
        persistent
      >
        <v-card>
          <v-toolbar color="purple darken-2" class="elevation-0" dense dark>
            <v-btn icon @click.native="hideDataDialog">
              <v-icon>close</v-icon>
            </v-btn>
            <v-toolbar-title>GESTION DE PROYECTO</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-btn dense icon flat @click="addOrEdit">
                <v-progress-circular
                  v-show="this.pending === true"
                  indeterminate
                  color="white"
                ></v-progress-circular>

                <v-icon v-show="this.pending === false">cloud_upload</v-icon>
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
                  <v-select
                    v-model="selectedItem.cp_id_tipo_proyecto"
                    :items="projects_tipo"
                    item-text="pt_descripcion"
                    item-value="pt_idpt"
                    label="Seleccione el tipo de proyecto"
                    required
                    append-icon="add"
                    @change="setTipoProyecto"
                    :loading="$store.state.projects.pending"
                  ></v-select>
                </v-flex>

                <v-flex style="display: block" row>
                  <v-text-field
                    v-model="selectedItem.cp_nombre"
                    @change="setNombre"
                    autofocus
                    ref="nombre"
                    :rules="[rules.cp_nombre]"
                    filled
                    color="deep-purple"
                    counter="100"
                    label="Nombre del proyecto"
                    style="min-height: 96px"
                    @keydown.enter="FocusDescripcion"
                    type="text"
                  ></v-text-field>
                </v-flex>

                <v-flex row>
                  <v-textarea
                    v-model="selectedItem.cp_descripcion"
                    ref="descripcion"
                    @change="setDescripcion"
                    auto-grow
                    counter="200"
                    filled
                    color="deep-purple"
                    label="Descripcion del proyecto"
                    @keydown.enter="FocusEstado"
                    rows="2"
                  ></v-textarea>
                </v-flex>
                <v-flex xs12 sm12 row>
                  <v-checkbox
                    v-model="selectedItem.cp_estado"
                    @change="setEstado"
                    ref="estado"
                    color="deep-purple"
                  >
                    <template v-slot:label></template>
                  </v-checkbox>
                  <v-subheader style="margin-top: 7px"
                    >Habilitacion del proyecto</v-subheader
                  >
                </v-flex>
              </v-layout>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-form>

    <v-btn bottom color="primary" dark fab fixed right @click="addItem">
      <v-icon>add</v-icon>
    </v-btn>
  </div>
</template>

<script>
import { mapActions, mapMutations, mapState, mapGetters } from "vuex";
import Draggable from "vuedraggable";

export default {
  name: "projects",
  components: {
    Draggable,
  },
  data() {
    return {
      hover: true,
      //activeTab: "tab-2",
      valid: false,
      dialog: false,
      items: [
        { pt_idpt: "1", pt_descripcion: "PROYECTOS PERSONALES" },
        { pt_idpt: "2", pt_descripcion: "PROYECTOS DEL EQUIPO" },
      ],
      images: {
        //create_project: require("./assets/create_project.png")
        create_project: require("@/assets/create_projects_equipo.svg"),
        create_project_ind: require("@/assets/create_projects_ind.svg"),
        logo: require("@/assets/logo3.png"),
      },
      form: "",
      // cp_nombre: "",
      // cp_descripcion: "",
      // phone: undefined,
      rules: {
        email: (v) => (v || "").match(/@/) || "Please enter a valid email",
        length: (len) => (v) =>
          (v || "").length >= len ||
          `Longitud de caracteres inválida, necesario ${len}`,
        required: (v) => !!v || "Este campo es obligatorio",
      },
    };
  },
  methods: {
    ...mapMutations({
      setNombre: "projects/setNombre",
      setDescripcion: "projects/setDescripcion",
      setEstado: "projects/setEstado",
      setOrden: "projects/setOrden",
      setTipoProyecto: "projects/setTipoProyecto",
      setProjects: "projects/setProjects",
      setSelectedItem: "projects/setSelectedItem",
      setEditMode: "projects/setEditMode",
      setPending: "projects/setPending",
      hideDeleteDialog: "projects/hideDeleteDialog",
      showDeleteDialog: "projects/showDeleteDialog",
      hideDataDialog: "projects/hideDataDialog",
      showDataDialog: "projects/showDataDialog",
    }),

    ...mapActions({
      readProjects: "projects/readProjects",
      readProjectsTipos: "projects/readProjectsTipos",
      addOrEdit: "projects/addOrEdit",
      deleteProjects: "projects/deleteProjects",
    }),
    RouterAdmin(item) {
      this.$store.commit("projects/setProjectsTitle", item.cp_nombre);
      let to = {
        // name: "ProjectsPanel",
        name: "historys",
        params: {
          projectId: item.cp_idcp,
          name: item.cp_nombre,
        },
      };
      this.$router.push(to);
    },

    editItem(item) {
      this.$nextTick(() => this.$refs.nombre.focus());
      this.setSelectedItem(item);
      this.setEditMode(true);
      this.showDataDialog();
    },
    addItem() {
      this.$nextTick(() => this.$refs.nombre.focus());
      this.setSelectedItem({ cp_nombre: "", cp_descripcion: "" });
      this.setEditMode(false);
      this.showDataDialog();
    },
    deleteItem(item) {
      this.setSelectedItem(item);
      this.showDeleteDialog();
    },
    FocusDescripcion() {
      this.$nextTick(() => this.$refs.descripcion.focus());
    },
    FocusEstado() {
      this.$nextTick(() => this.$refs.estado.focus());
    },
    handleDragEnd() {
      console.log(this.futureIndex);
      // this.futureItem = this.$store.state.projects.projects[this.futureIndex]
      // this.movingItem = this.$store.state.projects.projects[this.movingIndex]
      // const _items = Object.assign([], this.$store.state.projects.projects)
      // _items[this.futureIndex] = this.movingItem
      // _items[this.movingIndex] = this.futureItem

      // //this.items = _items;

      // console.log(_items[this.futureIndex])
    },
    handleMove(e) {
      const { index, futureIndex } = e.draggedContext;
      console.log("ProyectoId " + e.draggedContext.element.cp_idcp);
      console.log("nuevaposicion " + futureIndex);

      var data = {
        proyecto: e.draggedContext.element.cp_idcp,
        posicion: futureIndex,
        sw: "pilas",
      };

      this.$store
        .dispatch("projects/UpdateOrden", data)
        .then((res) => {})
        .catch((err) => {});

      this.movingIndex = index;
      this.futureIndex = futureIndex;
    },
    sortProjects(e) {
      const { index, futureIndex } = e.draggedContext;
      const _items = Object.assign([], this.$store.state.projects.projects);

      const cambiado_por = index;
      const puesto_actual = futureIndex;

      console.log(
        "Movia a " +
          _items[puesto_actual].cp_nombre +
          " Nueva pos " +
          puesto_actual
      );
      console.log(
        "Por  " + _items[cambiado_por].cp_nombre + " Nueva pos " + cambiado_por
      );
    },
  },
  mounted: function () {
    this.readProjectsTipos();
    this.readProjects();
  },
  computed: {
    ...mapState({
      projects: (state) => state.projects.projects,
      projects_tipo: (state) => state.projects.projects_tipo,
      activeTab: (state) => state.projects.activeTab,
      pending: (state) => state.projects.pending,
      selectedItem: (state) => state.projects.selectedItem,
      isEditMode: (state) => state.projects.isEditMode,
      dataDialog: (state) => state.projects.dataDialog,
      deleteDialog: (state) => state.projects.deleteDialog,
    }),
    ...mapGetters({
      GettProjectsInd: "projects/GettProjectsInd",
      GettProjects: "projects/GettProjects",
    }),
  },
};
</script>

<style scoped>
/* .card-text-descripcion .text-descripcion {
  overflow: auto !important;
  height: 120px !important;
  text-overflow: ellipsis;
}

.card-text-descripcion .text-descripcion::after {
  content: "...";
  position: absolute;
  right: -12px; 
  bottom: 4px;
} */
.text {
  position: relative;
  width: 175px; /* Could be anything you like. */
}

.text-concat {
  overflow: hidden;
  text-overflow: ellipsis;
  display: -webkit-box;
  line-height: 16px;
  height: 49px;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
}

.footer-card {
  padding: 2px;
}
</style>
