<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { useDestroyItemForm } from "@IceIcseusd/Components/Icseusd/js/useDestroyItemForm.js"
import ScrollableNav from '@IceIcseusd/Components/ScrollableNav.vue';
import LocalTranslation from '@IceDatabaseTranslationLocally/Components/LocalTranslation/LocalTranslation.vue';

const page = usePage();

const props = defineProps({
    config: Object,
    modelClassName: String,
    item: Object,
    orders: Object,
    active: String,
});

const { modalLiFoAddToStack, confirmDestroyItemForm } = useDestroyItemForm(props)

const getLinkCssClass = (actionName) => {
    let cssClass = 'rounded pt-1 pb-1 pl-2 pr-2 whitespace-nowrap'

    const blueActions = ['index', 'export', 'loaderrefresh',
        'roles', 'permissions', 'roleHasPermissions', 'modelHasPermissions', 'modelHasRoles']

    if (blueActions.includes(actionName)) {
        cssClass += props.active + ' border border-blue-500 hover:bg-blue-500'
            + (actionName === props.active ? ' bg-blue-600 text-gray-100' : ' bg-white text-blue-500 hover:text-gray-100')
    } else if (['create', 'import'].includes(actionName)) {
        cssClass += props.active + ' border border-green-500 hover:bg-green-500'
            + (actionName === props.active ? ' bg-green-600 text-gray-100' : ' bg-white text-green-500 hover:text-gray-100')
    } else if (actionName === 'edit') {
        cssClass += props.active + ' border border-yellow-500 hover:bg-yellow-500'
            + (actionName === props.active ? ' bg-yellow-600 text-gray-100' : ' bg-white text-yellow-500 hover:text-gray-100')
    }
    else if (actionName === 'show') {
        cssClass += props.active + ' border border-sky-500 hover:bg-sky-500'
            + (actionName === props.active ? ' bg-sky-600 text-gray-100' : ' bg-white text-sky-500 hover:text-gray-100')
    }
    else if (actionName === 'destroy') {
        cssClass += props.active + ' border border-rose-500 hover:bg-rose-500'
            + (actionName === props.active ? ' bg-rose-600 text-gray-100' : ' bg-white text-rose-500 hover:text-gray-100')
    }

    return cssClass
}
</script>

<template>
    <ScrollableNav :activeElementId="props.active">
        <div v-for="(i, index) in props.config" :key="`action-menu-${index}`"
            class="inline-block m-2 first:ml-0 last:mr-0">
            <template v-if="index === 'destroy'">
                <a v-bind="i.attributes" :class="getLinkCssClass(index)"
                    @click.stop.prevent="modalLiFoAddToStack(item, props.orders, confirmDestroyItemForm, { url: i.attributes.href })">
                    <LocalTranslation category="ActionMenu" :subtitle="i.label" />
                </a>
            </template>

            <Link v-else v-bind="i.attributes" :id="index" :class="getLinkCssClass(index)">
            <LocalTranslation category="ActionMenu" :subtitle="i.label" />
            </Link>
        </div>
    </ScrollableNav>
</template>
