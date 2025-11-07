import { useEffect, useState } from 'react';
import api from '../services/api.js';

const resourceSummary = [
    { key: 'usuarios', label: 'Usuários', path: '/usuarios', endpoint: '/users' },
    { key: 'cautelas', label: 'Cautelas', path: '/cautelas', endpoint: '/cautelas' },
    { key: 'armas', label: 'Armas', path: '/armas', endpoint: '/armas' },
    { key: 'municoes', label: 'Munições', path: '/municoes', endpoint: '/municoes' },
    { key: 'carregadores', label: 'Carregadores', path: '/carregadores', endpoint: '/carregadores' },
    { key: 'equipamentos', label: 'Equipamentos', endpoint: null },
];

export default function DashboardPage() {
    const [stats, setStats] = useState({});
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [pendingUsers, setPendingUsers] = useState([]);

    useEffect(() => {
        const load = async () => {
            setLoading(true);
            setError(null);

            try {
                const responses = await Promise.all(
                    resourceSummary
                        .filter((resource) => resource.endpoint)
                        .map(async (resource) => {
                            const { data } = await api.get(resource.endpoint);
                            return { key: resource.key, value: data.length };
                        }),
                );

                const counts = responses.reduce((acc, entry) => ({ ...acc, [entry.key]: entry.value }), {});

                const equipamentos = await Promise.all([
                    api.get('/algemas'),
                    api.get('/coletes'),
                    api.get('/espadas'),
                ]);

                counts.equipamentos = equipamentos.reduce((sum, response) => sum + response.data.length, 0);

                setStats(counts);

                try {
                    const { data } = await api.get('/usuarios-com-cautelas-pendentes');
                    setPendingUsers(data);
                } catch (pendingError) {
                    console.warn('Falha ao carregar usuários com cautela pendente', pendingError);
                }
            } catch (err) {
                console.error(err);
                setError('Não foi possível carregar o painel.');
            } finally {
                setLoading(false);
            }
        };

        load();
    }, []);

    return (
        <section className="dashboard-page">
            <div className="page-header">
                <div>
                    <h2 className="page-title">Visão geral</h2>
                    <p className="page-description">Resumo do inventário e das movimentações de cautela.</p>
                </div>
            </div>

            {error && <div className="alert alert-error">{error}</div>}

            {loading ? (
                <div className="table-loading">
                    <div className="spinner" />
                    <span>Carregando dados...</span>
                </div>
            ) : (
                <>
                    <div className="card-grid">
                        {resourceSummary.map((resource) => (
                            <div key={resource.key} className="card">
                                <div className="card-body">
                                    <span className="card-label">{resource.label}</span>
                                    <strong className="card-value">{stats[resource.key] ?? 0}</strong>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="card">
                        <div className="card-header">
                            <h3>Cautelas pendentes</h3>
                        </div>
                        <div className="card-body">
                            {pendingUsers.length === 0 ? (
                                <p className="empty-state">Nenhum policial com cautela pendente.</p>
                            ) : (
                                <ul className="pending-list">
                                    {pendingUsers.map((user) => (
                                        <li key={user.id}>
                                            <div>
                                                <strong>{user.name}</strong>
                                                {user.opm && <span className="muted"> • {user.opm.bpm}</span>}
                                                {user.posto_graduacao && (
                                                    <span className="muted"> • {user.posto_graduacao.nome}</span>
                                                )}
                                            </div>
                                            <span className="badge">{user.cautelas?.length ?? 0} cautelas</span>
                                        </li>
                                    ))}
                                </ul>
                            )}
                        </div>
                    </div>
                </>
            )}
        </section>
    );
}
