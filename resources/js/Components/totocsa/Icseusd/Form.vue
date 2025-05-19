<script setup>
import { useFilters } from "./js/useFilters.js";
import IcseusdControl from './Controls/IcseusdControl.vue';
import LocalTranslation from "@/Components/totocsa/LocalTranslation/LocalTranslation.vue";

const props = defineProps({
    config: Object,
    form: Object,
    formSubmit: Function,
    success: Boolean,
})

const itemFields = {}
for (let i of props.config.orders.item.fields) {
    if (props.config.fields.form[i] === undefined) {
        itemFields[i] = {
            tagName: 'INPUT',
            attributes: {
                type: 'text',
            },
        }
    } else {
        itemFields[i] = props.config.fields.form[i]
    }
}

const prefix = 'form'
const { modelIdName } = useFilters()

const fieldError = (field) => props.config?.errors?.[field]?.[0] ?? false
</script>

<template>
    <form @submit.prevent="formSubmit" class="table mx-auto w-auto text-left rounded-2xl">
        <template v-for="i in props.config.orders.item.fields">
            <div class="mb-4">
                <div>
                    <div>
                        <label :for="`${prefix}_${i}`">
                            <LocalTranslation :category="props.config.modelClassName" :subtitle="i" />
                        </label>
                    </div>

                    <div>
                        <IcseusdControl :itemField="itemFields[i]" :modelIdName="modelIdName" :prefix="prefix"
                            :formData="form" :field="i" />
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
