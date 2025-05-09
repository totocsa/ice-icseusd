<script setup>
import { useFilters } from "../js/useFilters.js";
import IcseusdInput from '../Controls/Input.vue';
import IcseusdSelect from '../Controls/Select.vue';
import IcseusdTextarea from '../Controls/Textarea.vue';
import LocalTranslation from "@/Components/totocsa/LocalTranslation/LocalTranslation.vue";

const props = defineProps({
    modelClassName: String,
    filters: Object,
    fields: Object,
    order: Array,
    filtersForm: Object,
    setFilters: Function,
    additionalData: Object,
})

const { modelIdName } = useFilters()

const filtersForm = props.filters
const prefix = 'filter'

const submitForm = () => {
    props.setFilters(props.filtersForm);
};

const getOptionsList = (source) => source.reduce((acc, key) => acc?.[key], props);
</script>

<template>
    <form class="inline-block py-1 text-left" @submit.stop.prevent="submitForm">
        <div v-for="i in props.order" class="inline-block mb-1 ml-2 mr-2 mt-0 text-left">
            <div>
                <label :for="`${prefix}_${i}`">
                    <LocalTranslation :category="props.modelClassName" :subtitle="i" />
                </label>
            </div>

            <div class="control">
                <IcseusdInput v-if="props.fields[i].filter.tag.toLowerCase() === 'input'" :formData="props.filtersForm"
                    :field="i" :modelIdName="modelIdName" :prefix="prefix"
                    :type="props.fields[i].filter.attributes.type" />

                <IcseusdSelect v-if="props.fields[i].filter.tag.toLowerCase() === 'select'"
                    :formData="props.filtersForm" :field="i" :modelIdName="modelIdName" :prefix="prefix"
                    :items="getOptionsList(props.fields[i].filter.options)" />

                <IcseusdTextarea v-if="props.fields[i].filter.tag.toLowerCase() === 'textarea'"
                    :formData="props.filtersForm" :field="i" :modelIdName="modelIdName" :prefix="prefix" />
            </div>
        </div>

        <div class="inline-block mb-1 ml-2 mr-2 mt-0">
            <button class="bg-indigo-600 hover:bg-indigo-500 m-0 pb-1 pl-2 pr-2 pt-1 rounded text-gray-100">
                <LocalTranslation category="form" subtitle="Submit" />
            </button>
        </div>
    </form>
</template>
