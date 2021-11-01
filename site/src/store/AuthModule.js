import axios from 'axios';

const url = window.location.protocol + "//" + window.location.host + '/api';

const initState = { loggedIn: false, exp: -1, exp_in: -1 };

export const auth = {
    namespaced: true,
    state: initState,
    actions: {
        login({ commit, dispatch }, user) {
            return axios
                .post(url + "/auth/login", user)
                .then(response => {
                    response = response.data.data;

                    commit("login", response);

                    return true;
                })
                .catch(error => {
                    dispatch("logout");
                    return error.response ? error.response.data : error.message;
                });
        },
        register({ commit }, user) {
            return axios
                .post(url + "/auth/register", user)
                .then(response => {
                    return true;
                })
                .catch(error => {
                    return error.response ? error.response.data : error.message;
                });
        },
        refresh({ commit, dispatch }) {
            return axios
                .post(url + "/auth/refresh")
                .then(response => {
                    const exp_in = response.data.data.exp_in;
                    commit("refresh", exp_in);

                    setTimeout(() => dispatch('refresh'), exp_in * 1000);

                    return true;
                })
                .catch(error => {
                    return dispatch("discard", {}, { root: true });
                });
        },
        updateProfile({ commit, dispatch }, profile) {
            return axios
                .post(url + "/auth/update/profile", profile)
                .then(response => {
                    commit('user/updateProfile', profile, { root: true });
                    return true;
                })
                .catch(error => {
                    return error.response ? error.response.data : error.message;
                });
        },
        updatePassword({ commit, dispatch }, passwords) {
            return axios
                .post(url + "/auth/update/password", passwords)
                .then(response => {
                    dispatch('logout');
                    return true;
                })
                .catch(error => {
                    return error.response ? error.response.data : error.message;
                });
        },
        discard({ commit }) {
            return axios
                .post(url + "/auth/logout").then(response => {
                    commit('discard');
                    return true;
                })
                .catch(error => {
                    commit('discard');
                    console.error("Could not delete Cookie");
                    return error;
                });
        },
        logout({ dispatch }) {
            return dispatch("discard");
        }
    },
    mutations: {
        login(state, response) {
            state.loggedIn = true;
            state.exp_in = response.exp_in;
            state.exp = Math.round(Date.now() / 1000) + response.exp_in;
            state.remember_token = response.remember_token;
            state.remember_exp_in = Math.round(Date.now() / 1000) + response.remember_exp_in;
        },
        discard(state) {
            Object.assign(state, initState);
        },
        refresh(state, exp_in) {
            state.exp_in = exp_in;
            state.exp = Math.round(Date.now() / 1000) + exp_in;
        },
        registerSuccess(state) {
            state.loggedIn = false;
        },
        registerFailure(state) {
            state.loggedIn = false;
        },
    },
};