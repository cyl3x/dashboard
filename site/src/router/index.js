import { createRouter, createWebHistory } from 'vue-router'
import NavBar from '../components/nav-bars/NavBar.vue'
import Dashboard from '../views/Dashboard.vue'
import User from '../views/User.vue'
import UserProfile from '../views/user/UserProfile.vue'
import UserConfig from '../views/user/UserConfig.vue'
import UserImages from '../views/user/UserImages.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import store from '../store/index'

const routes = [{
        path: "/",
        name: "Dashboard",
        components: { default: Dashboard, navbar: NavBar },
    },
    {
        path: "/user",
        name: "User",
        redirect: '/user/profile',
        components: { default: User, navbar: NavBar },
        children: [{
                path: "profile",
                name: "Profile",
                component: UserProfile,
            },
            {
                path: "config",
                name: "Config",
                component: UserConfig,
            },
            {
                path: "images",
                name: "Images",
                component: UserImages,
            }
        ]
    },
    {
        path: "/login",
        name: "Login",
        components: { default: Login },
    },
    {
        path: "/register",
        name: "Register",
        components: { default: Register },
    },
]

const router = createRouter({
    history: createWebHistory(""),
    routes
})

router.beforeEach((to, from, next) => {
    const publicPages = ['/login', '/register'];
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = store.state.auth.loggedIn;
    // trying to access a restricted page + not logged in
    // redirect to login page
    if (authRequired && !loggedIn) next('/login');
    else next();
});

export default router