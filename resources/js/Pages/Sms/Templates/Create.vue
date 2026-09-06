<script setup>
import { useForm } from '@inertiajs/vue3'
import SmsTemplateForm from '../../../Components/Sms/SmsTemplateForm.vue'

const form = useForm({
    title: '',
    body: '',
    type: '',
    status: 'active',
})

const submit = () => {
    form.post('/sms/templates', {
        preserveScroll: true,
    })
}
</script>

<template>
    <SmsTemplateForm
        :form="form"
        mode="create"
        @submit="submit"
    />
</template>