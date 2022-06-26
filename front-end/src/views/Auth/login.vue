<template>
  <v-app id="inspire">
    <v-main class="text-right">
      <v-container fluid>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12 rounded-lg" shaped>
              <v-toolbar dark>
                <v-toolbar-title>تسجيل الدخول</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <b-alert
                  :show="alert.dismissCountDown"
                  dismissible
                  :variant="alert.variant"
                  @dismissed="alert.dismissCountDown = 0"
                >
                  <p>{{ alert.msg }}</p>
                </b-alert>
                <v-form @submit.prevent="Login" id="login-form">
                  <v-text-field
                    prepend-icon="person"
                    name="البريد الإلكتروني"
                    label="البريد الإلكتروني"
                    :error-messages="emailErrors"
                    v-model="email"
                    type="text"
                  ></v-text-field>
                  <v-text-field
                    id="password"
                    :error-messages="passwordErrors"
                    v-model="password"
                    prepend-icon="lock"
                    name="كلمة المرور"
                    label="كلمة المرور"
                    type="password"
                  ></v-text-field>
                </v-form>
                <v-card-actions>
                  <v-spacer></v-spacer>
                   <span class="caption">
          هل لديك حساب ?
          <router-link color="primary" to="/register"
            >صنع حساب</router-link
          ></span
        >
                  <v-btn color="success" form="login-form" type="submit"
                    >التسجيل</v-btn
                  >
                </v-card-actions>
              </v-card-text>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import { mapActions } from "vuex";
import { required, minLength, email } from "vuelidate/lib/validators";

export default {
  validations: {
    email: {
      required,
      email,
      async exists(value) {
        if (value === "") {
          return true;
        }
        const response = await this.$axios(`/unique/${value}`);
        return response.status == 201 || value == "";
      },
    },
    password: { required, minLength: minLength(8) },
  },
  data() {
    return {
      email: "",
      password: "",
      alert: {
        dismissCountDown: null,
        variant: null,
        msg: null,
      },
    };
  },
  created() {
    if (typeof this.$route.params.msg!=="undefined") {
      this.alert.dismissCountDown = 10;
      this.alert.variant = this.$route.params.variant??"danger";
      this.alert.msg = this.$route.params.msg;
    }
  },
  methods: {
    ...mapActions(["login"]),
    Login() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.login({ email: this.email, password: this.password }).then(() => {
          if (this.$store.getters.authStatus == 2) {
            this.alert.dismissCountDown = 10;
            this.alert.variant = "danger";
            this.alert.msg = this.$store.getters.authMessage;
          }
        });
      }
    },
    post() {
      this.$v.$touch();
    },
  },
  computed: {
    passwordErrors() {
      const errors = [];
      if (!this.$v.password.$dirty) return errors;
      !this.$v.password.required &&
        errors.push("الرجاء ادخال بكلمة المرور");
      !this.$v.password.minLength &&
        errors.push("الرجاء ادخال بكلمة المرور بحد اقل من 8 حروف");
      return errors;
    },
    emailErrors() {
      const errors = [];
      if (!this.$v.email.$dirty) return errors;
      !this.$v.email.email && errors.push("الرجاء ادخال بريد صحيح");
      !this.$v.email.required && errors.push("الرجاء ادخال بريد الكتروني");
      !this.$v.email.exists && errors.push("البريد الالكتروني غير مسجل");
      return errors;
    },
  },
};
</script>

<style></style>
