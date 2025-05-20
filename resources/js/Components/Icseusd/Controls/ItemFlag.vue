<script setup>
import "/node_modules/flag-icons/css/flag-icons.min.css"

const props = defineProps({
    item: Object,
    field: String,
    ifEmpty: String,
})

const isString = typeof props.item[props.field] === 'string'
const value = isString ?
    (props.item[props.field] === '' ? props.item[props.ifEmpty] : props.item[props.field]) :
    ''
</script>

<template>
    <div :title="value" :class="`fi fi-${value} h-[30px] !w-[40px]`"></div>
    <div>{{ props.item[props.field] > '' ? props.item[props.field] : '&nbsp;' }}</div>
</template>
