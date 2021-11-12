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

            <v-toolbar-title>{{this.$store.state.projects.SelectProject}}</v-toolbar-title>

            <div class="flex-grow-1"></div>

            <v-btn icon>
              <v-icon>mdi-magnify</v-icon>
            </v-btn>

            <v-btn icon>
              <v-icon>mdi-heart</v-icon>
            </v-btn>

            <v-btn icon>
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </v-toolbar>

          <!-- Columns are always 50% wide, on mobile and desktop -->
          <v-row
            style="display: flex;flex-wrap: wrap;flex: 1 1 auto;margin-right: -12px;margin-left: -12px;"
          >
            <v-col v-for="(items,index) in menu_dashboard" :key="index">
              <v-hover>
                <template v-slot:default="{ hover }">
                  <v-card
                    class="v-card v-card--outlined v-sheet theme--light pa-0"
                    style="width:230px;"
                    outlined
                    tile
                  >
                    <div>
                      <router-link
                        :to="'/projects/'+items.to.params.projectId+'/task'"
                        
                        class="v-list-item--doc v-list-item v-list-item--link theme--light"
                        :tabindex="'ind-'+`${index}` "
                      >
                        <!-- <div class="v-avatar v-list-item__avatar" :color="items.color">
                          <v-btn dark icon>
                            <v-icon>{{items.icon}}</v-icon>
                          </v-btn>
                        </div>-->

                        <v-list-item-avatar :color="items.color">
                          <v-img class="icon_avatar" v-if="items.icon_img" :src="items.icon_img"></v-img>
                          <v-btn v-else dark icon>
                            <v-icon v-text="items.icon"></v-icon>
                          </v-btn>
                        </v-list-item-avatar>

                        <v-list-item-content>
                          <v-list-item-title class="subtitle-2 mb-1">{{items.titulo}}</v-list-item-title>
                          <v-list-item-subtitle class="caption">{{items.text}}</v-list-item-subtitle>
                        </v-list-item-content>

                        <!-- <div class="v-list-item__content">
                          <div class="v-list-item__title">
                            <span>{{items.titulo}}</span>
                          </div>
                          <div class="v-list-item__subtitle">
                            <span>{{items.text}}</span>
                          </div>
                        </div>-->
                        <!---->
                        <!-- <div class="v-list-item__action">
                          <i
                            aria-hidden="true"
                            class="v-icon notranslate mdi mdi-arrow-right theme--light"
                          ></i>
                        </div>-->
                        <v-fade-transition>
                          <v-overlay v-if="hover" absolute :color="items.hover_color">
                            <v-btn
                              :color="items.color"
                               :to="{ name: 'Task'}"
                             
                            >{{items.to.name}}</v-btn>
                            <!-- 
                              :to="{ name: 'Task', params: { id: `${items.to.params.projectId}` }}"
                             
                              :to="{name: `${items.to.name}`, params: { projectId:`${items.to.params.projectId}`, name : items.to.params.name }}"
                            -->
                          </v-overlay>
                        </v-fade-transition>
                      </router-link>
                    </div>
                  </v-card>
                </template>
              </v-hover>
            </v-col>
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
    <!-- <main class="page-content">
      <router-view />
    </main> -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      item: 0,
      overlay: false,
      items: [
        { text: "My Files", icon: "mdi-folder" },
        { text: "Shared with me", icon: "mdi-account-multiple" },
        // { text: "Starred", icon: "mdi-star" },
        { text: "Recent", icon: "mdi-history" },
        { text: "Offline", icon: "mdi-check-circle" },
        { text: "Uploads", icon: "mdi-upload" },
        { text: "Backups", icon: "mdi-cloud-upload" }
      ],
      menu_dashboard: [
        {
          color: "blue lighten-1",
          titulo: "Usuarios",
          text: "Usuarios",
          icon: "mdi-account-multiple",
          hover_color: "blue lighten-5",
          to: {
            name: "Usuarios",
            params: {
              projectId: this.$route.params.projectId,
              name: this.$route.params.name
            }
          }
        },
        {
          color: "purple darken-1",
          titulo: "Tareas",
          text: "Tareas",
          icon: "mdi-clipboard-check", //mdi-checkbox-marked-outline
          hover_color: "purple lighten-5",
          to: {
            name: "Task",
            params: {
              projectId: this.$route.params.projectId,
              name: this.$route.params.name
            }
          }
        },
        {
          color: "pink darken-1",
          titulo: "Clientes",
          text: "Clientes",
          icon: "mdi-human-child",
          hover_color: "pink lighten-5",
          to: {
            name: "Clientes",
            params: {
              projectId: this.$route.params.projectId,
              name: this.$route.params.name
            }
          }
        },
        {
          color: "yellow darken-3",
          titulo: "Ideas",
          text: "Ideas",
          icon: "mdi-lightbulb-outline",
          // icon_img: require("@/assets/idea.png"),
          hover_color: "yellow lighten-5",
          to: {
            name: "Ideas",
            params: {
              projectId: this.$route.params.projectId,
              name: this.$route.params.name
            }
          }
        },
        {
          color: "teal darken-3",
          titulo: "Cronograma",
          text: "organice su calendario",
          icon: "mdi-calendar-clock",
          // icon_img: require("@/assets/idea.png"),
          hover_color: "teal lighten-5",
          to: {
            name: "Cronograma",
            params: {
              projectId: this.$route.params.projectId,
              name: this.$route.params.name
            }
          }
        },
        {
          color: "deep-orange darken-3",
          titulo: "Sugerencias",
          text: "Alertas y tickets",
          icon: "mdi-facebook-messenger",
          // icon_img: require("@/assets/idea.png"),
          hover_color: "deep-orange lighten-5",
          to: {
            name: "Cronograma",
            params: {
              projectId: this.$route.params.projectId,
              name: this.$route.params.name
            }
          }
        }
      ]
    };
  }
};
</script>

<style>
.icon_avatar .v-image__image {
  height: 26px !important;
  width: 26px;
  top: 5px !important;
  left: 7px;
}
</style>