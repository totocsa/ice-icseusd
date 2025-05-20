<script setup>
const props = defineProps({
    formData: Object,
    field: String,
    prefix: String,
    modelIdName: Function,
})

const modelIdName = props.modelIdName(props.formData, props.field, props.prefix)
</script>

<template>
    <textarea v-model="modelIdName.model" :id="modelIdName.id" :name="modelIdName.name">
    </textarea>
</template>
