<script setup>
import { ChevronDoubleLeftIcon, ChevronDoubleRightIcon, ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import LocalTranslation from '@IceDatabaseTranslationLocally/Components/totocsa/LocalTranslation/LocalTranslation.vue';

const props = defineProps({
    defaultLinkClass: String,
    currentLinkClass: String,
    items: Object,
    page: Number,
    setPage: Function,
});

const arrowDefaultClass = props.defaultLinkClass.replace('px-4', 'px-2').replace('text-gray-900', 'text-gray-500')
</script>

<template>
    <div class="flex items-center justify-between py-1">
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    <LocalTranslation category="Paging" subtitle="Showing #from# to #to# of #total# results"
                        :params="{ '#from#': $page.props.items.from ?? 0, '#to#': $page.props.items.to ?? 0, '#total#': $page.props.items.total }" />
                </p>
            </div>

            <div>
                <nav class="isolate inline-flex flex-wrap -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <a href="#" @click.stop.prevent="setPage(1)" :class="[arrowDefaultClass, 'rounded-l-md']">
                        <span class="sr-only">First</span>
                        <ChevronDoubleLeftIcon class="size-5" aria-hidden="true" />
                    </a>

                    <a href="#" @click.stop.prevent="setPage($page.props.items.current_page - 1)"
                        :class="arrowDefaultClass">
                        <span class="sr-only">Previous</span>
                        <ChevronLeftIcon class="size-5" aria-hidden="true" />
                    </a>

                    <template v-for="link of $page.props.items.links">
                        <a v-if="!isNaN(link.label) || link.label === '...'" :href="link.url"
                            @click.stop.prevent="setPage(link.label)"
                            :class="[link.active ? props.currentLinkClass : props.defaultLinkClass]">
                            {{ link.label }}</a>
                    </template>

                    <a href="#" @click.stop.prevent="setPage($page.props.items.current_page + 1)"
                        :class="arrowDefaultClass">
                        <span class="sr-only">Next</span>
                        <ChevronRightIcon class="size-5" aria-hidden="true" />
                    </a>

                    <a href="#" @click.stop.prevent="setPage($page.props.items.last_page)"
                        :class="[arrowDefaultClass, 'rounded-r-md']">
                        <span class="sr-only">Last</span>
                        <ChevronDoubleRightIcon class="size-5" aria-hidden="true" />
                    </a>
                </nav>
            </div>
        </div>
    </div>
</template>
