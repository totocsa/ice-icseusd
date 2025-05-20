<script setup>
import { useFilters } from "../js/useFilters.js";
import IcseusdControl from '../Controls/IcseusdControl.vue';
import LocalTranslation from "@IceDatabaseTranslationLocally/Components/LocalTranslation/LocalTranslation.vue";

const props = defineProps({
    modelClassName: String,
    filters: Object,
    fields: Object,
    order: Array,
    filtersForm: Object,
    setFilters: Function,
    additionalData: Object,
})

const itemFields = {}
for (let i of props.order) {
    if (props.fields.filter[i] === undefined) {
        itemFields[i] = {
            tagName: 'INPUT',
            attributes: {
                type: 'text',
            },
        }
    } else {
        itemFields[i] = props.fields.filter[i]
    }
}

const { modelIdName } = useFilters()

const prefix = 'filter'

const submitForm = () => {
    props.setFilters(props.filtersForm);
};
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
                <IcseusdControl :itemField="itemFields[i]" :modelIdName="modelIdName" :prefix="prefix"
                    :formData="props.filtersForm" :item="props.filtersForm" :field="i" />
            </div>
        </div>

        <div class="inline-block mb-1 ml-2 mr-2 mt-0">
            <button class="bg-indigo-600 hover:bg-indigo-500 m-0 pb-1 pl-2 pr-2 pt-1 rounded text-gray-100">
                <LocalTranslation category="form" subtitle="Submit" />
            </button>
        </div>
    </form>
</template>
