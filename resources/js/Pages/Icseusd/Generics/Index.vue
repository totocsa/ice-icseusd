<script setup>
import IcseusdIndex from '@IceIcseusd/Components/Icseusd/Index.vue';

const props = defineProps({
    configName: String,
    userRoles: Object,
    routePrefix: String,
    routeController: String,
    routeParameterName: [String, Object],
    modelClassName: String,
    items: Object,
    per_pages: {
        type: [Array, Object],
        default: () => [10, 20, 50, 100],
    },
    itemButtons: Object,
    filters: Object,
    sort: Object,
    fields: Object,
    orders: Object,
    routes: Object,
    editableResults: Object,
    additionalData: Object,
    selectedItemId: Number,
    paramNames: Object,
})

const actionMenuConfig = route().has(`${props.routeController}.create`) ? {
    create: {
        label: 'Create',
        attributes: {
            href: route(`${props.routeController}.create`),
        }
    },
} : {}
</script>

<template>
    <IcseusdIndex @item-click="'item-click'" :selectedItemId="props.selectedItemId" :config="props" />
</template>
