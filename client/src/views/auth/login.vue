<template>
  <div class="ftco-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 col-lg-10">
          <div class="wrap d-md-flex">
            <div
              class="
                login100-more
                text-wrap text-center
                d-flex
                align-items-center
                order-md-last
              "
              :style="{
                'background-image': 'url(' + require('@/assets/task.png') + ')',
              }"
            >
              <!-- <div class="text w-100"> -->
              <!-- <img src="@/assets/task.svg" /> -->
              <!-- <h2>Welcome to login</h2>
                <p>Don't have an account?</p>
                <a href="#" class="btn btn-white btn-outline-white">Sign Up</a> -->
              <!-- </div> -->
            </div>
            <div class="login-wrap p-4 p-lg-5">
              <div class="d-flex">
                <div class="w-100">
                  <h3 class="mb-4">My task</h3>
                </div>
                <div class="w-100">
                  <p class="social-media d-flex justify-content-end">
                    <a
                      href="#"
                      class="
                        social-icon
                        d-flex
                        align-items-center
                        justify-content-center
                      "
                      ><span class="mdi mdi-facebook"></span
                    ></a>
                    <a
                      href="#"
                      class="
                        social-icon
                        d-flex
                        align-items-center
                        justify-content-center
                      "
                      ><span class="mdi mdi-twitter"></span
                    ></a>
                  </p>
                </div>
              </div>
              <v-form autocomplete="off">
                <v-row dense>
                  <v-col cols="12" md="12" class="py-0 ma-0 mt-0">
                    <div class="form-group mb-0">
                      <label class="label" for="username"
                        >Nombre de usuario</label
                      >
                      <v-text-field
                        append-icon="person"
                        v-model="username"
                        name="username"
                        ref="username"
                        hint="Al menos 3 caracteres"
                        dense
                        small
                        outlined
                        class="bg-color-login-txt"
                        rounded
                      ></v-text-field>
                    </div>

                    <div class="form-group mb-0">
                      <label class="label" for="password">Contraseña</label>
                      <v-text-field
                        ref="password"
                        v-model="password"
                        :rules="[rules.required, rules.min]"
                        :append-icon="show1 ? 'visibility_off' : 'visibility'"
                        :type="show1 ? 'text' : 'password'"
                        name="input-10-1"
                        hint="Al menos 3 caracteres"
                        @click:append="show1 = !show1"
                        dense
                        small
                        outlined
                        class="bg-color-login-txt py-0 ma-0 mt-0"
                        rounded
                      ></v-text-field>
                    </div>
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col cols="12" md="12" class="py-0 ma-0 mt-0">
                    <v-btn
                      color="info"
                      class="ma-0 white--text"
                      rounded
                      block
                      :loading="loading"
                      :disabled="loading"
                      @click.native="handleSubmit"
                    >
                      Conectar
                      <v-icon dense right dark>mdi-cloud-upload</v-icon>
                    </v-btn>
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col cols="12" md="12" class="py-0 ma-0 mt-0 text-center">
                    <div
                      class="
                        grey--text
                        text--lighten-1 text-center text-body-2
                        mt-3
                        mb-0
                      "
                    >
                      ¿ Ya tienes una cuenta ?
                    </div>
                    <v-btn
                      class="ma-0"
                      text
                      dense
                      color="primary"
                      plain
                      @click.native="GoRegister"
                    >
                      Registrate ahora
                    </v-btn>
                  </v-col>
                </v-row>
              </v-form>
            </div>
          </div>
        </div>
      </div>
    </div>
    <v-snackbar
      v-model="noti.snackbar"
      :color="noti.color"
      :multi-line="noti.mode === 'multi-line'"
      :timeout="noti.timeout"
      :vertical="noti.mode === 'vertical'"
    >
      {{ noti.text }}
      <v-btn dark flat @click="noti.snackbar = false">Close</v-btn>
    </v-snackbar>
    <NotificationRed />
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import NotificationRed from "@/components/modules/util/notifications/notifications-red";

export default {
  components: {
    NotificationRed,
  },
  data() {
    return {
      loader: null,
      loading: false,
      noti: {
        snackbar: false,
        color: "error",
        mode: "",
        timeout: 3000,
        text: "",
      },
      msg: "Hello World!",
      img: "./assets/logo.png",
      login_toolbar_principal: true,
      username: "",
      password: "",
      show1: false,
      rules: {
        required: (value) => !!value || "Required.",
        min: (v) => v.length >= 3 || "Min 3 characters",
        emailMatch: () => "The email and password you entered don't match",
      },
    };
  },
  watch: {
    loader() {
      const l = this.loader;
      this[l] = !this[l];

      setTimeout(() => (this[l] = false), 1000);

      this.loader = null;
    },
  },
  created() {
    this.$nextTick(function () {
      this.$refs.username.focus();
    });
  },
  computed: {
    ...mapGetters("auth", [
      "authenticating",
      "authenticationError",
      "authenticationErrorCode",
    ]),
  },
  methods: {
    ...mapActions("auth", ["login"]),
    GoRegister() {
      let to = {
        name: "register",
      };
      this.$router.push(to);
    },
    handleSubmit() {
      var vm = this;
      vm.loader = "loading";
      if (this.username != "" && this.password != "") {
        const response = this.login({
          username: this.username,
          password: this.password,
        });

        response
          .then((response) => {
            if (response.type == "error") {
              this.noti.snackbar = true;
              this.noti.color = response.type;
              this.noti.text = response.message;
            } else {
              this.password = "";
              this.username = "";
            }
          })
          .catch((error) => {
            this.password = "";
            this.username = "";
          });
      }
    },
    ...mapActions("functions", ["toggleSidebar"]),
    FocusPass() {
      this.$nextTick(function () {
        this.$refs.password.focus();
      });
    },
  },
};
</script>
<style scoped>
@import "./bootstrap.css";
.login100-more {
  width: calc(100% - 300px);
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  position: relative;
  z-index: 1;
}
.login-wrap {
  height: 465px;
}
.v-btn--rounded {
  border-radius: 28px;
}

.v-text-field.v-text-field--enclosed .v-text-field__details {
  margin-bottom: 0px;
}

.v-text-field.v-text-field--enclosed .v-text-field__details,
.v-text-field.v-text-field--enclosed > .v-input__control > .v-input__slot {
  padding: 0 5px;
}
input:-internal-autofill-selected {
  appearance: menulist-button;
  background-image: none !important;
  background-color: #fff !important;
  color: -internal-light-dark(black, white) !important;
}
</style>