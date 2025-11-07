import { useMemo } from 'react';
import ResourcePage from '../components/ResourcePage.jsx';
import api from '../services/api.js';

export default function UsersPage() {
    const columns = useMemo(
        () => [
            { key: 'name', label: 'Nome completo' },
            { key: 'email', label: 'E-mail institucional' },
            { key: 'apelido', label: 'Apelido' },
            {
                key: 'opm',
                label: 'OPM',
                render: (item) => item.opm?.bpm ?? '—',
            },
            {
                key: 'posto',
                label: 'Posto/Graduação',
                render: (item) => item.posto_graduacao?.nome ?? '—',
            },
        ],
        [],
    );

    const fields = useMemo(
        () => [
            {
                name: 'name',
                label: 'Nome completo',
                type: 'text',
                required: true,
            },
            {
                name: 'email',
                label: 'E-mail',
                type: 'email',
                required: true,
            },
            {
                name: 'apelido',
                label: 'Apelido/Identificação',
                type: 'text',
            },
            {
                name: 'password',
                label: 'Senha',
                type: 'password',
                requiredOnCreate: true,
                omitIfEmpty: true,
                helperText: 'A senha só é necessária na criação ou quando desejar atualizá-la.',
            },
            {
                name: 'is_admin',
                label: 'Usuário administrador',
                type: 'checkbox',
                defaultValue: false,
                hideOnEdit: true,
            },
            {
                name: 'opm_id',
                label: 'OPM',
                type: 'select',
                valueType: 'number',
                omitIfEmpty: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/opms');
                    return data;
                },
                getOptionLabel: (option) => option.bpm,
            },
            {
                name: 'posto_graduacoes_id',
                label: 'Posto/Graduação',
                type: 'select',
                valueType: 'number',
                omitIfEmpty: true,
                fetchOptions: async () => {
                    const { data } = await api.get('/posto_graduacoes');
                    return data;
                },
                getOptionLabel: (option) => option.nome,
            },
        ],
        [],
    );

    const transformSubmit = (values, editing) => {
        const payload = { ...values };

        if (Object.prototype.hasOwnProperty.call(values, 'is_admin')) {
            payload.is_admin = Boolean(values.is_admin);
        }

        if (Object.prototype.hasOwnProperty.call(payload, 'opm_id') && !payload.opm_id) {
            payload.opm_id = null;
        }

        if (Object.prototype.hasOwnProperty.call(payload, 'posto_graduacoes_id') && !payload.posto_graduacoes_id) {
            payload.posto_graduacoes_id = null;
        }

        if (!payload.password) {
            delete payload.password;
        }

        return payload;
    };

    return (
        <ResourcePage
            title="Usuários"
            description="Cadastro de policiais, responsáveis e administradores do sistema."
            resource="/users"
            columns={columns}
            fields={fields}
            transformSubmit={transformSubmit}
        />
    );
}
