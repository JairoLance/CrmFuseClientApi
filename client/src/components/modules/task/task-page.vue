<template>
  <div>
    <v-container class="grey lighten-5">
      <v-form ref="form" v-model="Form">
        <v-dialog
          scrollable
          v-model="dialog"
          max-width="700px"
          transition="dialog-bottom-transition"
          persistent
        >
          <v-card>
            <v-toolbar color="purple darken-2" class="elevation-0" dense dark>
              <v-btn icon @click.native="dialog = false">
                <v-icon>close</v-icon>
              </v-btn>
              <v-toolbar-title>
                <p class="subtitle-1 mb-0">GESTION DE TICKETS</p>
                <p class="overline mb-0 mt-0 white--text">
                  {{ Task.history_sel.us_nombres }}
                </p>
              </v-toolbar-title>
              <v-spacer></v-spacer>
              <v-toolbar-items>
                <v-btn dense icon flat @click.native="save">
                  <v-icon>cloud_upload</v-icon>
                </v-btn>

                <v-btn @click="$refs.form.reset()" dense icon flat>
                  <v-icon>mdi-cancel</v-icon>
                </v-btn>
              </v-toolbar-items>
            </v-toolbar>

            <v-card-text>
              <v-container fluid pa-0 class="mt-0">
                <v-layout pa-2 pt-2 justify-space-between wrap>
                  <v-row dense>
                    <v-col cols="12">
                      <v-autocomplete
                        v-model="Form.ta_estado"
                        :items="Task.statuses"
                        item-text="text"
                        item-value="id"
                        label="Estados del ticket "
                        required
                        :rules="[estadoRules]"
                        append-icon="add"
                      ></v-autocomplete>
                    </v-col>

                    <v-col cols="12">
                      <v-text-field
                        v-model="Form.ta_titulo"
                        autofocus
                        ref="nombre"
                        filled
                        color="deep-purple"
                        counter="100"
                        label="Titulo del tickets"
                        :rules="[tituloRules]"
                        type="text"
                      ></v-text-field>
                    </v-col>

                    <v-col cols="12">
                      <v-textarea
                        v-model="Form.ta_descripcion"
                        ref="descripcion"
                        auto-grow
                        counter="200"
                        filled
                        color="deep-purple"
                        :rules="[descripcionRules]"
                        label="Describe la historia"
                        rows="2"
                      ></v-textarea>
                    </v-col>

                    <v-col cols="12">
                      <p>Agrega un comentario</p>
                      <quill-editor
                        ref="myTextEditor"
                        v-model="Form.ta_comentarios"
                        :config="editorOption"
                      >
                      </quill-editor>
                    </v-col>
                  </v-row>
                </v-layout>
              </v-container>
            </v-card-text>
          </v-card>
        </v-dialog>
      </v-form>

      <v-row>
        <v-col cols="12" md="9">
          <v-toolbar class="elevation-0" dense>
            <v-btn
              :to="'/projects/' + Task.history_sel.hi_proyecto_id + '/historys'"
              icon
            >
              <v-icon>mdi-keyboard-backspace</v-icon>
            </v-btn>

            <v-toolbar-title>
              <p class="subtitle-1 mb-0">{{ Task.history_sel.hi_nombre }}</p>
              <p class="overline mb-0 mt-0 primary--text">
                {{ Task.history_sel.us_nombres }}
              </p>
            </v-toolbar-title>

            <div class="flex-grow-1"></div>

            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-btn
                  dense
                  color="primary"
                  v-on="on"
                  @click.native="addTask"
                  outlined
                >
                  <v-icon>mdi-plus</v-icon> crear tickets
                </v-btn>
              </template>
              <span>Nueva tarea</span>
            </v-tooltip>
          </v-toolbar>

          <!-- Columns are always 50% wide, on mobile and desktop -->
          <v-row
            style="
              display: flex;
              flex-wrap: wrap;
              flex: 1 1 auto;
              margin-right: -12px;
              margin-left: -12px;
            "
          >
            <v-layout align-start justify-center>
              <!-- <v-list nav dense>
                <v-list-item-group  color="primary">
                  <v-list-item v-for="(item, i) in blocks" :key="i">
                    <v-list-item-icon>
                      <v-icon v-text="item.id"></v-icon>
                    </v-list-item-icon>

                    <v-list-item-content>
                      <v-list-item-title v-text="item.title"></v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-item-group>
              </v-list>-->

              <div class="mt-3" v-if="!blocks.length && Task.loading">
                <v-progress-circular size="70" color="primary" indeterminate>
                  <span class="caption">Cargando</span>
                </v-progress-circular>
              </div>

              <Kanban
                v-else
                :stages="statuses"
                :blocks="blocks"
                @update-block="updateBlock"
                @edit="EditTask"
                @eliminar="EliminarTask"
              >
                <div v-for="(stage, i) in statuses" :key="i">
                  <h2>
                    {{ stage.text }}
                    <a>+</a>
                  </h2>
                </div>
                <div v-for="item in blocks" :slot="item.id" :key="item.id">
                  <div>
                    <strong>id:</strong>
                    {{ item.id }}
                  </div>
                  <div>{{ item.title }}</div>
                </div>
                <div
                  v-for="stage in statuses"
                  :key="stage.name"
                  :slot="`footer-${stage.name}`"
                >
                  <v-btn dense small class="mb-5 mt-5" color="primary" outlined>
                    <v-icon>mdi-plus</v-icon> Agregar tarea
                  </v-btn>
                </div>
              </Kanban>
            </v-layout>
          </v-row>
        </v-col>
        <v-col cols="6" md="3">
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
    <dialog-loader ref="dialogLoader"></dialog-loader>
    <confirm ref="confirm"></confirm>
  </div>
</template>

<script>
import Vue from "vue";
import { mapActions, mapMutations, mapState, mapGetters } from "vuex";
import faker from "faker";
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";
import { quillEditor } from "vue-quill-editor";
import { debounce } from "lodash";
import draggable from "vuedraggable";
import Kanban from "@/components/modules/util/Kanban";
import confirm from "@/components/util/Confirm";

const moment = require("moment");

let now = moment();

import DialogLoader from "@/components/modules/util/dialogs/DialogLoader";

export default {
  name: "Task",
  components: {
    draggable,
    DialogLoader,
    Kanban,
    quillEditor,
    confirm
  },
  data() {
    return {
      editorOption: {},
      item: 0,
      overlay: false,
      dialog: false,
      items: [
        { text: "My Files", icon: "mdi-folder" },
        { text: "Shared with me", icon: "mdi-account-multiple" },
        // { text: "Starred", icon: "mdi-star" },
        { text: "Recent", icon: "mdi-history" },
        { text: "Offline", icon: "mdi-check-circle" },
        { text: "Uploads", icon: "mdi-upload" },
        { text: "Backups", icon: "mdi-cloud-upload" },
      ],
      estadoRules: (v) => !!v || "El estado es requerido",
      tituloRules: (v) => !!v || "El titulo es requerido",
      descripcionRules: (v) => !!v || "la descripcion es requerida",
    };
  },
  mounted: function () {},
  computed: {
    ...mapState({
      blocks: (state) => state.task.list,
      statuses: (state) => state.task.statuses,
      Historys: (state) => state.historys,
      Task: (state) => state.task,
      Form: (state) => state.task.form,
    }),
  },
  methods: {
    EditTask(items) {
      this.dialog = true;
      var $this = this;

      var n = {
        ta_idta: items.id,
        ta_historia_id: null,
        ta_usuario_id: null,
        ta_tipo_tarea: 1,
        ta_titulo: items.title,
        ta_comentarios: items.comentarios,
        ta_fecha_vencimiento: now.format("YYYY-MM-DD HH:mm:ss"),
        ta_hora: now.format("YYYY-MM-DD HH:mm:ss"),
        ta_descripcion: items.name,
        ta_asignar_a: 0,
        ta_vincular_a: 0,
        ta_visible_usuarios: 0,
        ta_prioridad: 1,
        ta_estado: items.status.id,
        ta_recordatorio: "NO",
        ta_recordatorio_fecha: now.format("YYYY-MM-DD HH:mm:ss"),
        ta_archivado: 0,
        ta_fecha_actualizacion: null,
        ta_orden: 1,
      };

      setTimeout(function () {
        const dform = { ...$this.Form, ...n };
        Object.assign($this.Form, dform);
      }, 50);
    },
    EliminarTask(items) {
      var n = {
        ta_idta: items.id,
        ta_historia_id: null,
        ta_usuario_id: null,
        ta_tipo_tarea: 1,
        ta_titulo: items.title,
        ta_comentarios: items.comentarios,
        ta_fecha_vencimiento: now.format("YYYY-MM-DD HH:mm:ss"),
        ta_hora: now.format("YYYY-MM-DD HH:mm:ss"),
        ta_descripcion: items.name,
        ta_asignar_a: 0,
        ta_vincular_a: 0,
        ta_visible_usuarios: 0,
        ta_prioridad: 1,
        ta_estado: items.status.id,
        ta_recordatorio: "NO",
        ta_recordatorio_fecha: now.format("YYYY-MM-DD HH:mm:ss"),
        ta_archivado: 0,
        ta_fecha_actualizacion: null,
        ta_orden: 1,
      };

      var vm = this;
      const dform = { ...vm.Form, ...n };
      Object.assign(vm.Form, dform);
      var msn = " ";
      msn = "¿ Desea eliminar este tickets " + items.title + " ?";
      vm.$refs.confirm
        .open("Mensaje", msn, { color: "primary" })
        .then((confirm) => {
          if (confirm) {
            this.$store.dispatch("task/ELIMINAR");
          } else {
          }
        });
    },
    save() {
      if (this.$refs.form.validate()) {
        this.$store.dispatch("task/GUARDAR");
      }
    },
    ResetForm() {
      this.$refs.form.reset();
    },
    addTask() {
      this.dialog = true;
    },
    updateBlock: debounce(async function (id, status, i) {
      // console.log(this.blocks.find(b => b.id === id).status)
      var $this = this;
      let esta = this.statuses.find((b) => b.name === status);

      let data = {
        ta_historia_id: this.$route.params.historyId,
        ta_idta: id,
        ta_orden: i,
        ta_estado: esta.id,
      };

      await this.$store.dispatch("task/moveTask", data);

      await this.$store.dispatch("task/TienesTicketsAbiertos");

      //console.log(this.blocks.find(b => b.id === id))
      /* let blocs = this.blocks.find((b) => b.id === id);
      console.log("Tarea anterior " + blocs.status.name);
      let data = {
        projectId: this.$route.params.projectId,
        tarea_id: id,
        estado: status,
      };
      this.$store.dispatch("task/moveTask", data);*/
      //this.blocks.find(b => b.id === Number(id)).status = status;
    }, 300),
    addBlock: debounce(function (stage) {
      // this.blocks.push({
      //   id: this.blocks.length,
      //   status: stage,
      //   title: faker.company.bs(),
      // });
    }, 300),
  },
  async created() {
    await this.$store.dispatch("task/GetList");
    await this.$store.dispatch("task/GetHistoryId");
  },
};
</script>


<style>
.truncate {
  text-overflow: ellipsis;
  overflow: hidden;
}

.text-muted {
  color: #909090 !important;
}

.theme--light.v-card {
  background: #fff;
  box-shadow: 0 1px 15px rgba(0, 0, 0, 0.04), 0 1px 6px rgba(0, 0, 0, 0.04);
}

p.card-description {
  margin-bottom: 0px;
}

p.card-titulo {
  margin-bottom: 1px;
  line-height: 1.3rem;
  font-family: Nunito, sans-serif;
}
.v-card__title {
  padding: 2px 16px 8px;
}

.grabbable {
  cursor: move; /* fallback if grab cursor is unsupported */
  cursor: grab;
  cursor: -moz-grab;
  cursor: -webkit-grab;
}

.drag-column-header h2 {
  color: #f3f3f3;
}

.li-card-drag header,
.li-card-drag .v-toolbar__content {
  height: 32px !important;
}

.li-card-drag .v-toolbar__content,
.li-card-drag .v-toolbar__extension {
  padding: 4px 4px;
}

ul.drag-list,
ul.drag-inner-list {
  list-style-type: none;
  margin: 0;
  padding: 0;
}
.drag-container {
  max-width: 1000px;
  margin: 20px auto;
}
.drag-list {
  display: flex;
  align-items: flex-start;
}
@media (max-width: 690px) {
  .drag-list {
    display: block;
  }
}
.drag-column {
  flex: 1;
  margin: 0 10px;
  position: relative;
  background: #efefef;
  /* background: rgba(0, 0, 0, 0.2); */
  overflow: hidden;
  min-width: 215px;
}
@media (max-width: 690px) {
  .drag-column {
    margin-bottom: 30px;
  }
}
.drag-column h2 {
  font-size: 0.8rem;
  margin: 0;
  text-transform: uppercase;
  font-weight: 600;
}
.drag-column-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px;
}
.drag-inner-list {
  min-height: 50px;
  color: white;
}
.drag-item {
  padding: 10px;
  margin: 10px;
  height: 100px;
  background: rgba(0, 0, 0, 0.4);
  transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
}
.drag-item.is-moving {
  transform: scale(1.5);
  background: rgba(0, 0, 0, 0.8);
}
.drag-header-more {
  cursor: pointer;
}
.drag-options {
  position: absolute;
  top: 44px;
  left: 0;
  width: 100%;
  height: 100%;
  padding: 10px;
  transform: translateX(100%);
  opacity: 0;
  transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
}
.drag-options.active {
  transform: translateX(0);
  opacity: 1;
}
.drag-options-label {
  display: block;
  margin: 0 0 5px 0;
}
.drag-options-label input {
  opacity: 0.6;
}
.drag-options-label span {
  display: inline-block;
  font-size: 0.9rem;
  font-weight: 400;
  margin-left: 5px;
}
/* Dragula CSS  */
.gu-mirror {
  position: fixed !important;
  margin: 0 !important;
  z-index: 9999 !important;
  opacity: 0.8;
  list-style-type: none;
}
.gu-hide {
  display: none !important;
}
.gu-unselectable {
  -webkit-user-select: none !important;
  -moz-user-select: none !important;
  -ms-user-select: none !important;
  user-select: none !important;
}
.gu-transit {
  opacity: 0.2;
}

* {
  box-sizing: border-box;
}
body {
  background: #33363d;
  color: white;
  font-family: "Lato";
  font-weight: 300;
  line-height: 1.5;
  -webkit-font-smoothing: antialiased;
}

.drag-column .drag-column-header > div {
  width: 100%;
}
.drag-column .drag-column-header > div h2 > a {
  float: right;
}
.drag-column .drag-column-footer > div {
  margin-left: 10px;
}
.drag-column .drag-column-footer > div a {
  text-decoration: none;
  color: white;
}
.drag-column .drag-column-footer > div a:hover {
  text-decoration: underline;
}
.drag-column-on-hold .drag-column-header,
.drag-column-on-hold .is-moved,
.drag-column-on-hold .drag-options {
  /* background: #fb7d44; */
  background: #65bd77;
}
.drag-column-in-progress .drag-column-header,
.drag-column-in-progress .is-moved,
.drag-column-in-progress .drag-options {
  background: #2a92bf;
}
.drag-column-needs-review .drag-column-header,
.drag-column-needs-review .is-moved,
.drag-column-needs-review .drag-options {
  background: #f23925;
}
.drag-column-approved .drag-column-header,
.drag-column-approved .is-moved,
.drag-column-approved .drag-options {
  background: #00b961;
}
.section {
  padding: 20px;
  text-align: center;
}
.section a {
  color: white;
  text-decoration: none;
  font-weight: 300;
}
.section h4 {
  font-weight: 400;
}
.section h4 a {
  font-weight: 600;
}
</style>