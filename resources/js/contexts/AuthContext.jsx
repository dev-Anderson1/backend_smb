import { createContext, useContext, useEffect, useMemo, useState } from 'react';
import api from '../services/api.js';

const AuthContext = createContext(null);

export const AuthProvider = ({ children }) => {
    const [token, setToken] = useState(() => localStorage.getItem('token'));
    const [user, setUser] = useState(() => {
        const stored = localStorage.getItem('user');
        if (!stored) {
            return null;
        }

        try {
            return JSON.parse(stored);
        } catch (error) {
            console.error('Erro ao ler usuário armazenado', error);
            return null;
        }
    });
    const [loading, setLoading] = useState(false);
    const [initializing, setInitializing] = useState(true);
    const [error, setError] = useState(null);

    useEffect(() => {
        if (token) {
            api.defaults.headers.common.Authorization = `Bearer ${token}`;
        } else {
            delete api.defaults.headers.common.Authorization;
        }

        setInitializing(false);
    }, [token]);

    const login = async (credentials) => {
        setLoading(true);
        setError(null);

        try {
            const { data } = await api.post('/login', credentials);

            setToken(data.token);
            setUser(data.user);
            localStorage.setItem('token', data.token);
            localStorage.setItem('user', JSON.stringify(data.user));

            return data.user;
        } catch (err) {
            const message = err.response?.data?.message || 'Não foi possível realizar o login.';
            setError(message);
            throw err;
        } finally {
            setLoading(false);
        }
    };

    const logout = async () => {
        try {
            await api.post('/logout');
        } catch (err) {
            console.warn('Falha ao encerrar sessão, continuando mesmo assim.', err);
        }

        setToken(null);
        setUser(null);
        localStorage.removeItem('token');
        localStorage.removeItem('user');
    };

    const value = useMemo(
        () => ({
            token,
            user,
            loading,
            initializing,
            error,
            login,
            logout,
            setError,
        }),
        [token, user, loading, initializing, error],
    );

    return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = () => {
    const context = useContext(AuthContext);

    if (!context) {
        throw new Error('useAuth deve ser utilizado dentro de um AuthProvider');
    }

    return context;
};
