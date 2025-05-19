<script setup>
import { reactive } from "vue"
import { useIndex } from "./js/useIndex.js";
import Items from '@/Components/totocsa/Icseusd/Index/Items.vue';
import Pagination from '@/Components/totocsa/Icseusd/Index/Pagination.vue';
import PerPage from '@/Components/totocsa/Icseusd/Index/PerPage.vue';
import Sort from '@/Components/totocsa/Icseusd/Index/Sort.vue';
import Filters from '@/Components/totocsa/Icseusd/Index/Filters.vue';

const props = defineProps({
    config: Object,
    selectedItemId: Number,
    itemCursor: String,
})

defineEmits(['item-click', 'item-button-click']);

const { setPage, setPerPage, setSort, setFilters } = useIndex(props.config)

let da = {}
for (let i in props.config.filters) {
    da[i] = props.config.filters[i]
}

const filtersForm = reactive(da)

const defaultLinkClass = "bg-gray-50 hover:bg-gray-100 inline-flex items-center font-semibold px-4 py-2 relative ring-1 ring-inset ring-gray-300 text-gray-900 text-sm"
const currentLinkClass = defaultLinkClass.replace('text-gray-900', 'text-gray-100')
    .replace('bg-gray-50', 'bg-indigo-600')
    .replace('hover:bg-gray-100', 'hover:bg-indigo-500')
</script>

<template>
    <div>
        <div class="text-right">
            <Filters :fields="props.config.fields" :filters="props.config.filters" :order="props.config.orders.filters"
                :configName="props.config.configName" :modelClassName="props.config.modelClassName"
                :setFilters="setFilters" :filtersForm="filtersForm" :additionalData="props.config.additionalData" />
            <Sort :sort="props.config.sort" :fields="props.config.fields" :order="props.config.orders.sorts"
                :configName="props.config.configName" :modelClassName="props.config.modelClassName"
                :setSort="setSort" />
            <PerPage :items="props.config.items" :per_pages="props.config.per_pages" :setPerPage="setPerPage"
                :defaultLinkClass="defaultLinkClass" :currentLinkClass="currentLinkClass" />
        </div>

        <Pagination :items="props.config.items" :setPage="setPage" :defaultLinkClass="defaultLinkClass"
            :currentLinkClass="currentLinkClass" />
        <Items @item-click="$emit('item-click', $event)" @item-button-click="$emit('item-button-click', $event)"
            :selectedItemId="props.selectedItemId" :itemCursor="props.itemCursor"
            :routePrefix="props.config.routePrefix" :routeController="props.config.routeController"
            :routeParameterName="props.config.routeParameterName" :configName="props.config.configName"
            :modelClassName="props.config.modelClassName" :fields="props.config.fields" :orders="props.config.orders"
            :itemButtons="props.config.itemButtons" :editableResults="props.config.editableResults" />
    </div>
</template>
