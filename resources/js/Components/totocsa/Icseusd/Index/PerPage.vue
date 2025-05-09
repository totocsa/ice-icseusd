<script setup>

const props = defineProps({
    defaultLinkClass: String,
    currentLinkClass: String,
    items: Object,
    per_pages: [Object, Array],
    setPerPage: Function,
});

const per_pages = Array.isArray(props.per_pages) ?
    props.per_pages.reduce((a, v) => ({ ...a, [v]: v }), {}) :
    props.per_pages

</script>

<template>
    <nav class="isolate inline-flex py-1 -space-x-px rounded-md shadow-sm" aria-label="Per page">
        <template v-for="(v, k) in per_pages">
            <a href="#" @click.stop.prevent="setPerPage(k)"
                :class="[k == props.items.per_page ? props.currentLinkClass : props.defaultLinkClass]">
                {{ v }}
            </a>
        </template>
    </nav>
</template>
