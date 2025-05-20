<script setup>
import { ref } from "vue"
import { useIndex } from "../js/useIndex.js"
import { useDestroyItemForm } from "../js/useDestroyItemForm.js"
import Item from '@IceIcseusd/Components/Icseusd/Index/Item.vue'
import EditableText from "@IceIcseusd/Components/Icseusd/Controls/EditableText.vue"

const props = defineProps({
    configName: String,
    userRoles: Object,
    routePrefix: String,
    routeController: String,
    routeParameterName: [String, Object],
    modelClassName: String,
    items: Object,
    fields: Object,
    orders: Object,
    itemButtons: Object,
    editableResults: Object,
    selectedItemId: Number,
    itemCursor: String,
})

defineEmits(['item-click', 'item-button-click'])

const { saveEditableField, getEditableResult } = useIndex(props)
const { modalLiFoAddToStack, confirmDestroyItemForm } = useDestroyItemForm(props)

const editableSaveConfig = ref(null)
const editableStyle = ref(null)
const editableInput = ref(null)

const editableSaveCssClasses = ['bg-amber-100', 'border-amber-300']

const editableStart = (event, config) => {
    editableSaveConfig.value = config
    editableInput.value = event.srcElement
    editableStyle.value = {
        x: event.srcElement.offsetLeft,
        y: event.srcElement.offsetTop,
        width: event.srcElement.clientWidth,
    }
}

const editableSubmit = (event) => {
    if (editableInput.value) {
        for (let i of editableSaveCssClasses) {
            event.srcElement.classList.add(i)
        }

        editableSaveConfig.value.value = event.srcElement.value
        saveEditableField(props.routeController, editableSaveConfig.value, updateReloadFlag)
    }

    editableInput.value = null
}

const editableCancel = (event) => {
    // A editableSave után még ez is futni akar, ezért kell az if.
    if (editableInput.value !== null) {
        editableInput.value = null
    }
}

const reloadFlag = ref(0)
const updateReloadFlag = () => {
    reloadFlag.value = reloadFlag.value > 1000 ? 0 : reloadFlag.value + 1
}
</script>

<template>
    <EditableText v-if="editableInput !== null" v-model="editableInput.innerText" :style="editableStyle"
        :target="editableInput" @keyup.enter="editableSubmit" @focusout="editableCancel" @keyup.esc="editableCancel" />

    <div class="flex flex-wrap justify-center">
        <template v-for="it in $page.props.items.data" :key="`${it.id}-${reloadFlag.value}`">
            <Item @item-click="$emit('item-click', $event)" @item-button-click="$emit('item-button-click', $event)"
                :selectedItemId="props.selectedItemId" :itemCursor="props.itemCursor" :item="it"
                :editableStart="editableStart" :routePrefix="routePrefix" :routeController="routeController"
                :routeParameterName="routeParameterName" :modelClassName="modelClassName" :fields="fields"
                :orders="orders" :itemButtons="props.itemButtons" :getEditableResult="getEditableResult"
                :reloadFlag="reloadFlag" :modalLiFoAddToStack="modalLiFoAddToStack"
                :confirmDestroyItemForm="confirmDestroyItemForm" />
        </template>
    </div>
</template>
