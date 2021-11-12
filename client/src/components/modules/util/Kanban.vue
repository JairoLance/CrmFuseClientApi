<template>
  <div class="drag-container">
    <ul class="drag-list">
      <li
        v-for="stage in stages"
        class="drag-column"
        :class="{ ['drag-column-' + stage.name]: true }"
        :key="stage.name"
      >
        <span class="drag-column-header">
          <slot :name="stage.name">
            <h2>{{ stage.text }}</h2>
          </slot>
        </span>
        <div class="drag-options">kk</div>
        <ul class="drag-inner-list" ref="list" :data-status="stage.name">
          <li
            style="padding: 7px"
            v-for="block in getBlocks(stage.name)"
            :data-block-id="block.id"
            :key="block.id"
          >
            <v-card
              min-width="170"
              class="mx-auto elevation-0 mt-2 li-card-drag"
            >
              <v-toolbar
                class="elevation-0"
                style="border-bottom: 1px solid #e6e6e6"
                dense
              >
                <v-toolbar-title class="body-1 text-left li-card-header">
                  <v-icon>{{ block.icon }}</v-icon>
                  {{ block.name }}
                </v-toolbar-title>

                <div class="flex-grow-1"></div>

                <v-menu bottom right>
                  <template v-slot:activator="{ on }">
                    <v-btn icon v-on="on">
                      <v-icon>mdi-dots-vertical</v-icon>
                    </v-btn>
                  </template>

                  <v-list nav dense>
                    <v-list-item-group dense v-model="item" color="primary">
                      <v-list-item
                        v-for="(item, i) in items_opciones"
                        @click="item.actions(block)"
                        :key="i"
                      >
                        <v-list-item-icon>
                          <v-icon v-text="item.icon"></v-icon>
                        </v-list-item-icon>

                        <v-list-item-content>
                          <v-list-item-title
                            v-text="item.text"
                          ></v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list-item-group>
                  </v-list>
                </v-menu>
              </v-toolbar>
              <v-card-title class="title grabbable">
                <v-row class="fill-height flex-column" justify="space-between">
                  <p class="mt-2 caption text-left card-titulo">
                    {{ block.title }}
                  </p>

                  <div>
                    <!-- <p class="ma-0 body-1 font-weight-bold font-italic text-left">ddddd</p> -->
                    <p
                      class="
                        caption
                        truncate
                        text-muted text-small
                        mb-1
                        font-weight-light
                        card-description
                      "
                    >
                      {{ block.ta_descripcion }}
                    </p>
                  </div>
                </v-row>
              </v-card-title>
            </v-card>
          </li>
        </ul>
        <div class="drag-column-footer">
          <slot :name="`footer-${stage.name}`"></slot>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import dragula from "dragula";
export default {
  name: "KanbanBoard",
  props: {
    stages: {},
    blocks: [],
  },
  data() {
    return {
      hover: false,
      item: 0,
      items_opciones: [
        {
          text: "Editar",
          icon: "mdi-file-document-edit",
          actions: (v) => this.Editar(v),
        },
        // { text: "Duplicar", icon: "mdi-content-duplicate" },
        // { text: "Archivar", icon: "mdi-delete-sweep" },
        {
          text: "Eliminar",
          icon: "mdi-delete",
          actions: (v) => this.Eliminar(v),
        },
      ],
    };
  },
  computed: {
    localBlocks() {
      return this.blocks;
    },
  },
  methods: {
    Editar(item) {
      this.$emit("edit", item);
    },
    Eliminar(item) {
      this.$emit("eliminar", item);
    },
    getBlocks(status) {
      let filter = this.localBlocks.filter(
        (block) => block.status.name === status
      );
      return filter;
    },
  },
  mounted() {
    dragula(this.$refs.list)
      .on("drag", (el) => {
        el.classList.add("is-moving");
      })
      .on("drop", (block, list) => {
        let index = 0;
        for (index = 0; index < list.children.length; index += 1) {
          if (list.children[index].classList.contains("is-moving")) break;
        }
        //console.log(block.dataset.blockId)
        //console.log(list.dataset.status)
        this.$emit(
          "update-block",
          block.dataset.blockId,
          list.dataset.status,
          index
        );
      })
      .on("dragend", (el) => {
        el.classList.remove("is-moving");
        window.setTimeout(() => {
          el.classList.add("is-moved");
          window.setTimeout(() => {
            el.classList.remove("is-moved");
          }, 600);
        }, 100);
      });
  },
};
</script>