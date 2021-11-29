import Vue from "vue";
import Vuex from "vuex";
import Axios from "axios";

Vue.use(Vuex, Axios);

export const store = new Vuex.Store({
    state: {
        user: null
    },
    getters: {
        activeUser(state) {
            return state.user;
        },
        activeUserRole(state) {
            return state.user.role_id;
        }
    },
    actions: {
        async getUser({ commit }) {
            try {
                let { data } = await Axios.get("/api/getUser");

                commit("getUser", data);
            } catch (error) {
                console.log("vuex getUser - " + error);
            }
        }
    },
    mutations: {
        getUser(state, userData) {
            state.user = userData;
        },
        removeUser(state) {
            state.user = null;
        }
    }
});
