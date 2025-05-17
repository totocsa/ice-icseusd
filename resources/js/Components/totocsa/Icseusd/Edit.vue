<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import Form from './Form.vue'

const success = ref(false);

const props = defineProps({
    config: Object,
    formData: Object,
})

const form = useForm({ ...{ _method: 'PUT' }, ...props.formData });

const formSubmit = () => {
    form.post(route(`${props.config.routeController}.update`, { [props.config.routeParameterName]: props.config.item.id }), {
        preserveState: true,
        preserveUrl: true,
        onSuccess: (data) => {
            router.visit(route(`${data.props.routeController}.show`, { [data.props.routeParameterName]: data.props.item.id }))

            success.value = true;
            setTimeout(() => {
                success.value = false
            }, 2000)
        },
        onError: (data) => {
            console.log(data)
        }
    })
}
</script>

<template>
    <Form :config="props.config" :form="form" :formSubmit="formSubmit" :success="success" />
</template>
