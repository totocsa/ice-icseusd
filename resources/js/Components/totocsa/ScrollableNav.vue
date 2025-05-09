<template>
    <div class="relative w-full select-none">
        <!-- Bal nyíl -->
        <button v-if="canScrollLeft" @click="scrollLeft"
            class="absolute left-0 m-0 top-1/2 -translate-y-1/2 z-10 bg-white p-1 rounded-[1.5rem] shadow-[0_0_0.125rem] shadow-gray-800">
            <ChevronLeftIcon class="size-5" aria-hidden="true" />
        </button>

        <!-- Görgethető sáv -->
        <div ref="scrollContainer" class="overflow-x-auto whitespace-nowrap scrollbar-hide scroll-smooth"
            @scroll="checkScroll">
            <slot />
        </div>

        <!-- Jobb nyíl -->
        <button v-if="canScrollRight" @click="scrollRight"
            class="absolute right-0 m-0 top-1/2 -translate-y-1/2 z-10 bg-white p-1 rounded-[1.5rem] shadow-[0_0_0.125rem] shadow-gray-800">
            <ChevronRightIcon class="size-5" aria-hidden="true" />
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, nextTick } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    activeElementId: {
        type: String,
        default: null,
    },
})

const scrollContainer = ref(null)
const canScrollLeft = ref(false)
const canScrollRight = ref(false)
const isDragging = ref(false)
const lastX = ref(0)

const checkScroll = () => {
    const el = scrollContainer.value
    if (!el) return

    canScrollLeft.value = el.scrollLeft > 0
    canScrollRight.value = el.scrollLeft + el.clientWidth < el.scrollWidth - 1
}

const scrollLeft = () => {
    scrollContainer.value.scrollBy({ left: -200, behavior: 'smooth' })
}

const scrollRight = () => {
    scrollContainer.value.scrollBy({ left: 200, behavior: 'smooth' })
}

const onMouseDown = (e) => {
    isDragging.value = true
    lastX.value = e.clientX
}

const onMouseUp = () => {
    isDragging.value = false
}

const scrollContainerMouseMove = (event) => {
    if (!isDragging.value) return
    const dx = event.clientX - lastX.value
    scrollContainer.value.scrollLeft -= dx
    lastX.value = event.clientX
}

onMounted(() => {
    checkScroll()
    const el = scrollContainer.value
    if (el) {
        window.addEventListener('mouseup', onMouseUp)
        window.addEventListener('resize', checkScroll)
        el.addEventListener('mousedown', onMouseDown)
        el.addEventListener('mousemove', scrollContainerMouseMove)
        el.addEventListener('scroll', checkScroll)

        nextTick(() => {
            if (props.activeElementId) {
                const activeEl = el.querySelector(`#${props.activeElementId}`)
                if (activeEl) {
                    el.scrollTo({ left: activeEl.offsetLeft, behavior: 'smooth' })
                }
            }
        })
    }
})

onBeforeUnmount(() => {
    const el = scrollContainer.value
    window.removeEventListener('mouseup', onMouseUp)
    window.removeEventListener('resize', checkScroll)
    if (el) {
        el.removeEventListener('mousedown', onMouseDown)
        el.removeEventListener('mousemove', scrollContainerMouseMove)
        el.removeEventListener('scroll', checkScroll)
    }
})
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
