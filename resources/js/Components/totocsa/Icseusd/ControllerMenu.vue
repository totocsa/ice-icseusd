<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Dropdown from '@/Components/Dropdown.vue'
import DropdownLink from '@/Components/DropdownLink.vue'
import ScrollableNav from '@/Components/totocsa/ScrollableNav.vue'
import LocalTranslation from '@/Components/totocsa/LocalTranslation/LocalTranslation.vue'

const props = defineProps({
    userRoles: Object,
    groupName: String,
    active: String,
});

const page = usePage();

const groups = computed(() => {
    const items = [
        {
            name: 'default',
            subtitle: 'Default',
            href: route('appRoot'),
        },
    ]

    if (props.userRoles.Developer) {
        items.push({
            name: 'development',
            subtitle: 'Development',
            href: route('authorization.index'),
        })
    }

    if (props.userRoles.Administrator) {
        items.push({
            name: 'administration',
            subtitle: 'Administration',
            href: route('users.index'),
        })
    } else if (props.userRoles.Translator) {
        items.push({
            name: 'administration',
            subtitle: 'Administration',
            href: route('translations.index'),
        })
    }

    items.sort((a, b) => a.subtitle.localeCompare(b.subtitle))

    return items
})

const currentGroup = computed(() =>
    groups.value.find(({ name }) => name === props.groupName)
)
</script>

<template>
    <ScrollableNav :activeElementId="props.active">
        <div v-if="groups.length > 1" class="inline-block m-2 first:ml-0 last:mr-0 static">
            <Dropdown align="left" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button type="button"
                            class="inline-flex items-center rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                            <LocalTranslation category="ControllerMenu-groupName" :subtitle="currentGroup.subtitle" />

                            <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <template v-for="i in groups" :key="`groups-${i.subtitle}`">
                        <DropdownLink v-if="i.name !== currentGroup.name" :href="i.href">
                            <LocalTranslation category="ControllerMenu-groupName" :subtitle="i.subtitle" />
                        </DropdownLink>
                    </template>
                </template>
            </Dropdown>
        </div>

        <slot />
    </ScrollableNav>
</template>
