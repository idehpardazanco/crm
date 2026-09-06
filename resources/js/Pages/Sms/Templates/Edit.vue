<script setup>
import { useForm } from '@inertiajs/vue3'
import SmsTemplateForm from '../../../Components/Sms/SmsTemplateForm.vue'

const props = defineProps({
    template: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    title: props.template.title ?? '',
    body: props.template.body ?? '',
    type: props.template.type ?? '',
    status: props.template.status ?? 'active',
})

const submit = () => {
    form.put(
        `/sms/templates/${props.template.id}`,
        {
            preserveScroll: true,
        }
    )
}
</script>

<template>
    <SmsTemplateForm
        :form="form"
        mode="edit"
        @submit="submit"
    />
</template>