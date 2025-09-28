import { createStore } from 'vuex';

export default createStore({
    state: {
        user: null,
        isAuthenticated: false
    },
    mutations: {
        SET_USER(state, user) {
            state.user = user;
            state.isAuthenticated = !!user;
        }
    },
    actions: {
        async login({ commit }, credentials) {
            try {
                const response = await axios.post('/api/login', credentials);
                const user = response.data.user;
                commit('SET_USER', user);
                return user;
            } catch (error) {
                console.error('Login failed:', error);
                throw error;
            }
        },
        logout({ commit }) {
            commit('SET_USER', null);
        }
    },
    getters: {
        isAuthenticated: state => state.isAuthenticated,
        user: state => state.user
    }
});