<script setup>
import IcseusdItemBoolean from './ItemBoolean.vue'
import IcseusdItemFlag from './ItemFlag.vue'
import IcseusdItemText from './ItemText.vue'
import IcseusdInput from './Input.vue'
import IcseusdSelect from './Select.vue'
import IcseusdTextarea from './Textarea.vue'
import IcseusdFlag from './Flag.vue'
import IcseusdEditableTextStarter from "./EditableTextStarter.vue"

const props = defineProps({
    clickHandler: Function,
    field: String,
    formData: Object,
    getEditableResult: Function,
    ifEmptyField: String,
    item: Object,
    itemField: Object,
    modelIdName: Function,
    options: Object,
    prefix: String,
    route: String,
})

const upperCaseTagName = props.itemField.tagName.toLocaleUpperCase()
</script>

<template>
    <IcseusdItemBoolean v-if="upperCaseTagName === 'ITEM_BOOLEAN'" :item="props.item" :field="props.field"
        :options="itemField.options" />

    <IcseusdItemFlag v-if="upperCaseTagName === 'ITEM_FLAG'" :item="props.item" :field="props.field"
        :ifEmpty="props.itemField.ifEmpty" />

    <IcseusdItemText v-if="upperCaseTagName === 'ITEM_TEXT'" :item="props.item" :field="props.field" />

    <IcseusdInput v-if="upperCaseTagName === 'INPUT'" :modelIdName="props.modelIdName" :prefix="props.prefix"
        :formData="props.formData" :field="props.field" :type="props.itemField.attributes.type" />

    <IcseusdSelect v-if="upperCaseTagName === 'SELECT'" :modelIdName="props.modelIdName" :prefix="props.prefix"
        :formData="props.formData" :field="props.field" :options="props.itemField.options" />

    <IcseusdTextarea v-if="upperCaseTagName === 'TEXTAREA'" :modelIdName="props.modelIdName" :prefix="props.prefix"
        :formData="props.formData" :field="props.field" />

    <IcseusdFlag v-if="upperCaseTagName === 'FLAG'" :modelIdName="props.modelIdName" :prefix="props.prefix"
        :formData="props.formData" :field="props.field" />

    <IcseusdEditableTextStarter v-if="upperCaseTagName === 'EDITABLE_TEXT_STARTER'" :clickHandler="props.clickHandler"
        :getEditableResult="props.getEditableResult" :item="props.item" :field="props.field" :route="props.route" />
</template>
