import { Outlet, useLocation } from 'react-router-dom';
import Header from './Header.jsx';
import Sidebar from './Sidebar.jsx';

export default function Layout() {
    const location = useLocation();

    return (
        <div className="app-shell">
            <Sidebar currentPath={location.pathname} />
            <div className="main-column">
                <Header />
                <main className="main-content">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}
