<script setup>
import { Link } from '@inertiajs/vue3'
import { t } from "@/Components/totocsa/LocalTranslation/translation.js"
import IconAsync from '@/Components/bealejd/blog-async-icons/src/components/Icons/IconAsync.vue'
import IcseusdControl from '@IceIcseusd/Components/totocsa/Icseusd/Controls/IcseusdControl.vue'
import LocalTranslation from "@/Components/totocsa/LocalTranslation/LocalTranslation.vue"

const props = defineProps({
    userRoles: Object,
    routePrefix: String,
    routeController: String,
    routeParameterName: [String, Object],
    modelClassName: String,
    item: Object,
    fields: Object,
    orders: Object,
    itemButtons: Object,
    showDestroyItemForm: Function,
    editableStart: Function,
    getEditableResult: Function,
    reloadFlag: Number,
    selectedItemId: Number,
    modalLiFoAddToStack: Function,
    confirmDestroyItemForm: Function,
    itemCursor: { type: String, default: '' },
})

const itemFields = {}
for (let i of props.orders.item.fields) {
    if (props.fields.item[i] === undefined) {
        itemFields[i] = {
            tagName: 'ITEM_TEXT',
        }
    } else {
        itemFields[i] = props.fields.item[i]
    }
}

const emit = defineEmits(['item-click', 'item-button-click'])

const setHref = (url, item) => {
    let href, key
    if (url.type === 'route') {
        const routeName = url.route
            .replaceAll('{{props.routePrefix}}', props.routePrefix)
            .replaceAll('{{props.routeController}}', props.routeController)
        const params = {}
        for (let i in url.routeParams) {
            key = i.replaceAll('{{props.routeParameterName}}', props.routeParameterName)

            params[key] = url.routeParams[i].replaceAll('{{item.id}}', item.id)
        }

        href = route(routeName, params)
    }

    return href
}

const showIf = (condition, context) => {
    try {
        return new Function("item", `return ${condition}`)(context)
    } catch (e) {
        console.error("Hibás feltétel:", condition, e)
        return false
    }
}
</script>

<template>
    <div @click="$emit('item-click', item)" class="m-2 p-2 w-[200px] max-w-[200px] min-w-[200px]"
        :class="[item.id === selectedItemId ? 'bg-indigo-200' : 'bg-gray-200', props.itemCursor]">
        <div class="flex flex-col">
            <div v-for="(i1, index) in props.orders.item.fields" :key="`${i1.id}-${index}`">
                <div :title="t(props.modelClassName, i1)"
                    class="font-bold overflow-x-hidden text-ellipsis whitespace-nowrap">
                    <LocalTranslation :category="props.modelClassName" :subtitle="i1" />
                </div>

                <!--<div
                    v-if="props.fields[i1]?.editableOnIndex && route().has(`${props.routePrefix}${props.routeController}.saveEditable`)">
                    <div @click="editableStart($event, { 'id': item.id, 'field': i1 })" :title="item[i1]"
                        :class="['border-[1px] cursor-pointer pl-1 pr-1 overflow-x-hidden text-ellipsis whitespace-nowrap',
                            fieldError(item.id, i1) && fieldError(item.id, i1).hasError ? 'bg-rose-100 border-rose-300' : 'bg-emerald-100 border-emerald-300']">
                        {{
                            fieldError(item.id, i1) ?
                                (fieldError(item.id, i1).value > '' ? fieldError(item.id, i1).value : '&nbsp;') :
                                (item[i1] > '' ? item[i1] : '&nbsp;')
                        }}
                    </div>

                    <div v-if="fieldError(item.id, i1).hasError" class="flex text-red-600">
                        {{ fieldError(item.id, i1) ? fieldError(item.id, i1)?.errors?.[0].message : '' }}
                        <LocalTranslation :category="fieldError(item.id, i1)?.errors?.[0].key"
                            :subtitle="fieldError(item.id, i1).errors?.[0].original" :showText="false" />
                    </div>
                </div>

                <div v-if="false"></div>
                <div v-else :title="item[i1]" class="overflow-x-hidden text-ellipsis whitespace-nowrap">
                    {{ item[i1] > '' ? item[i1] : '&nbsp;' }}
                </div>
-->
                <IcseusdControl :itemField="itemFields[i1]" :clickHandler="editableStart"
                    :getEditableResult="props.getEditableResult" :item="item" :field="i1"
                    :route="`${props.routePrefix}${props.routeController}.saveEditable`" />
            </div>
        </div>

        <div v-if="props.orders.itemButtons.length > 0" class="flex justify-start mt-4">
            <template v-for="i2 in props.orders.itemButtons" :key="`${item.id}-${i2}`">
                <Link v-if="!['destroy'].includes(i2) && (!props.itemButtons[i2]?.itemButtonClick ?? false)"
                    :href="setHref(props.itemButtons[i2].url, item)" :class="props.itemButtons[i2].cssClass">
                <IconAsync :name="props.itemButtons[i2].icon.name" :type="props.itemButtons[i2].icon.type"
                    class="w-6" />
                </Link>

                <a v-if="(props.itemButtons[i2]?.itemButtonClick ?? false) && showIf(props.itemButtons[i2].showIf, item)"
                    :href="setHref(props.itemButtons[i2].url, item)"
                    @click.stop.prevent="$emit('item-button-click', { item: item, buttonId: i2, button: props.itemButtons[i2] })"
                    :class="props.itemButtons[i2].cssClass">
                    <IconAsync :name="props.itemButtons[i2].icon.name" :type="props.itemButtons[i2].icon.type"
                        class="w-6" />
                </a>

                <a v-if="i2 === 'destroy'" :href="setHref(props.itemButtons[i2].url, item)"
                    @click.stop.prevent="modalLiFoAddToStack(item, props.orders, props.confirmDestroyItemForm, { url: setHref(props.itemButtons[i2].url, item) })"
                    :class="props.itemButtons[i2].cssClass">
                    <IconAsync :name="props.itemButtons[i2].icon.name" :type="props.itemButtons[i2].icon.type"
                        class="w-6" />
                </a>
            </template>
        </div>
    </div>
</template>
