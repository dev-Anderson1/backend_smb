import { useEffect, useMemo, useState } from 'react';

const emptyValue = (field, isEditing) => {
    if (typeof field.defaultValue !== 'undefined') {
        return field.defaultValue;
    }

    if (isEditing && typeof field.defaultEditValue !== 'undefined') {
        return field.defaultEditValue;
    }

    if (field.type === 'number') {
        return '';
    }

    if (field.type === 'checkbox') {
        return false;
    }

    return '';
};

const getValue = (source, path) => {
    if (!path.includes('.')) {
        return source?.[path];
    }

    return path.split('.').reduce((acc, key) => (acc ? acc[key] : undefined), source);
};

const normalizeValue = (field, value) => {
    if (field.type === 'number') {
        if (value === '' || value === null || Number.isNaN(Number(value))) {
            return '';
        }
        return Number(value);
    }

    if (field.type === 'checkbox') {
        return Boolean(value);
    }

    if (field.type === 'select' && field.valueType === 'number') {
        return value === '' ? '' : Number(value);
    }

    if (typeof field.normalize === 'function') {
        return field.normalize(value);
    }

    return value;
};

export default function ResourceForm({
    fields,
    initialData,
    isEditing,
    onSubmit,
    onCancel,
    submitting,
}) {
    const [values, setValues] = useState({});
    const [errors, setErrors] = useState({});
    const [options, setOptions] = useState({});
    const [loadingOptions, setLoadingOptions] = useState({});

    useEffect(() => {
        const baseValues = fields.reduce((acc, field) => {
            const value = typeof field.accessor === 'string'
                ? getValue(initialData, field.accessor)
                : getValue(initialData, field.name);

            acc[field.name] = typeof value !== 'undefined' ? normalizeValue(field, value) : emptyValue(field, isEditing);

            return acc;
        }, {});

        setValues(baseValues);
    }, [fields, initialData, isEditing]);

    useEffect(() => {
        let cancelled = false;

        const loadOptions = async () => {
            await Promise.all(
                fields
                    .filter((field) => field.type === 'select' && typeof field.fetchOptions === 'function')
                    .map(async (field) => {
                        setLoadingOptions((prev) => ({ ...prev, [field.name]: true }));

                        try {
                            const data = await field.fetchOptions();
                            if (!cancelled) {
                                setOptions((prev) => ({ ...prev, [field.name]: data }));
                            }
                        } catch (err) {
                            console.error(`Erro ao carregar opções para ${field.name}`, err);
                        } finally {
                            if (!cancelled) {
                                setLoadingOptions((prev) => ({ ...prev, [field.name]: false }));
                            }
                        }
                    }),
            );
        };

        loadOptions();

        return () => {
            cancelled = true;
        };
    }, [fields]);

    const allOptions = useMemo(() => {
        const staticOptions = fields
            .filter((field) => field.type === 'select' && Array.isArray(field.options))
            .reduce((acc, field) => ({ ...acc, [field.name]: field.options }), {});

        return { ...staticOptions, ...options };
    }, [fields, options]);

    const handleChange = (name, value) => {
        setValues((prev) => ({ ...prev, [name]: value }));
    };

    const visibleFields = fields.filter((field) => {
        if (isEditing && field.hideOnEdit) {
            return false;
        }

        if (!isEditing && field.hideOnCreate) {
            return false;
        }

        return true;
    });

    const validate = () => {
        const newErrors = {};

        visibleFields.forEach((field) => {
            const isRequired = field.required === true
                || (!isEditing && field.requiredOnCreate)
                || (isEditing && field.requiredOnEdit);

            if (!isRequired) {
                return;
            }

            const value = values[field.name];
            if (field.type === 'checkbox') {
                if (!value) {
                    newErrors[field.name] = 'Campo obrigatório';
                }
                return;
            }

            if (value === '' || value === null || typeof value === 'undefined') {
                newErrors[field.name] = 'Campo obrigatório';
            }
        });

        setErrors(newErrors);

        return Object.keys(newErrors).length === 0;
    };

    const handleSubmit = (event) => {
        event.preventDefault();

        if (!validate()) {
            return;
        }

        const payload = visibleFields.reduce((acc, field) => {
            const rawValue = values[field.name];
            const parsedValue = normalizeValue(field, rawValue);

            if (field.omitIfEmpty && (parsedValue === '' || parsedValue === null || typeof parsedValue === 'undefined')) {
                return acc;
            }

            acc[field.name] = parsedValue;
            return acc;
        }, {});

        onSubmit(payload);
    };

    return (
        <form className="resource-form" onSubmit={handleSubmit}>
            <div className="resource-form-grid">
                {visibleFields.map((field) => {
                    const fieldOptions = allOptions[field.name] || [];
                    const loading = loadingOptions[field.name];
                    const value = values[field.name];

                    return (
                        <label key={field.name} className="form-field">
                            <span className="form-label">
                                {field.label}
                                {(field.required || field.requiredOnCreate || field.requiredOnEdit) && <span className="form-required">*</span>}
                            </span>
                            {field.type === 'textarea' && (
                                <textarea
                                    value={value}
                                    onChange={(event) => handleChange(field.name, event.target.value)}
                                    placeholder={field.placeholder}
                                    rows={field.rows || 3}
                                />
                            )}
                            {field.type === 'select' && (
                                <select
                                    value={value ?? ''}
                                    onChange={(event) => handleChange(field.name, event.target.value)}
                                    disabled={loading || field.disabled}
                                >
                                    <option value="">Selecione...</option>
                                    {fieldOptions.map((option) => {
                                        const optionValue = field.getOptionValue ? field.getOptionValue(option) : option.id;
                                        const optionLabel = field.getOptionLabel ? field.getOptionLabel(option) : option.label;

                                        return (
                                            <option key={optionValue} value={optionValue}>
                                                {optionLabel}
                                            </option>
                                        );
                                    })}
                                </select>
                            )}
                            {field.type === 'checkbox' && (
                                <input
                                    type="checkbox"
                                    checked={Boolean(value)}
                                    onChange={(event) => handleChange(field.name, event.target.checked)}
                                />
                            )}
                            {field.type !== 'textarea' && field.type !== 'select' && field.type !== 'checkbox' && (
                                <input
                                    type={field.type || 'text'}
                                    value={value ?? ''}
                                    onChange={(event) => handleChange(field.name, event.target.value)}
                                    placeholder={field.placeholder}
                                />
                            )}
                            {field.helperText && <span className="form-helper">{field.helperText}</span>}
                            {errors[field.name] && <span className="form-error">{errors[field.name]}</span>}
                        </label>
                    );
                })}
            </div>
            <div className="form-actions">
                <button type="button" className="button button-secondary" onClick={onCancel}>
                    Cancelar
                </button>
                <button type="submit" className="button" disabled={submitting}>
                    {submitting ? 'Salvando...' : 'Salvar'}
                </button>
            </div>
        </form>
    );
}
