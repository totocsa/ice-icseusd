import { reactive, toRef } from "vue"

export function useFilters() {
    const modelIdName = (formData, field, prefix) => {
        return reactive({
            model: toRef(formData, field),
            name: `${prefix}[${field}]`,
            id: `${prefix}_${field}`,
        })
    }

    return { modelIdName }
}
