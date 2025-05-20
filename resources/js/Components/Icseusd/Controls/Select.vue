<script setup>
const props = defineProps({
    formData: Object,
    field: String,
    options: Object,
    prefix: String,
    modelIdName: Function,
})

const modelIdName = props.modelIdName(props.formData, props.field, props.prefix)
</script>

<template>
    <select v-model="modelIdName.model" :id="modelIdName.id" :name="modelIdName.name">
        <option v-for="i of props.options" :value="i.value" :key="`option-${i.value}-${i.text}`">{{ i.text }}</option>
    </select>
</template>
