import { useAuth } from '../contexts/AuthContext.jsx';

export default function Header() {
    const { user, logout } = useAuth();

    const handleLogout = async () => {
        await logout();
    };

    return (
        <header className="app-header">
            <div>
                <h1 className="app-title">SMB - Gestão de Materiais</h1>
                <p className="app-subtitle">Administração completa de armas, equipamentos e cautelas</p>
            </div>
            <div className="user-box">
                <div className="user-info">
                    <span className="user-name">{user?.name}</span>
                    <span className="user-email">{user?.email}</span>
                </div>
                <button type="button" className="button button-secondary" onClick={handleLogout}>
                    Sair
                </button>
            </div>
        </header>
    );
}
