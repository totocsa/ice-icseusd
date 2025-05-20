import { ref } from "vue"
import { router, usePage } from "@inertiajs/vue3"

import { t } from "@IceDatabaseTranslationLocally/Components/totocsa/LocalTranslation/translation.js"

export function useIndex(props) {
    const page = usePage()
    const editableResults = ref({})

    const allEditableResults = () => {
        return editableResults.value
    }

    const getEditableResult = (id, field) => {
        return editableResults.value?.[id]?.[field] ?? false
    }

    const fetchIndex = () => {
        const url = props?.configName
            ? route("icseusd.generics.index", {
                configName: props.configName,
                components: { index: page.component },
            })
            : route(`${props.routePrefix}${props.routeController}.index`)

        try {
            router.get(
                url,
                {
                    filters: props.filters,
                    paging: {
                        page: props.items.current_page,
                        per_page: props.items.per_page,
                    },
                    sort: {
                        field: props.sort.field,
                        direction: props.sort.direction,
                    },
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: props.paramNames.index,
                    onSuccess: (response) => {
                        //console.log("Fetching: success", response)
                    },
                    error: (response) => {
                        console.log("Fetching: error")
                    },
                }
            )
        } catch (error) {
            console.error("Error fetching Index:", error)
        }
    }

    const setPage = (page) => {
        if (page > 0 && page <= props.items.last_page) {
            props.items.current_page = page
            fetchIndex()
        }
    }

    const setPerPage = (per_page) => {
        if (true) {
            props.items.per_page = per_page
            fetchIndex()
        }
    }

    const setSort = (field) => {
        if (props.sort.field == field) {
            props.sort.direction =
                props.sort.direction === "asc" ? "desc" : "asc"
        } else {
            props.sort.field = field
            props.sort.direction = "asc"
        }

        fetchIndex()
    }

    const setFilters = (filters) => {
        for (let i in filters) {
            props.filters[i] = filters[i]
        }

        fetchIndex()
    }

    const setTitleText = (data) => {
        if (Array.isArray(data)) {
            let title = t(data?.[0], data?.[1])
            title += data.length > 2 ? " - " + t(data?.[2], data?.[3]) : ""

            title += data?.[4] ? " - " + data?.[4] : ""
            return title
        } else {
            return data
        }
    }

    const saveEditableField = (routeController, config, updateReloadFlag) => {
        router.post(
            route(`${routeController}.saveEditable`),
            {
                route: {
                    current: route().current(),
                    params: route().params,
                },
                editable: config,
            },
            {
                preserveUrl: true,
                preserveState: true,
                preserveScroll: true,
                only: ["editableResults"],
                onSuccess: (response) => {
                    let i1, i2

                    for (i1 in response.props.editableResults) {
                        for (i2 in response.props.editableResults[i1]) {
                            editableResults.value[i1] ??= {}
                            editableResults.value[i1][i2] =
                                response.props.editableResults[i1][i2]
                        }
                    }

                    updateReloadFlag()
                },
                onError: (error) => {
                    console.log("onError", error)
                },
            }
        )
    }

    return {
        props,
        setPage,
        setPerPage,
        setSort,
        setFilters,
        setTitleText,
        saveEditableField,
        allEditableResults,
        getEditableResult,
    }
}
