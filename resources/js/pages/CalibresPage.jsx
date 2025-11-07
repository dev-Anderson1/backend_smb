import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';

export default function CalibresPage() {
    const columns = useMemo(
        () => [
            { key: 'nome', label: 'Nome do calibre' },
            { key: 'medidas', label: 'Especificações' },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            { name: 'nome', label: 'Nome', type: 'text', required: true },
            { name: 'medidas', label: 'Medidas', type: 'text', required: true },
        ],
        [],
    );

    return (
        <ResourcePage
            title="Calibres"
            description="Tabela de calibres com suas respectivas medidas."
            resource="/calibres"
            columns={columns}
            fields={fields}
        />
    );
}
