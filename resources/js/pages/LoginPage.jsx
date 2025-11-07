import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext.jsx';

export default function LoginPage() {
    const navigate = useNavigate();
    const { login, loading, error, setError, token } = useAuth();
    const [form, setForm] = useState({ email: '', password: '' });
    const [formError, setFormError] = useState(null);

    useEffect(() => {
        if (token) {
            navigate('/', { replace: true });
        }
    }, [token, navigate]);

    const handleChange = (event) => {
        const { name, value } = event.target;
        setForm((prev) => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        setFormError(null);
        setError(null);

        if (!form.email || !form.password) {
            setFormError('Informe e-mail e senha.');
            return;
        }

        try {
            await login(form);
            navigate('/', { replace: true });
        } catch (err) {
            if (!err.response) {
                setFormError('Não foi possível conectar com o servidor.');
            }
        }
    };

    return (
        <div className="login-page">
            <div className="login-card">
                <h1>SMB - Gestão de Materiais</h1>
                <p>Acesse com suas credenciais para administrar o acervo.</p>

                <form className="login-form" onSubmit={handleSubmit}>
                    <label className="form-field">
                        <span className="form-label">E-mail</span>
                        <input
                            type="email"
                            name="email"
                            value={form.email}
                            onChange={handleChange}
                            placeholder="exemplo@pm.gov.br"
                        />
                    </label>
                    <label className="form-field">
                        <span className="form-label">Senha</span>
                        <input
                            type="password"
                            name="password"
                            value={form.password}
                            onChange={handleChange}
                            placeholder="Digite sua senha"
                        />
                    </label>
                    {(formError || error) && <div className="alert alert-error">{formError || error}</div>}
                    <button type="submit" className="button button-full" disabled={loading}>
                        {loading ? 'Entrando...' : 'Entrar'}
                    </button>
                </form>
            </div>
        </div>
    );
}
