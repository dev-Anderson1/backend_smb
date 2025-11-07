import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';

export default function EspadasPage() {
    const columns = useMemo(
        () => [
            { key: 'tipo', label: 'Tipo' },
            { key: 'num_serie', label: 'Número de série' },
            { key: 'quantidade', label: 'Quantidade disponível' },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            { name: 'tipo', label: 'Tipo', type: 'text', required: true },
            { name: 'num_serie', label: 'Número de série', type: 'text', required: true },
            { name: 'quantidade', label: 'Quantidade', type: 'number', required: true },
        ],
        [],
    );

    return (
        <ResourcePage
            title="Espadas"
            description="Cadastro de espadas e itens cerimoniais."
            resource="/espadas"
            columns={columns}
            fields={fields}
        />
    );
}
