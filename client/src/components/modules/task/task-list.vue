<template>
  <v-card class="mx-auto" max-width="700">
    <v-toolbar elevation="0" color="transparent" dense light>
      <v-toolbar-title>Listas de tickets </v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn
        color="success"
        @click.native="BuscarTicketsEstado(1)"
        class="mr-1"
        small
        dark
      >
        Activo
      </v-btn>

      <v-btn
        color="primary"
        @click.native="BuscarTicketsEstado(2)"
        class="mr-1"
        small
        dark
      >
        En desarrollo
      </v-btn>

      <v-btn
        color="error"
        @click.native="BuscarTicketsEstado(3)"
        class="mr-1"
        small
        dark
      >
        Finalizado
      </v-btn>

      <!-- <v-btn color="warning"  class="mr-1" small dark>
        <v-icon>mdi-plus</v-icon> crear tickets
      </v-btn> -->
    </v-toolbar>

    <v-list two-line>
      <v-container v-if="Task.listTask.length <= 0">
        <v-row dense>
          <v-col cols="12">
            <img
              style="height: 45vh; width: 100%"
              :src="require('@/assets/tasklist.svg')"
            />
          </v-col>

          <v-col class="text-center">
            <v-card
              class="pa-3 text-center"
              elevation="0"
              color="transparent"
              tile
            >
              <p class="h3 blue-black--text font-weight-black mb-0">
                {{ $store.state.auth.session.us_nombres }}
              </p>
              <p class="subtitle-2 blue-grey--text font-weight-regular mb-0">
                no tienes tickets creados.
              </p>
              <p class="subtitle-2 blue-grey--text font-weight-regular mb-2">
                comienza creando tus tickets , seleccionando el proyecto y
                creando las historias de usuarios.
              </p>
              <v-btn
                dense
                class="ma-0"
                outlined
                :to="{ name: 'Projects' }"
                color="primary"
              >
                Ir a proyectos
              </v-btn>
            </v-card>
          </v-col>
        </v-row>
      </v-container>

      <v-list-item-group active-class="pink--text" v-else multiple>
        <template v-for="(item, index) in Task.listTask">
          <v-list-item :key="item.ta_idta">
            <template v-slot:default="{ active }">
              <v-list-item-icon>
                <v-btn fab x-small dark :color="colors(item)">
                  {{ index + 1 }}
                </v-btn>
              </v-list-item-icon>
              <v-list-item-content>
                <v-list-item-title v-text="item.ta_titulo"></v-list-item-title>

                <v-list-item-subtitle
                  class="text--primary"
                  v-text="item.ta_descripcion"
                ></v-list-item-subtitle>

                <v-list-item-subtitle
                  v-text="item.ta_fecha_creacion"
                ></v-list-item-subtitle>
              </v-list-item-content>

              <v-list-item-action>
                <v-list-item-action-text
                  v-text="item.te_descripcion"
                ></v-list-item-action-text>
                <v-list-item-action-text>
                  <v-btn
                    :to="'/historys/' + item.ta_historia_id + '/tickets'"
                    :color="colors(item)"
                    outlined
                    small
                    text
                  >
                    Administrar
                  </v-btn>
                </v-list-item-action-text>
              </v-list-item-action>
            </template>
          </v-list-item>

          <v-divider
            v-if="index < Task.listTask.length - 1"
            :key="index"
          ></v-divider>
        </template>
      </v-list-item-group>
    </v-list>
  </v-card>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
export default {
  computed: {
    ...mapState({
      Task: (state) => state.task,
    }),
  },
  created() {
    this.TienesTicketsAbiertosList();
  },
  methods: {
    ...mapActions("task", ["TienesTicketsAbiertosList"]),
    BuscarTicketsEstado(estado) {
      this.TienesTicketsAbiertosList(estado);
    },
    colors(items) {
      var color = "success";
      if (items.ta_estado == 2) {
        color = "primary";
      } else if (items.ta_estado == 3) {
        color = "error";
      }
      return color;
    },
  },
};
</script>

<style>
</style>