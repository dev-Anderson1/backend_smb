import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';

export default function PostoGraduacoesPage() {
    const columns = useMemo(
        () => [
            { key: 'nome', label: 'Posto / Graduação' },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            { name: 'nome', label: 'Posto / Graduação', type: 'text', required: true },
        ],
        [],
    );

    return (
        <ResourcePage
            title="Postos e Graduações"
            description="Lista de postos e graduações disponíveis para associação aos usuários."
            resource="/posto_graduacoes"
            columns={columns}
            fields={fields}
        />
    );
}
