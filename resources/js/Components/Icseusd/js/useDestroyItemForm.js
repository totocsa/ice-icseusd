import { markRaw, ref } from "vue"
import { router } from "@inertiajs/vue3"
import { useModalLiFoStore } from "@IceModalLiFo/Components/ModalLiFo/ModalLiFoStore.js"
import DestroyItemForm from "@IceIcseusd/Components/Icseusd/ActionMenu/DestroyItemForm.vue"

export function useDestroyItemForm(props) {
    const isDestroyItemForm = ref(false)
    const destroyItem = ref(null)

    let urlDestroyItemForm

    const showDestroyItemForm = (url, item) => {
        isDestroyItemForm.value = true
        destroyItem.value = item
        urlDestroyItemForm = url
    }

    const modalLiFoAddToStack = (destroyItem, orders = undefined, confirmSubmit = undefined, confirmParams = {}) => {
        const itemId = location.protocol === 'https:' ? crypto.randomUUID() : 'x' + Date.now() + parseInt(Math.random() * (9999999 - 1000000) + 1000000)
        useModalLiFoStore().addToStack(itemId, markRaw(DestroyItemForm), {
            itemId: itemId,
            title: {
                category: 'form',
                subtitle: 'Delete confirm',
            },
            question: {
                category: 'form',
                subtitle: 'Are you sure you want to delete this item?',
            },
            modelClassName: props.modelClassName,
            order: orders === undefined ? props.orders.item.fields : orders.item.fields,
            destroyItem: destroyItem,
            confirmSubmit: confirmSubmit === undefined ? confirmDestroyItemForm : confirmSubmit,
            confirmParams: confirmParams,
        })
    }

    const confirmDestroyItemForm = (params) => {
        const currentUrl = new URL(location.href)
        const destroyUrl = new URL(params.url)
        destroyUrl.search = currentUrl.search

        router.delete(destroyUrl.href, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                router.reload()
                useModalLiFoStore().removeLast()
            },
        })
    }

    const closeDestroyItemForm = () => {
        isDestroyItemForm.value = false
        urlDestroyItemForm = ""
    }

    return {
        isDestroyItemForm,
        destroyItem,
        showDestroyItemForm,
        modalLiFoAddToStack,
        confirmDestroyItemForm,
        closeDestroyItemForm,
    }
}
