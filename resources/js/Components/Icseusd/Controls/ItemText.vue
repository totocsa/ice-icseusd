<script setup>
const props = defineProps({
    item: Object,
    field: String,
})

const value = props.item[props.field]
</script>

<template>
    <div :title="value" class="overflow-x-hidden text-ellipsis whitespace-nowrap">
        {{ value > '' ? value : '&nbsp;' }}
    </div>
</template>
