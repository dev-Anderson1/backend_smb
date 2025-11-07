import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';
import api from '../services/api.js';

export default function MunicoesPage() {
    const columns = useMemo(
        () => [
            { key: 'tipo', label: 'Tipo' },
            {
                key: 'calibre',
                label: 'Calibre',
                render: (item) => item.calibre?.nome ?? item.calibre_id ?? '—',
            },
            {
                key: 'arma_id',
                label: 'Arma associada',
                render: (item) => item.arma_id ?? '—',
            },
            { key: 'quantidade', label: 'Quantidade disponível' },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            { name: 'tipo', label: 'Tipo de munição', type: 'text', required: true },
            {
                name: 'calibre_id',
                label: 'Calibre',
                type: 'select',
                valueType: 'number',
                required: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/calibres');
                    return data;
                },
                getOptionLabel: (option) => option.nome,
            },
            {
                name: 'arma_id',
                label: 'Arma',
                type: 'select',
                valueType: 'number',
                required: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/armas');
                    return data;
                },
                getOptionLabel: (option) => `Arma #${option.id}`,
            },
            { name: 'quantidade', label: 'Quantidade', type: 'number', required: true },
        ],
        [],
    );

    return (
        <ResourcePage
            title="Munições"
            description="Controle de lotes de munições e calibres." 
            resource="/municoes"
            columns={columns}
            fields={fields}
        />
    );
}
