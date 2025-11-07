import axios from 'axios';

const api = axios.create({
    baseURL: '/api',
    headers: {
        Accept: 'application/json',
    },
});

api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            console.warn('Requisição não autorizada, verifique as credenciais.');
        }

        return Promise.reject(error);
    },
);

export default api;
