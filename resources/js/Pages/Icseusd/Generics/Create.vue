<script setup>
import { reactive } from "vue"
import IceLayout from '@/Layouts/IceLayout.vue';
import IcseusdCreate from '@/Components/totocsa/Icseusd/Create.vue';
import ActionMenu from '@/Components/totocsa/Icseusd/ActionMenu/ActionMenu.vue';
import LocalTranslationHeader from '@/Components/totocsa/LocalTranslation/LocalTranslationHeader.vue';

const props = defineProps({
    routeController: String,
    routeParameterName: String,
    modelClassName: String,
    item: Object,
    orders: Object,
    fields: Object,
    errors: Object,
    additionalData: Object,
})

const actionMenuConfig = {
    index: {
        label: 'Index',
        attributes: {
            href: route(`${props.routeController}.index`),
        }
    },
}

let da = {}
for (let i in props.item) {
    da[i] = props.item[i]
}

const formData = reactive(da)

const titleArray = ['Users', 'Users', 'ActionMenu', 'Create']
</script>

<template>
    <IceLayout :title="titleArray" :authUser="$page.props.auth.user">
        <template #header>
            <LocalTranslationHeader :titleArray="titleArray" />
            <ActionMenu :config="actionMenuConfig" />
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-4 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <IcseusdCreate :config="props" :formData="formData" />
                </div>
            </div>
        </div>
    </IceLayout>
</template>
