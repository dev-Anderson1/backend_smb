import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';
import api from '../services/api.js';

export default function ModelosArmasPage() {
    const columns = useMemo(
        () => [
            { key: 'name', label: 'Modelo' },
            { key: 'numero_serie', label: 'Número de série' },
            {
                key: 'calibre',
                label: 'Calibre',
                render: (item) => item.calibre?.nome ?? item.calibre_id ?? '—',
            },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            { name: 'name', label: 'Modelo', type: 'text', required: true },
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
            { name: 'numero_serie', label: 'Número de série', type: 'text', required: true },
        ],
        [],
    );

    return (
        <ResourcePage
            title="Modelos de Armas"
            description="Catálogo de modelos e calibres disponíveis."
            resource="/modelo_armas"
            columns={columns}
            fields={fields}
        />
    );
}
