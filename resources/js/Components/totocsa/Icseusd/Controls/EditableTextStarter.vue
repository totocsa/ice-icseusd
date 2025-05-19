<script setup>
import { route } from '/vendor/tightenco/ziggy/src/js'
import IcseusdItemText from './ItemText.vue'
import LocalTranslation from "@/Components/totocsa/LocalTranslation/LocalTranslation.vue"

const props = defineProps({
    clickHandler: Function,
    field: String,
    getEditableResult: Function,
    item: Object,
    route: String,
})

const fieldError = (id, field) => props.getEditableResult(id, field)
</script>

<template>
    <div v-if="route().has(props.route)">
        <div @click="props.clickHandler($event, { 'id': props.item.id, 'field': field })"
            :title="props.item[props.field]"
            :class="['border-[1px] cursor-pointer pl-1 pr-1 overflow-x-hidden text-ellipsis whitespace-nowrap',
                fieldError(props.item.id, props.field) && fieldError(props.item.id, props.field).hasError ? 'bg-rose-100 border-rose-300' : 'bg-emerald-100 border-emerald-300']">
            {{
                fieldError(props.item.id, props.field) ?
                    (fieldError(props.item.id, props.field).value > '' ? fieldError(props.item.id, props.field).value :
                        '&nbsp;') :
                    (props.item[props.field] > '' ? props.item[props.field] : '&nbsp;')
            }}
        </div>

        <div v-if="fieldError(props.item.id, props.field).hasError" class="flex text-red-600">
            {{
                fieldError(props.item.id, props.field) ?
                    fieldError(props.item.id, props.field)?.errors?.[0].message :
                    ''
            }}

            <LocalTranslation :category="fieldError(props.item.id, props.field)?.errors?.[0].key"
                :subtitle="fieldError(props.item.id, props.field).errors?.[0].original" :showText="false" />
        </div>
    </div>

    <IcseusdItemText v-else :item="props.item" :field="props.field" />
</template>
