<script setup>
import { XMarkIcon } from '@heroicons/vue/20/solid'
import { useModalLiFoStore } from '@/Components/totocsa/ModalLiFo/ModalLiFoStore.js'
import Modal from "@/Components/totocsa/ModalLiFo/Modal.vue"
import CountItemsAndTranslationIcon from "@/Components/totocsa/ModalLiFo/CountItemsAndTranslationIcon.vue"
import LocalTranslation from "@/Components/totocsa/LocalTranslation/LocalTranslation.vue"

const props = defineProps({
    itemId: String,
    title: {
        type: Object,
        default: () => ({
            category: 'form',
            subtitle: 'Delete confirm',
        })
    },
    question: {
        type: Object,
        default: () => ({
            category: 'form',
            subtitle: 'Are you sure you want to delete this item?',
        })
    },
    modelClassName: String,
    order: Object,
    destroyItem: Object,
    confirmSubmit: Function,
    confirmParams: Object,
})

const modalLiFoStore = useModalLiFoStore()

const closeModal = (event) => {
    modalLiFoStore.removeLast()
}
</script>

<template>
    <Modal>
        <form @submit.prevent="props.confirmSubmit(props.confirmParams)">
            <!-- Fejléc -->
            <div class="flex justify-between rounded-t-lg p-3 bg-red-500 text-gray-100">
                <div class="text-lg">
                    <LocalTranslation :category="props.title.category" :subtitle="props.title.subtitle" />
                </div>

                <div class="flex items-start ml-2">
                    <CountItemsAndTranslationIcon />

                    <XMarkIcon @click="closeModal"
                        class="bg-red-700 cursor-pointer inline-flex rounded hover:bg-red-800 w-5" />
                </div>
            </div>

            <!-- Tartalom -->
            <div class="bg-gray-100 p-3">
                <div class="flex flex-col items-center">
                    <div class="text-left">
                        <div v-for="i in props.order" :key="i" class="odd:bg-gray-400 even:bg-gray-200 pl-1 pr-1">
                            <div class="font-bold">
                                <LocalTranslation :category="props.modelClassName" :subtitle="i" />
                            </div>

                            <div>
                                {{ props.destroyItem[i] === '' ? '&nbsp;' : props.destroyItem[i] }}
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-center pt-6">
                    <LocalTranslation :category="props.question.category" :subtitle="props.question.subtitle" />
                </p>
            </div>


            <!-- Gombok -->
            <div class="flex justify-end space-x-3 bg-gray-100 border-t border-gray-400 p-3 rounded-b-lg">
                <button @click="closeModal" type="button" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">
                    <LocalTranslation category="form" subtitle="Close" />
                </button>

                <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">
                    <LocalTranslation category="form" subtitle="Confirm" />
                </button>
            </div>
        </form>
    </Modal>
</template>
