<template>
  <div style="padding: 0em" class="ftco-section">
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
            <div class="login-wrap p-3 p-lg-3">
              <div class="d-flex">
                <div class="w-100">
                  <h3 class="mb-1">My task</h3>
                  <h3 class="mb-2 crear-cuenta-txt">
                    crear cuenta de usuarios.
                  </h3>
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
              <v-form ref="form" aria-autocomplete="false" autocomplete="false">
                <v-row dense>
                  <v-col cols="12" md="12" class="py-0 ma-0 mt-0">
                    <v-autocomplete
                      v-model="Form.rempresa"
                      :items="Auth.list_empresa"
                      item-text="emp_nombre"
                      item-value="emp_idemp"
                      label="Seleccione la empresa"
                      required
                      :rules="[rules.req_empresa]"
                      :loading="Auth.loading"
                      append-icon="add"
                    ></v-autocomplete>

                    <v-text-field
                      append-icon="mdi-sticker-text-outline"
                      v-model="Form.rnombres"
                      label="Nombre completo"
                      name="rnombres"
                      ref="rnombres"
                      :rules="[rules.required]"
                      dense
                      outline
                      class="bg-color-login-txt"
                      placeholder=""
                    ></v-text-field>

                    <v-text-field
                      append-icon="mdi-email"
                      v-model="Form.rcorreo"
                      label="Correo electronico"
                      name="rcorreo"
                      ref="rcorreo"
                      :rules="emailRules"
                      dense
                      outline
                      class="bg-color-login-txt"
                      placeholder=""
                    ></v-text-field>

                    <v-text-field
                      append-icon="person"
                      v-model="Form.rusername"
                      label="Nombre de usuario"
                      name="rusername"
                      ref="rusername"
                      :rules="[rules.required]"
                      dense
                      outline
                      class="bg-color-login-txt"
                      placeholder=""
                    ></v-text-field>

                    <v-text-field
                      ref="rpassword"
                      outline
                      v-model="Form.rpassword"
                      :rules="[rules.required, rules.min]"
                      :append-icon="show1 ? 'visibility_off' : 'visibility'"
                      :type="show1 ? 'text' : 'password'"
                      name="rpassword"
                      append-outer-icon=""
                      label="Contraseña"
                      @click:append="show1 = !show1"
                      dense
                      class="bg-color-login-txt py-0 ma-0 mt-0"
                    ></v-text-field>

                    <v-text-field
                      ref="reppassword"
                      outline
                      v-model="Form.reppassword"
                      :rules="repeatPasswordRules"
                      :append-icon="show1 ? 'visibility_off' : 'visibility'"
                      :type="show1 ? 'text' : 'password'"
                      name="reppassword"
                      label="Repita su Contraseña"
                      @click:append="show1 = !show1"
                      dense
                      class="bg-color-login-txt py-0 ma-0 mt-0"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row dense>
                  <v-col cols="12" md="12" class="py-0 ma-0 mt-1">
                    <v-btn
                      color="info"
                      class="ma-0 white--text"
                      rounded
                      block
                      :loading="Auth.loadingRegistro"
                      :disabled="Auth.loadingRegistro"
                      @click.native="handleRegisterSubmit"
                    >
                      Registrar
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
                      @click.native="GoLogin"
                    >
                      Iniciar session
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
import { mapGetters, mapActions, mapState } from "vuex";
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
      show1: false,
      rules: {
        required: (value) => !!value || "Campo requerido.",
        req_empresa: (v) => !!v || "empresa requerida!",
        min: (v) => v.length >= 3 || "Min 3 characters",
        emailMatch: () => "The email and password you entered don't match",
      },
      emailRules: [
        (v) => !!v || "E-mail es requerido",
        (v) => /.+@.+\..+/.test(v) || "Email invalido.",
      ],
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
  mounted() {
    // setTimeout(function () {
    //   document
    //     .getElementsByClassName("v-form")[0]
    //     .setAttribute("autocomplete", "off");
    //   document
    //     .getElementsByClassName("v-form")[0]
    //     .setAttribute("autocomplete", "false");
    //   $(".v-form , form , input").attr("autocomplete", "off");
    //   $(".v-form , form , input").prop("autocomplete", "off");
    //   $(".v-form , form").disableAutoFill();
    // }, 500);
  },
  created() {
    this.$nextTick(function () {
      this.$refs.rusername.focus();
    });
    this.GetEmpresas();
  },
  computed: {
    ...mapGetters("auth", [
      "authenticating",
      "authenticationError",
      "authenticationErrorCode",
    ]),
    ...mapState({
      Auth: (state) => state.auth,
      Form: (state) => state.auth.FormRegister,
    }),

    repeatPasswordRules() {
      return [
        (v) => !!v || "Contraseña no ingresada",
        (v) =>
          (v && v.length >= 4) ||
          "La contraseña debe tener al menos 4 caracteres.",
        (v) => v === this.Form.rpassword || "¡Diferentes contraseñas!",
      ];
    },
  },
  methods: {
    ...mapActions("auth", ["register", "GetEmpresas"]),
    GoLogin() {
      let to = {
        name: "login",
      };
      this.$router.push(to);
    },
    handleRegisterSubmit() {
      var vm = this;
      vm.loader = "loading";
      if (this.$refs.form.validate()) {
        if (this.rusername != "" && this.rpassword != "") {
          this.register();
        }
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
  width: calc(100% - 350px);
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center;
  position: relative;
  z-index: 1;
}
/* .login-wrap {
  height: 465px;
} */
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
.crear-cuenta-txt {
  font-size: 15px;
  color: #c3c3c3;
  line-height: 1px;
}
</style>