import { useEffect, useMemo, useState } from 'react';
import api from '../services/api.js';
import { useAuth } from '../contexts/AuthContext.jsx';

const statusLabels = {
    pendente: 'Pendente',
    autorizada: 'Autorizada',
    devolvido: 'Devolvido',
};

export default function CautelasPage() {
    const { user: currentUser } = useAuth();
    const [cautelas, setCautelas] = useState([]);
    const [users, setUsers] = useState([]);
    const [armas, setArmas] = useState([]);
    const [espadas, setEspadas] = useState([]);
    const [coletes, setColetes] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [formOpen, setFormOpen] = useState(false);
    const [adminId, setAdminId] = useState(currentUser?.id ? String(currentUser.id) : '');
    const [userId, setUserId] = useState('');
    const [armaItems, setArmaItems] = useState([]);
    const [espadaItems, setEspadaItems] = useState([]);
    const [coleteItems, setColeteItems] = useState([]);
    const [formError, setFormError] = useState(null);
    const [saving, setSaving] = useState(false);
    const [statusDraft, setStatusDraft] = useState({});

    const statusOptions = useMemo(() => Object.keys(statusLabels), []);

    const fetchCautelas = async () => {
        setLoading(true);
        setError(null);

        try {
            const { data } = await api.get('/cautelas');
            setCautelas(data);
        } catch (err) {
            console.error(err);
            setError('Não foi possível carregar as cautelas.');
        } finally {
            setLoading(false);
        }
    };

    const fetchReferences = async () => {
        try {
            const [usersResponse, armasResponse, espadasResponse, coletesResponse] = await Promise.all([
                api.get('/users'),
                api.get('/armas'),
                api.get('/espadas'),
                api.get('/coletes'),
            ]);

            setUsers(usersResponse.data);
            setArmas(armasResponse.data);
            setEspadas(espadasResponse.data);
            setColetes(coletesResponse.data);
        } catch (err) {
            console.error(err);
            setError('Não foi possível carregar dados auxiliares.');
        }
    };

    useEffect(() => {
        fetchCautelas();
        fetchReferences();
    }, []);

    useEffect(() => {
        if (currentUser?.id) {
            setAdminId(String(currentUser.id));
        }
    }, [currentUser]);

    const resetForm = () => {
        setAdminId(currentUser?.id ? String(currentUser.id) : '');
        setUserId('');
        setArmaItems([]);
        setEspadaItems([]);
        setColeteItems([]);
        setFormError(null);
    };

    const addItemRow = (setter, template) => {
        setter((prev) => [...prev, template]);
    };

    const updateItemRow = (setter, index, field, value) => {
        setter((prev) =>
            prev.map((item, idx) => (idx === index ? { ...item, [field]: value } : item)),
        );
    };

    const removeItemRow = (setter, index) => {
        setter((prev) => prev.filter((_, idx) => idx !== index));
    };

    const handleSubmit = async (event) => {
        event.preventDefault();
        setFormError(null);

        if (!adminId || !userId) {
            setFormError('Selecione o administrador responsável e o policial.');
            return;
        }

        const normalizeItems = (items, key) =>
            items
                .filter((item) => item[key])
                .map((item) => ({
                    [key]: Number(item[key]),
                    quantidade: Number(item.quantidade) > 0 ? Number(item.quantidade) : 1,
                }));

        const armasPayload = normalizeItems(armaItems, 'arma_id');
        const espadasPayload = normalizeItems(espadaItems, 'espada_id');
        const coletesPayload = normalizeItems(coleteItems, 'colete_id');

        if (armasPayload.length === 0 && espadasPayload.length === 0 && coletesPayload.length === 0) {
            setFormError('Informe pelo menos um item para a cautela.');
            return;
        }

        const payload = {
            admin_id: Number(adminId),
            user_id: Number(userId),
            itens: {},
        };

        if (armasPayload.length) {
            payload.itens.armas = armasPayload;
        }

        if (espadasPayload.length) {
            payload.itens.espadas = espadasPayload;
        }

        if (coletesPayload.length) {
            payload.itens.coletes = coletesPayload;
        }

        setSaving(true);

        try {
            await api.post('/cautela/store', payload);
            setFormOpen(false);
            resetForm();
            await fetchCautelas();
        } catch (err) {
            console.error(err);
            const message = err.response?.data?.message || 'Não foi possível registrar a cautela.';
            setFormError(message);
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = async (cautela) => {
        if (!window.confirm('Confirma a exclusão desta cautela?')) {
            return;
        }

        try {
            await api.delete(`/cautelas/${cautela.id}`);
            await fetchCautelas();
        } catch (err) {
            console.error(err);
            setError('Não foi possível remover a cautela.');
        }
    };

    const handleApplyStatus = async (cautela) => {
        const desiredStatus = statusDraft[cautela.id];

        if (!desiredStatus || desiredStatus === cautela.status) {
            return;
        }

        try {
            await api.put(`/cautelas/${cautela.id}`, { status: desiredStatus });
            setStatusDraft((prev) => ({ ...prev, [cautela.id]: desiredStatus }));
            await fetchCautelas();
        } catch (err) {
            console.error(err);
            setError('Não foi possível atualizar o status.');
        }
    };

    const formatItems = (cautela) => {
        if (!Array.isArray(cautela.itens)) {
            return [];
        }

        return cautela.itens.map((item) => {
            if (item.arma) {
                const modelo = item.arma.modelo?.name ? ` - ${item.arma.modelo.name}` : '';
                return {
                    tipo: 'Arma',
                    descricao: `#${item.arma.id}${modelo}`,
                    quantidade: item.quantidade,
                };
            }

            if (item.colete) {
                return {
                    tipo: 'Colete',
                    descricao: `${item.colete.tipo} (${item.colete.num_serie})`,
                    quantidade: item.quantidade,
                };
            }

            if (item.espada) {
                return {
                    tipo: 'Espada',
                    descricao: `${item.espada.tipo} (${item.espada.num_serie})`,
                    quantidade: item.quantidade,
                };
            }

            if (item.algema) {
                return {
                    tipo: 'Algema',
                    descricao: `${item.algema.tipo} (${item.algema.num_serie})`,
                    quantidade: item.quantidade,
                };
            }

            return {
                tipo: 'Item',
                descricao: 'Registro não identificado',
                quantidade: item.quantidade,
            };
        });
    };

    return (
        <section className="cautelas-page">
            <div className="page-header">
                <div>
                    <h2 className="page-title">Cautelas</h2>
                    <p className="page-description">Acompanhe emissões, devoluções e autorizações de materiais.</p>
                </div>
                <button type="button" className="button" onClick={() => setFormOpen(true)}>
                    Nova cautela
                </button>
            </div>

            {error && <div className="alert alert-error">{error}</div>}

            {formOpen && (
                <div className="card cautela-form-card">
                    <div className="card-header">
                        <h3>Registrar cautela</h3>
                        <button type="button" className="button button-ghost" onClick={() => setFormOpen(false)}>
                            Fechar
                        </button>
                    </div>
                    <div className="card-body">
                        <form className="cautela-form" onSubmit={handleSubmit}>
                            <div className="cautela-form-grid">
                                <label className="form-field">
                                    <span className="form-label">Administrador responsável</span>
                                    <select value={adminId} onChange={(event) => setAdminId(event.target.value)}>
                                        <option value="">Selecione...</option>
                                        {users.map((user) => (
                                            <option key={user.id} value={user.id}>
                                                {user.name}
                                            </option>
                                        ))}
                                    </select>
                                </label>
                                <label className="form-field">
                                    <span className="form-label">Policial / militar</span>
                                    <select value={userId} onChange={(event) => setUserId(event.target.value)}>
                                        <option value="">Selecione...</option>
                                        {users.map((user) => (
                                            <option key={user.id} value={user.id}>
                                                {user.name} {user.apelido ? `(${user.apelido})` : ''}
                                            </option>
                                        ))}
                                    </select>
                                </label>
                            </div>

                            <div className="cautela-section">
                                <div className="section-header">
                                    <h4>Armas</h4>
                                    <button
                                        type="button"
                                        className="button button-secondary"
                                        onClick={() => addItemRow(setArmaItems, { arma_id: '', quantidade: 1 })}
                                    >
                                        Adicionar arma
                                    </button>
                                </div>
                                {armaItems.length === 0 && <p className="muted">Nenhuma arma selecionada.</p>}
                                {armaItems.map((item, index) => (
                                    <div key={`arma-${index}`} className="item-row">
                                        <select
                                            value={item.arma_id}
                                            onChange={(event) => updateItemRow(setArmaItems, index, 'arma_id', event.target.value)}
                                        >
                                            <option value="">Selecione...</option>
                                            {armas.map((arma) => (
                                                <option key={arma.id} value={arma.id}>
                                                    Arma #{arma.id}
                                                </option>
                                            ))}
                                        </select>
                                        <input
                                            type="number"
                                            min="1"
                                            value={item.quantidade}
                                            onChange={(event) => updateItemRow(setArmaItems, index, 'quantidade', event.target.value)}
                                        />
                                        <button
                                            type="button"
                                            className="button button-danger"
                                            onClick={() => removeItemRow(setArmaItems, index)}
                                        >
                                            Remover
                                        </button>
                                    </div>
                                ))}
                            </div>

                            <div className="cautela-section">
                                <div className="section-header">
                                    <h4>Espadas</h4>
                                    <button
                                        type="button"
                                        className="button button-secondary"
                                        onClick={() => addItemRow(setEspadaItems, { espada_id: '', quantidade: 1 })}
                                    >
                                        Adicionar espada
                                    </button>
                                </div>
                                {espadaItems.length === 0 && <p className="muted">Nenhuma espada selecionada.</p>}
                                {espadaItems.map((item, index) => (
                                    <div key={`espada-${index}`} className="item-row">
                                        <select
                                            value={item.espada_id}
                                            onChange={(event) => updateItemRow(setEspadaItems, index, 'espada_id', event.target.value)}
                                        >
                                            <option value="">Selecione...</option>
                                            {espadas.map((espada) => (
                                                <option key={espada.id} value={espada.id}>
                                                    {espada.tipo} ({espada.num_serie})
                                                </option>
                                            ))}
                                        </select>
                                        <input
                                            type="number"
                                            min="1"
                                            value={item.quantidade}
                                            onChange={(event) => updateItemRow(setEspadaItems, index, 'quantidade', event.target.value)}
                                        />
                                        <button
                                            type="button"
                                            className="button button-danger"
                                            onClick={() => removeItemRow(setEspadaItems, index)}
                                        >
                                            Remover
                                        </button>
                                    </div>
                                ))}
                            </div>

                            <div className="cautela-section">
                                <div className="section-header">
                                    <h4>Coletes</h4>
                                    <button
                                        type="button"
                                        className="button button-secondary"
                                        onClick={() => addItemRow(setColeteItems, { colete_id: '', quantidade: 1 })}
                                    >
                                        Adicionar colete
                                    </button>
                                </div>
                                {coleteItems.length === 0 && <p className="muted">Nenhum colete selecionado.</p>}
                                {coleteItems.map((item, index) => (
                                    <div key={`colete-${index}`} className="item-row">
                                        <select
                                            value={item.colete_id}
                                            onChange={(event) => updateItemRow(setColeteItems, index, 'colete_id', event.target.value)}
                                        >
                                            <option value="">Selecione...</option>
                                            {coletes.map((colete) => (
                                                <option key={colete.id} value={colete.id}>
                                                    {colete.tipo} ({colete.num_serie})
                                                </option>
                                            ))}
                                        </select>
                                        <input
                                            type="number"
                                            min="1"
                                            value={item.quantidade}
                                            onChange={(event) => updateItemRow(setColeteItems, index, 'quantidade', event.target.value)}
                                        />
                                        <button
                                            type="button"
                                            className="button button-danger"
                                            onClick={() => removeItemRow(setColeteItems, index)}
                                        >
                                            Remover
                                        </button>
                                    </div>
                                ))}
                            </div>

                            {formError && <div className="alert alert-error">{formError}</div>}

                            <div className="form-actions">
                                <button type="button" className="button button-secondary" onClick={() => setFormOpen(false)}>
                                    Cancelar
                                </button>
                                <button type="submit" className="button" disabled={saving}>
                                    {saving ? 'Salvando...' : 'Registrar cautela'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}

            <div className="card">
                <div className="card-body">
                    {loading ? (
                        <div className="table-loading">
                            <div className="spinner" />
                            <span>Carregando cautelas...</span>
                        </div>
                    ) : cautelas.length === 0 ? (
                        <p className="empty-state">Nenhuma cautela registrada até o momento.</p>
                    ) : (
                        <div className="cautela-list">
                            {cautelas.map((cautela) => {
                                const items = formatItems(cautela);
                                const draft = statusDraft[cautela.id] ?? cautela.status;

                                return (
                                    <div key={cautela.id} className="cautela-card">
                                        <header className="cautela-card-header">
                                            <div>
                                                <h3>#{cautela.id}</h3>
                                                <span className={`status-badge status-${cautela.status}`}>
                                                    {statusLabels[cautela.status] ?? cautela.status}
                                                </span>
                                            </div>
                                            <div className="cautela-actions">
                                                <select
                                                    value={draft}
                                                    onChange={(event) =>
                                                        setStatusDraft((prev) => ({
                                                            ...prev,
                                                            [cautela.id]: event.target.value,
                                                        }))
                                                    }
                                                >
                                                    {statusOptions.map((status) => (
                                                        <option key={status} value={status}>
                                                            {statusLabels[status] ?? status}
                                                        </option>
                                                    ))}
                                                </select>
                                                <button
                                                    type="button"
                                                    className="button button-secondary"
                                                    onClick={() => handleApplyStatus(cautela)}
                                                >
                                                    Atualizar status
                                                </button>
                                                <button
                                                    type="button"
                                                    className="button button-danger"
                                                    onClick={() => handleDelete(cautela)}
                                                >
                                                    Excluir
                                                </button>
                                            </div>
                                        </header>
                                        <div className="cautela-card-body">
                                            <dl className="cautela-info">
                                                <div>
                                                    <dt>Responsável</dt>
                                                    <dd>{cautela.admin?.name ?? '—'}</dd>
                                                </div>
                                                <div>
                                                    <dt>Policial</dt>
                                                    <dd>{cautela.usuario?.name ?? '—'}</dd>
                                                </div>
                                                <div>
                                                    <dt>Registrada em</dt>
                                                    <dd>{new Date(cautela.created_at).toLocaleString()}</dd>
                                                </div>
                                            </dl>
                                            <div className="cautela-items">
                                                <h4>Itens vinculados</h4>
                                                {items.length === 0 ? (
                                                    <p className="muted">Itens não carregados para esta cautela.</p>
                                                ) : (
                                                    <ul>
                                                        {items.map((item, index) => (
                                                            <li key={`${cautela.id}-${index}`}>
                                                                <span className="item-type">{item.tipo}</span>
                                                                <span className="item-description">{item.descricao}</span>
                                                                <span className="item-quantity">x{item.quantidade}</span>
                                                            </li>
                                                        ))}
                                                    </ul>
                                                )}
                                            </div>
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    )}
                </div>
            </div>
        </section>
    );
}
