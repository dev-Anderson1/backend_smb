import { BrowserRouter, Navigate, Route, Routes } from 'react-router-dom';
import { AuthProvider, useAuth } from './contexts/AuthContext.jsx';
import Layout from './components/Layout.jsx';
import LoginPage from './pages/LoginPage.jsx';
import DashboardPage from './pages/DashboardPage.jsx';
import UsersPage from './pages/UsersPage.jsx';
import AlgemasPage from './pages/AlgemasPage.jsx';
import EspadasPage from './pages/EspadasPage.jsx';
import ColetesPage from './pages/ColetesPage.jsx';
import CarregadoresPage from './pages/CarregadoresPage.jsx';
import ModelosArmasPage from './pages/ModelosArmasPage.jsx';
import ArmasPage from './pages/ArmasPage.jsx';
import MunicoesPage from './pages/MunicoesPage.jsx';
import CalibresPage from './pages/CalibresPage.jsx';
import OpmsPage from './pages/OpmsPage.jsx';
import PostoGraduacoesPage from './pages/PostoGraduacoesPage.jsx';
import CautelasPage from './pages/CautelasPage.jsx';

const ProtectedRoute = () => {
    const { token, initializing } = useAuth();

    if (initializing) {
        return (
            <div className="page-loading">
                <div className="spinner" />
                <span>Carregando...</span>
            </div>
        );
    }

    if (!token) {
        return <Navigate to="/login" replace />;
    }

    return <Layout />;
};

const AppRoutes = () => (
    <Routes>
        <Route path="/login" element={<LoginPage />} />
        <Route element={<ProtectedRoute />}>
            <Route path="/" element={<DashboardPage />} />
            <Route path="/usuarios" element={<UsersPage />} />
            <Route path="/cautelas" element={<CautelasPage />} />
            <Route path="/algemas" element={<AlgemasPage />} />
            <Route path="/espadas" element={<EspadasPage />} />
            <Route path="/coletes" element={<ColetesPage />} />
            <Route path="/carregadores" element={<CarregadoresPage />} />
            <Route path="/modelo-armas" element={<ModelosArmasPage />} />
            <Route path="/armas" element={<ArmasPage />} />
            <Route path="/municoes" element={<MunicoesPage />} />
            <Route path="/calibres" element={<CalibresPage />} />
            <Route path="/opms" element={<OpmsPage />} />
            <Route path="/posto-graduacoes" element={<PostoGraduacoesPage />} />
        </Route>
        <Route path="*" element={<Navigate to="/" replace />} />
    </Routes>
);

export default function App() {
    return (
        <AuthProvider>
            <BrowserRouter>
                <AppRoutes />
            </BrowserRouter>
        </AuthProvider>
    );
}
