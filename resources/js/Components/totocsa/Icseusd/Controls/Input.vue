<script setup>
const props = defineProps({
    formData: Object,
    field: String,
    type: String,
    prefix: String,
    modelIdName: Function,
})

const modelIdName = props.modelIdName(props.formData, props.field, props.prefix)
</script>

<template>
    <input v-model="modelIdName.model" :type="props.type" :id="modelIdName.id" :name="modelIdName.name" />
</template>
