<script setup>
import { useFilters } from "./js/useFilters.js";
import icseusdInput from './Controls/Input.vue';
import icseusdSelect from './Controls/Select.vue';
import icseusdTextarea from './Controls/Textarea.vue';
import LocalTranslation from "@/Components/totocsa/LocalTranslation/LocalTranslation.vue";

const props = defineProps({
    config: Object,
    form: Object,
    formSubmit: Function,
    success: Boolean,
})

const prefix = 'form'
const { modelIdName } = useFilters()
const getOptionsList = (source) => source.reduce((acc, key) => acc?.[key], props);

const fieldError = (field) => props.config?.errors?.[field]?.[0] ?? false
</script>

<template>
    <form @submit.prevent="formSubmit" class="table m-auto w-auto text-left rounded-2xl">
        <template v-for="i in props.config.orders.item.fields">
            <div class="mb-4">
                <div>
                    <div>
                        <label :for="`${prefix}_${i}`">
                            <LocalTranslation :category="props.config.modelClassName" :subtitle="i" />
                        </label>
                    </div>

                    <div>
                        <icseusdInput v-if="props.config.fields[i].form.tag.toLowerCase() === 'input'"
                            :modelIdName="modelIdName" :prefix="prefix" :formData="form" :field="i"
                            :type="props.config.fields[i].form.attributes.type" />

                        <icseusdSelect v-if="props.config.fields[i].form.tag.toLowerCase() === 'select'"
                            :modelIdName="modelIdName" :prefix="prefix" :formData="form" :field="i"
                            :items="getOptionsList(props.config.fields[i].form.options)" />

                        <icseusdTextarea v-if="props.config.fields[i].form.tag.toLowerCase() === 'textarea'"
                            :modelIdName="modelIdName" :prefix="prefix" :formData="form" :field="i" />
                    </div>
                </div>

                <div v-if="fieldError(i)" class="flex text-red-600">
                    {{ fieldError(i) ? fieldError(i).message : '' }}
                    <LocalTranslation :category="fieldError(i).key" :subtitle="fieldError(i).original"
                        :showText="false" />
                </div>

            </div>
        </template>

        <div class="mt-4 text-center" :class="{ 'animate-success-form': success, }">
            <button class="bg-indigo-600 hover:bg-indigo-500 rounded p-2 text-gray-100">
                <LocalTranslation category="form" subtitle="Submit" />
            </button>
        </div>
    </form>
</template>
