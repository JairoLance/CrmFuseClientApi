<template>
  <div>
    <draggable v-model="$store.state.projects.projects" @sort="sortProjects">
      <v-hover
        v-for="(item, index) in $store.state.projects.projects"
        :key="'pe-'+`${item.cp_idcp}`+ `${index}` "
      >
        <template v-slot:default="{ hover }">
          <v-card
            style="margin-right: 10px; margin-bottom:5px;"
            class="m-auto elevation-1"
            min-width="220"
            max-width="220"
          >
            <v-img aspect-ratio="1" :src="images.create_project"></v-img>

            <v-card-text>
              <h2 class="subtitle-2 primary--text">{{ item.cp_nombre }}</h2>

              <span v-if="item.cp_descripcion" class="caption grey--text">{{item.cp_descripcion}}</span>
              <span v-else class="caption grey--text">
                <div
                  style="width:100%; height:12px; margin-bottom:2px; background-color:#e6e6e6; display:block;"
                ></div>
                <div
                  style="width:90%; height:12px; margin-bottom:2px; background-color:#e6e6e6; display:block;"
                ></div>
                <div
                  style="width:80%; height:12px; margin-bottom:2px; background-color:#e6e6e6; display:block;"
                ></div>
                <div
                  style="width:70%; height:12px; margin-bottom:2px; background-color:#e6e6e6; display:block;"
                ></div>
                <div
                  style="width:60%; height:12px; margin-bottom:2px; background-color:#e6e6e6; display:block;"
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
                <v-btn color="primary" block class="mb-2">Editar proyecto</v-btn>
                <v-btn color="success" block>Crear tarea</v-btn>
              </v-overlay>
            </v-fade-transition>
          </v-card>
        </template>
      </v-hover>
    </draggable>
  </div>
</template>

<script>
import Draggable from "vuedraggable";

export default {
  components: {
    Draggable
  },
  data() {
    return {
      hover: true,
      
        //create_project: require("./assets/create_project.png")
        create_project: require("@/assets/create_projects_equipo.svg"),
        create_project_ind: require("@/assets/create_projects_ind.svg"),
        logo: require("@/assets/logo3.png")
      
    };
  },
  methods: {
    sortProjects() {
      // this.$store.state.projects.forEach((project, index) => {
      //   firebase.firestore().doc('users/' + this.$store.state.authUser.uid + '/projects/' + project.id)
      //     .update({
      //       'order': index
      //     })
      // })
      // this.$store.commit('setNotification', 'Projects sorted successfully')
    }
  }
};
</script>

<style scoped>
</style>
