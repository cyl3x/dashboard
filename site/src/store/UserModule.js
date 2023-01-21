import axios from 'axios';

const url = window.location.protocol + "//" + window.location.host + '/api';
const initState = { user: null, config: null, imageList: null };

export const user = {
    namespaced: true,
    state: initState,
    actions: {
        getUser({ commit }) {
            return axios
                .post(url + "/user")
                .then(response => {
                    commit("setUser", response.data.data.user);
                    return true;
                })
                .catch(error => {
                    commit("discardUser");
                    return error.response ? error.response.data : error.message;
                });
        },
        getConfig({ commit }) {
            return axios
                .get(url + '/user/config').then(response => {
                    var config = response.data.data.config;
                    commit("setConfig", config);
                    return true;
                })
                .catch(error => {
                    commit("discardConfig");
                    return error.response ? error.response.data : error.message;
                });
        },
        updateConfig({ commit }, config) {
            return axios
                .put(url + "/user/update/config", { config })
                .then(response => {
                    commit("setConfig", config);
                    return true;
                })
                .catch(error => {
                    return error.response ? error.response.data : error.message;
                });
        },
        getImageList({ commit }) {
            return axios
                .get(url + '/user/img/list').then(response => {
                    commit("setImageList", response.data.data.list);
                    return true;
                })
                .catch(error => {
                    commit("discardImageList");
                    return error.response ? error.response.data : error.message;
                });
        },
        removeImage({ commit }, image) {
            return axios
                .put(url + '/user/img/remove', { image }).then(response => {
                    commit("removeImage", image);
                    return true;
                })
                .catch(error => {
                    return error.response ? error.response.data : error.message;
                });
        },
        addImage({ commit }, imageFile) {
            const formData = new FormData();
            formData.append('file', imageFile);

            const headers = {
                headers: { 'Content-Type': 'multipart/form-data' }
            };

            return axios
                .post(url + '/user/img/add', formData, headers).then(response => {
                    commit("addImage", imageFile);
                    return true;
                })
                .catch(error => {
                    return error.response ? error.response.data.error : error.message;
                });
        },
        discardConfig({ commit }) {
            return new Promise((resolve, reject) => {
                commit('discardConfig');
                resolve();
            });
        },
        discardImageList({ commit }) {
            return new Promise((resolve, reject) => {
                commit('discardImageList')
                resolve();
            });
        },
        discard({ commit }) {
            return new Promise((resolve, reject) => {
                commit('discard');
                resolve();
            });
        }
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setConfig(state, config) {
            state.config = config;
        },
        setImageList(state, imageList) {
            state.imageList = imageList;
        },
        addImage(state, image) {
            state.imageList.push(image.name);
        },
        removeImage(state, image) {
            const index = state.imageList.indexOf(image);
            if (index !== -1) state.imageList.splice(index, 1);
        },
        updateProfile(state, profile) {
            state.user.email = profile.email;
            state.user.username = profile.username;
        },
        discardUser(state) {
            state.user = null;
        },
        discardImageList(state) {
            state.imageList = null;
        },
        discardConfig(state) {
            state.config = null;
        },
        discard(state) {
            Object.assign(state, initState);
        },
    },
};