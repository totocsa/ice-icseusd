<script setup>
import IcseusdControl from './Controls/IcseusdControl.vue';
import LocalTranslation from "@IceDatabaseTranslationLocally/Components/totocsa/LocalTranslation/LocalTranslation.vue";

const props = defineProps({
    config: Object,
})

const itemFields = {}
for (let i of props.config.orders.item.fields) {
    if (props.config.fields.show[i] === undefined) {
        itemFields[i] = {
            tagName: 'ITEM_TEXT',
        }
    } else {
        itemFields[i] = props.config.fields.show[i]
    }
}
</script>

<template>
    <div class="table w-auto ml-auto mr-auto">
        <div v-for="i in props.config.orders.item.fields" :key="i" class="item table-row bg-blue-200 even:bg-blue-100">
            <div class="label table-cell p-2 font-bold">
                <LocalTranslation :category="props.config.modelClassName" :subtitle="i" />
            </div>

            <div class="value table-cell p-2">
                <IcseusdControl :itemField="itemFields[i]" :item="props.config.item" :field="i" />
            </div>
        </div>
    </div>
</template>
