<script setup>
const props = defineProps({
    formData: Object,
    field: String,
    items: Object,
    prefix: String,
    modelIdName: Function,
})

const modelIdName = props.modelIdName(props.formData, props.field, props.prefix)
</script>

<template>
    <select v-model="modelIdName.model" :id="modelIdName.id" :name="modelIdName.name">
        <option v-for="i in props.items" :value="i.value">{{ i.text }}</option>
    </select>
</template>
