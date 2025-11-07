import { useCallback, useEffect, useMemo, useState } from 'react';
import ResourceForm from './ResourceForm.jsx';
import api from '../services/api.js';

export default function ResourcePage({
    title,
    description,
    resource,
    columns,
    fields,
    transformSubmit,
    mapData,
}) {
    const [items, setItems] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [formOpen, setFormOpen] = useState(false);
    const [editingItem, setEditingItem] = useState(null);
    const [saving, setSaving] = useState(false);

    const fetchData = useCallback(async () => {
        setLoading(true);
        setError(null);

        try {
            const { data } = await api.get(resource);
            setItems(mapData ? data.map(mapData) : data);
        } catch (err) {
            console.error(err);
            setError('Não foi possível carregar os dados.');
        } finally {
            setLoading(false);
        }
    }, [resource, mapData]);

    useEffect(() => {
        fetchData();
    }, [fetchData]);

    const handleCreate = () => {
        setEditingItem(null);
        setFormOpen(true);
    };

    const handleEdit = (item) => {
        setEditingItem(item);
        setFormOpen(true);
    };

    const handleDelete = async (item) => {
        if (!window.confirm('Tem certeza que deseja remover este registro?')) {
            return;
        }

        try {
            await api.delete(`${resource}/${item.id}`);
            await fetchData();
        } catch (err) {
            console.error(err);
            setError('Não foi possível remover o registro.');
        }
    };

    const handleSubmit = async (values) => {
        setSaving(true);
        setError(null);

        try {
            const payload = transformSubmit ? transformSubmit(values, editingItem) : values;

            if (editingItem) {
                await api.put(`${resource}/${editingItem.id}`, payload);
            } else {
                await api.post(resource, payload);
            }

            setFormOpen(false);
            setEditingItem(null);
            await fetchData();
        } catch (err) {
            console.error(err);
            const message = err.response?.data?.message || 'Não foi possível salvar o registro.';
            setError(message);
        } finally {
            setSaving(false);
        }
    };

    const tableColumns = useMemo(
        () =>
            columns.map((column) => ({
                ...column,
                render: column.render || ((item) => item[column.key] ?? '—'),
            })),
        [columns],
    );

    return (
        <section className="resource-page">
            <div className="page-header">
                <div>
                    <h2 className="page-title">{title}</h2>
                    {description && <p className="page-description">{description}</p>}
                </div>
                <button type="button" className="button" onClick={handleCreate}>
                    Novo registro
                </button>
            </div>

            {error && <div className="alert alert-error">{error}</div>}

            {formOpen && (
                <div className="card">
                    <div className="card-header">
                        <h3>{editingItem ? 'Editar registro' : 'Novo registro'}</h3>
                        <button type="button" className="button button-ghost" onClick={() => setFormOpen(false)}>
                            Fechar
                        </button>
                    </div>
                    <ResourceForm
                        fields={fields}
                        initialData={editingItem}
                        isEditing={Boolean(editingItem)}
                        submitting={saving}
                        onSubmit={handleSubmit}
                        onCancel={() => {
                            setFormOpen(false);
                            setEditingItem(null);
                        }}
                    />
                </div>
            )}

            <div className="card">
                <div className="card-body">
                    {loading ? (
                        <div className="table-loading">
                            <div className="spinner" />
                            <span>Carregando dados...</span>
                        </div>
                    ) : items.length === 0 ? (
                        <p className="empty-state">Nenhum registro encontrado.</p>
                    ) : (
                        <div className="table-wrapper">
                            <table>
                                <thead>
                                    <tr>
                                        {tableColumns.map((column) => (
                                            <th key={column.key}>{column.label}</th>
                                        ))}
                                        <th className="table-actions-column">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {items.map((item) => (
                                        <tr key={item.id}>
                                            {tableColumns.map((column) => (
                                                <td key={column.key}>{column.render(item)}</td>
                                            ))}
                                            <td className="table-actions">
                                                <button
                                                    type="button"
                                                    className="button button-ghost"
                                                    onClick={() => handleEdit(item)}
                                                >
                                                    Editar
                                                </button>
                                                <button
                                                    type="button"
                                                    className="button button-danger"
                                                    onClick={() => handleDelete(item)}
                                                >
                                                    Excluir
                                                </button>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    )}
                </div>
            </div>
        </section>
    );
}
