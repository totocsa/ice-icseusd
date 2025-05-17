<script setup>
import IcseusdInput from './Input.vue';
import IcseusdSelect from './Select.vue';
import IcseusdTextarea from './Textarea.vue';
import IcseusdFlag from './Flag.vue';

const props = defineProps({
    tagName: String,
    modelIdName: Function,
    prefix: String,
    formData: Object,
    field: String,
    type: String,
    options: Array,
})

const getOptionsList = (source) => source.reduce((acc, key) => acc?.[key], props);
</script>

<template>
    <icseusdInput v-if="props.tagName.toLocaleUpperCase() === 'INPUT'" :modelIdName="props.modelIdName"
        :prefix="props.prefix" :formData="props.formData" :field="props.field" :type="props.type" />

    <icseusdSelect v-if="props.tagName.toLocaleUpperCase() === 'SELECT'" :modelIdName="props.modelIdName"
        :prefix="props.prefix" :formData="props.formData" :field="props.field" :items="getOptionsList(props.options)" />

    <icseusdTextarea v-if="props.tagName.toLocaleUpperCase() === 'TEXTAREA'" :modelIdName="props.modelIdName"
        :prefix="props.prefix" :formData="props.formData" :field="props.field" />

    <IcseusdFlag v-if="props.tagName.toLocaleUpperCase() === 'FLAG'" :modelIdName="props.modelIdName"
        :prefix="props.prefix" :formData="props.formData" :field="props.field" />
</template>
