<script setup>
import { ref, onMounted, nextTick } from "vue";

const props = defineProps({
    modelValue: { type: String },
    style: { type: Object },
})

const emit = defineEmits(["save", "cancel"]);
const localValue = ref(props.modelValue);
const inputRef = ref(null);

const save = () => {
    emit("save");
};

const cancel = () => {
    emit("cancel")
}

onMounted(() => {
    nextTick(() => {
        if (inputRef.value) {
            inputRef.value.focus();
            inputRef.value.value = inputRef.value.value.trim()
        }
    });
})
</script>

<template>
    <div v-if="style" class="absolute bg-indigo-500" :style="{
        top: style.y + 'px',
        left: style.x + 'px',
        width: style.width + 'px',
    }">
        <input ref="inputRef" v-model="localValue" @blur="cancel" @keyup.enter="save" @keyup.esc="cancel"
            name="editable-field" class="bg-indigo-100 border-[1px] border-indigo-300 pb-0 pl-1 pr-1 pt-0 w-full" />
    </div>
</template>
