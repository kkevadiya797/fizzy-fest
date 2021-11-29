import Vue from "vue";
import VueRouter from "vue-router";
import VueAxios from "vue-axios";
import Axios from "axios";
import { store } from "./store/store";

// dashboard
import AdminDashboard from "./views/admin/dashboard/Dashboard.vue";

// User Layouts
import LayoutLogin from "./views/layouts/LayoutLogin.vue";
import LayoutBasic from "./views/layouts/LayoutBasic.vue";
import LayoutPlay from "./views/layouts/LayoutPlay.vue";

import PageNotFound from "./views/errors/404.vue";
import Loader from "./components/loader/loader.vue";

// Auth
import Login from "./views/auth/UserLogin.vue";
import ForgotPassword from "./views/auth/UserForgotPassword.vue";
import ChagePassword from "./views/auth/UserChangePassword.vue";
import Register from "./views/auth/UserRegister.vue";
import ForgotPasswordReset from "./views/auth/UserPasswordReset.vue";

// Dashboard
import Dashboard from "./views/dashboard/Dashboard.vue";

//Discover
import DiscoverIndex from "./views/discover/DiscoverIndex.vue";
import Discover from "./views/discover/Discover.vue";
import DiscoverView from "./views/discover/DiscoverView.vue";

// Fizzies
import FizzyIndex from "./views/fizzies/Index.vue";
import FizzyCreate from "./views/fizzies/Create.vue";
import FizzyView from "./views/fizzies/View.vue";

// Reports
import Reports from "./views/reports/Index.vue";
import ReportView from "./views/reports/View.vue";

// CMS
import CmsIndex from "./views/cms/Index.vue";

// Setting
import Profile from "./views/settings/UserProfile.vue";
import ChangePassword from "./views/settings/UserChangePassword.vue";
import DeleteAccount from "./views/settings/UserDeleteAccount.vue";

// admin-user
import UserIndex from "./views/admin/users/UserIndex.vue";
import UserView from "./views/admin/users/UserView.vue";

// play-fizzy
import PlayGame from "./views/host-fizzy/Play.vue";

// Participate
import PlayerPage from "./views/Player/player.vue";

Vue.use(VueRouter, VueAxios, Axios);

const router = new VueRouter({
    mode: "history",
    routes: [
        // Auth
        {
            path: "/app",
            meta: {
                redirectIfAuthenticated: true
            },
            component: LayoutLogin,
            children: [
                {
                    path: "/",
                    component: Login
                },
                {
                    path: "login",
                    name: "login",
                    component: Login
                },
                {
                    path: "forgotpassword",
                    name: "forgotpassword",
                    component: ForgotPassword
                },
                {
                    path: "forgotpassword/email/:id",
                    name: "ChagePassword",
                    component: ChagePassword
                },
                {
                    path: "register",
                    name: "register",
                    component: Register
                },
                {
                    path: "password/reset",
                    name: "password.reset",
                    component: ForgotPasswordReset
                }
            ]
        },
        {
            path: "/app",
            meta: {
                auth: true
            },
            component: LayoutBasic,
            children: [
                {
                    path: "dashboard/global",
                    name: "admin.dashboard",
                    component: AdminDashboard
                },
                {
                    path: "user",
                    name: "users.index",
                    component: UserIndex
                },
                {
                    path: "users/:id/view",
                    name: "users.view",
                    component: UserView
                },
                {
                    path: "cms",
                    name: "cms.index",
                    component: CmsIndex
                }
            ]
        },
        {
            path: "/app",
            meta: {
                auth: true
            },
            component: LayoutBasic,
            children: [
                {
                    path: "dashboard/my-ac",
                    name: "dashboard",
                    component: Dashboard
                },
                // disvover
                {
                    path: "discover",
                    name: "discover",
                    component: Discover
                },
                {
                    path: "discover/:token/view",
                    name: "discover.view",
                    component: DiscoverView
                },
                // Fizzies
                {
                    path: "fizzies",
                    name: "fizzies.index",
                    component: FizzyIndex
                },
                {
                    path: "fizzies/create",
                    name: "fizzies.create",
                    component: FizzyCreate
                },
                {
                    path: "fizzies/:token/view",
                    name: "fizzies.view",
                    component: FizzyView
                },
                // Reports
                {
                    path: "reports",
                    name: "reports.index",
                    component: Reports
                },
                {
                    path: "reports/:id/view",
                    name: "reports.view",
                    component: ReportView
                },
                // Settings
                {
                    path: "settings/profile",
                    name: "settings.profile",
                    component: Profile
                },
                {
                    path: "settings/changepassword",
                    name: "settings.changepassword",
                    component: ChangePassword
                },
                {
                    path: "settings/deleteaccount",
                    name: "settings.deleteaccount",
                    component: DeleteAccount
                }
            ]
        },
        {
            path: "/loader/:to",
            name: "loader",
            component: Loader
        },
        // host-fizzy
        {
            path: "/app/host/:token/:id",
            meta: {
                auth: true
            },
            component: LayoutPlay,
            children: [
                {
                    path: "",
                    name: "play",
                    component: PlayGame
                }
            ]
        },
        // play game
        {
            path: "/play",
            component: LayoutPlay,
            children: [
                {
                    path: "",
                    name: "player",
                    component: PlayerPage
                }
            ]
        },
        {
            path: "*",
            name: "404",
            component: PageNotFound,
        }
    ]
});

router.beforeEach((to, from, next) => {
    let isAuthenticated = localStorage.getItem("auth/isAuthenticated");

    if (isAuthenticated) {
        Axios.defaults.headers.common[
            "Authorization"
        ] = `Bearer ${isAuthenticated}`;

        if (!store.getters.activeUser && to.name != "loader") {
            next({ name: "loader", params: { to: to.path } });
        }
    }

    if (to.matched.some(m => m.meta.auth) && !isAuthenticated) {
        next({ name: "login" });
    } else if (
        to.matched.some(m => m.meta.redirectIfAuthenticated) &&
        isAuthenticated
    ) {
        next({ name: "dashboard" });
    } else {
        next();
    }
});

export default router;
