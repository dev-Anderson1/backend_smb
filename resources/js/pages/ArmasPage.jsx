import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';
import api from '../services/api.js';

export default function ArmasPage() {
    const columns = useMemo(
        () => [
            { key: 'id', label: 'ID' },
            {
                key: 'modelo',
                label: 'Modelo',
                render: (item) => item.modelo?.name ?? item.modelo_id ?? '—',
            },
            { key: 'carregador_id', label: 'Carregador' },
            { key: 'municao_id', label: 'Munição' },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            {
                name: 'modelo_id',
                label: 'Modelo',
                type: 'select',
                valueType: 'number',
                required: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/modelo_armas');
                    return data;
                },
                getOptionLabel: (option) => `${option.name} (${option.numero_serie})`,
            },
            {
                name: 'carregador_id',
                label: 'Carregador',
                type: 'select',
                valueType: 'number',
                required: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/carregadores');
                    return data;
                },
                getOptionLabel: (option) => `Cap. ${option.capacidade} (${option.quantidade} un.)`,
            },
            {
                name: 'municao_id',
                label: 'Munição',
                type: 'select',
                valueType: 'number',
                required: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/municoes');
                    return data;
                },
                getOptionLabel: (option) => `${option.tipo} (${option.quantidade} un.)`,
            },
        ],
        [],
    );

    return (
        <ResourcePage
            title="Armas"
            description="Gerenciamento das armas disponíveis para cautela."
            resource="/armas"
            columns={columns}
            fields={fields}
        />
    );
}
