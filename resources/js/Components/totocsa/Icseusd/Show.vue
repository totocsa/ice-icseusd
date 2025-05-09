<script setup>
import LocalTranslation from "@/Components/totocsa/LocalTranslation/LocalTranslation.vue";

const props = defineProps({
    modelClassName: String,
    item: Object,
    orders: Object,
})
</script>

<template>
    <div class="table w-auto ml-auto mr-auto">
        <div v-for="i in props.orders.item.fields" :key="i" class="item table-row bg-blue-200 even:bg-blue-100">
            <div class="label table-cell p-2 font-bold">
                <LocalTranslation :category="props.modelClassName" :subtitle="i" />
            </div>
            <div class="value table-cell p-2">{{ item[i] }}</div>
        </div>
    </div>
</template>
