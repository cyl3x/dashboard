import { createStore } from 'vuex';
import { auth } from "./AuthModule";
import { user } from "./UserModule";
import createPersistedState from 'vuex-persistedstate';
import router from '../router/index'

const dataState = createPersistedState({
    key: 'vuex',
    paths: ['auth'],
});

export default createStore({
    strict: true,
    plugins: [dataState],
    modules: {
        auth,
        user,
    },
    actions: {
        discard({ dispatch }) {
            dispatch('user/discard');
            return dispatch('auth/discard').then(response => {
                router.push("/login");
                return response;
            });
        }
    }
});