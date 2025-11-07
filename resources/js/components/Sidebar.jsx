import { NavLink } from 'react-router-dom';

const navItems = [
    { path: '/', label: 'Painel' },
    { path: '/usuarios', label: 'Usuários' },
    { path: '/cautelas', label: 'Cautelas' },
    { path: '/armas', label: 'Armas' },
    { path: '/modelo-armas', label: 'Modelos de Armas' },
    { path: '/municoes', label: 'Munições' },
    { path: '/carregadores', label: 'Carregadores' },
    { path: '/calibres', label: 'Calibres' },
    { path: '/opms', label: 'OPMs' },
    { path: '/posto-graduacoes', label: 'Postos/Graduações' },
    { path: '/algemas', label: 'Algemas' },
    { path: '/coletes', label: 'Coletes' },
    { path: '/espadas', label: 'Espadas' },
];

export default function Sidebar({ currentPath }) {
    return (
        <aside className="sidebar">
            <div className="sidebar-brand">
                <span className="sidebar-logo">SMB</span>
                <span className="sidebar-text">Sistema Militar de Bens</span>
            </div>
            <nav className="sidebar-nav">
                {navItems.map((item) => (
                    <NavLink
                        key={item.path}
                        to={item.path}
                        className={({ isActive }) =>
                            `sidebar-link${isActive || currentPath === item.path ? ' sidebar-link-active' : ''}`
                        }
                    >
                        {item.label}
                    </NavLink>
                ))}
            </nav>
        </aside>
    );
}
