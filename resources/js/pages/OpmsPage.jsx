import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';

export default function OpmsPage() {
    const columns = useMemo(
        () => [
            { key: 'bpm', label: 'BPM / Unidade' },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            { name: 'bpm', label: 'BPM / Unidade', type: 'text', required: true },
        ],
        [],
    );

    return (
        <ResourcePage
            title="OPMs"
            description="Cadastro de unidades militares / batalhões."
            resource="/opms"
            columns={columns}
            fields={fields}
        />
    );
}
