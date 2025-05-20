<script setup>
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/20/solid'
import LocalTranslation from "@IceDatabaseTranslationLocally/Components/totocsa/LocalTranslation/LocalTranslation.vue";

const props = defineProps({
    modelClassName: String,
    sort: Object,
    fields: Object,
    order: Object,
    setSort: Function,
})
</script>

<template>
    <div class="inline-block ml-2 mr-2">
        <template v-for="(i, index) in props.order" :key="i">
            <div :class="[index === props.sort.field ? 'active' : '', 'inline-block cursor-pointer ml-2 mr-2']"
                @click="props.setSort(index)">
                <span class="inline">
                    <LocalTranslation :category="props.modelClassName" :subtitle="index" />
                </span>
                <span class="inline-block min-w-4 w-4">
                    <template v-if="index === props.sort.field">
                        <ChevronDownIcon v-if="props.sort.direction === 'desc'" class="inline align-top min-w-4 w-4" />
                        <ChevronUpIcon v-if="props.sort.direction === 'asc'" class="inline align-top min-w-4 w-4" />
                    </template>
                </span>
            </div>
        </template>
    </div>
</template>
