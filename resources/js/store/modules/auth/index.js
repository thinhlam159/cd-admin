import {MODULE_STORE} from "@/const";

const auth = {
    namespaced: true,
    state() {
        return {
            isAuthenticated: false,
            // isAuthenticated: true,
            userName: '',
        };
    },
    getters: {
        [MODULE_STORE.AUTH.GETTERS.GET_USER_NAME](state) {
            return state.userName;
        },
    },
    mutations: {
        [MODULE_STORE.AUTH.MUTATIONS.SET_USER_NAME](state, payload) {
            state.userName = payload.userName
        }
    }
};
export default auth;
