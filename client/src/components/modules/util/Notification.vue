<template>
  <v-snackbar :timeout="timeout" v-model="snackbar">
    {{ this.$store.state.notification }}
    <v-btn icon flat color="grey lighten-2" @click.native="snackbar = false"><v-icon>close</v-icon></v-btn>
  </v-snackbar>
</template>

<script>
  export default {
    data () {
      return {
        snackbar: false,
        timeout: 7000
      }
    },
    methods: {
    },
    created () {
      this.$store.watch(
        (state) => {
          return this.$store.state.notification
        },
        (newValue, oldValue) => {
          if (newValue) {
            this.snackbar = true
            setTimeout(() => {
              this.snackbar = false
              this.$store.commit('setNotification', null)
            }, this.timeout)
          }
        }
      )
    }
  }
</script>
