import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';
import api from '../services/api.js';

export default function CarregadoresPage() {
    const columns = useMemo(
        () => [
            { key: 'capacidade', label: 'Capacidade' },
            { key: 'quantidade', label: 'Quantidade em estoque' },
            {
                key: 'arma_id',
                label: 'Arma vinculada',
                render: (item) => item.arma_id ?? '—',
            },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            { name: 'capacidade', label: 'Capacidade', type: 'number', required: true },
            { name: 'quantidade', label: 'Quantidade', type: 'number', required: true },
            {
                name: 'arma_id',
                label: 'Arma (opcional)',
                type: 'select',
                valueType: 'number',
                omitIfEmpty: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/armas');
                    return data;
                },
                getOptionLabel: (option) => `Arma #${option.id}`,
            },
        ],
        [],
    );

    return (
        <ResourcePage
            title="Carregadores"
            description="Controle de carregadores por capacidade e arma relacionada."
            resource="/carregadores"
            columns={columns}
            fields={fields}
        />
    );
}
