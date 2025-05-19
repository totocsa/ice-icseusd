<script setup>
import "/node_modules/flag-icons/css/flag-icons.min.css"
import ScrollableNav from '@IceIcseusd/Components/totocsa/ScrollableNav.vue';

const props = defineProps({
    modelIdName: Function,
    formData: Object,
    field: String,
    prefix: String,
})

const modelIdName = props.modelIdName(props.formData, props.field, props.prefix)

const countryCodes = [
    'ad', 'ae', 'af', 'ag', 'ai', 'al', 'am', 'ao', 'aq', 'ar', 'arab', 'as', 'asean', 'at', 'au', 'aw', 'ax',
    'az', 'ba', 'bb', 'bd', 'be', 'bf', 'bg', 'bh', 'bi', 'bj', 'bl', 'bm', 'bn', 'bo', 'bq', 'br', 'bs', 'bt',
    'bv', 'bw', 'by', 'bz', 'ca', 'cc', 'cd', 'cefta', 'cf', 'cg', 'ch', 'ci', 'ck', 'cl', 'cm', 'cn', 'co',
    'cp', 'cr', 'cu', 'cv', 'cw', 'cx', 'cy', 'cz', 'de', 'dg', 'dj', 'dk', 'dm', 'do', 'dz', 'eac', 'ec', 'ee',
    'eg', 'eh', 'er', 'es', 'es-ct', 'es-ga', 'es-pv', 'et', 'eu', 'fi', 'fj', 'fk', 'fm', 'fo', 'fr', 'ga',
    'gb', 'gb-eng', 'gb-nir', 'gb-sct', 'gb-wls', 'gd', 'ge', 'gf', 'gg', 'gh', 'gi', 'gl', 'gm', 'gn', 'gp',
    'gq', 'gr', 'gs', 'gt', 'gu', 'gw', 'gy', 'hk', 'hm', 'hn', 'hr', 'ht', 'hu', 'ic', 'id', 'ie', 'il', 'im',
    'in', 'io', 'iq', 'ir', 'is', 'it', 'je', 'jm', 'jo', 'jp', 'ke', 'kg', 'kh', 'ki', 'km', 'kn', 'kp', 'kr',
    'kw', 'ky', 'kz', 'la', 'lb', 'lc', 'li', 'lk', 'lr', 'ls', 'lt', 'lu', 'lv', 'ly', 'ma', 'mc', 'md', 'me',
    'mf', 'mg', 'mh', 'mk', 'ml', 'mm', 'mn', 'mo', 'mp', 'mq', 'mr', 'ms', 'mt', 'mu', 'mv', 'mw', 'mx', 'my',
    'mz', 'na', 'nc', 'ne', 'nf', 'ng', 'ni', 'nl', 'no', 'np', 'nr', 'nu', 'nz', 'om', 'pa', 'pc', 'pe', 'pf',
    'pg', 'ph', 'pk', 'pl', 'pm', 'pn', 'pr', 'ps', 'pt', 'pw', 'py', 'qa', 're', 'ro', 'rs', 'ru', 'rw', 'sa',
    'sb', 'sc', 'sd', 'se', 'sg', 'sh', 'sh-ac', 'sh-hl', 'sh-ta', 'si', 'sj', 'sk', 'sl', 'sm', 'sn', 'so',
    'sr', 'ss', 'st', 'sv', 'sx', 'sy', 'sz', 'tc', 'td', 'tf', 'tg', 'th', 'tj', 'tk', 'tl', 'tm', 'tn', 'to',
    'tr', 'tt', 'tv', 'tw', 'tz', 'ua', 'ug', 'um', 'un', 'us', 'uy', 'uz', 'va', 'vc', 've', 'vg', 'vi', 'vn',
    'vu', 'wf', 'ws', 'xk', 'xx', 'ye', 'yt', 'za', 'zm', 'zw',
]
</script>

<template>
    <input v-model="modelIdName.model" type="text" :id="modelIdName.id" :name="modelIdName.name" />

    <div class="hidden sm:block sm:w-[571px] md:w-[671px] lg:w-[771px] xl:w-[871px] 2xl:w-[971px]">
        <ScrollableNav
            :activeElementId="'flag-' + (modelIdName.model === '' ? formData['locales-configname'] : modelIdName.model)">
            <div v-for="i of countryCodes" class="inline-block m-[2px] p-[4px] hover:bg-ice-700" :class="{
                '': i !== modelIdName.model,
                'bg-ice-950 text-ice-100': modelIdName.model === '' ? i === formData['locales-configname'] : i === modelIdName.model,
            }">

                <figure @click="modelIdName.model = i" :id="`flag-${i}`" class="cursor-pointer">
                    <figcaption class="block text-center">{{ i }}</figcaption>
                    <div :class="`!bg-bottom fi fi-${i} !h-[51px] !w-[68px]`"></div>
                </figure>
            </div>
        </ScrollableNav>
    </div>
</template>
