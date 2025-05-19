<script setup>
const props = defineProps({
    item: Object,
    field: String,
    options: {
        type: Object,
        default: {
            'true': 'true-1',
            'false': 'false-0',
        }
    },
})

const value = props.item[props.field] ? props.options.true : props.options.false
</script>

<template>
    <div :title="value" class="overflow-x-hidden text-ellipsis whitespace-nowrap">
        {{ value > '' ? value : '&nbsp;' }}
    </div>
</template>
