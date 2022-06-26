import Vue from "vue";
import VueRouter from "vue-router";
import Home from "../views/HomeView.vue";
import Login from "./routes/auth/login";
import Register from "./routes/auth/register";
import Logout from "./routes/auth/logout";
import ManageLeaders from "./routes/Admin/manageLeaders";
Vue.use(VueRouter);

const routes = [
  {
    path: "/",
    name: "Home",
    component: Home,
  },
  {
    path: "/about",
    name: "About",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/About.vue"),
  },
  ...Login,
  ...Register,
  ...Logout,
  ...ManageLeaders,
];

const router = new VueRouter({
  mode: "history",
  routes,
});

export default router;
