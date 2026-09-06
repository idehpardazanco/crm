<script setup>
import { useForm } from '@inertiajs/vue3'
import UserForm from '../../Components/Users/UserForm.vue'

const form = useForm({
    name: '',
    mobile: '',
    email: '',
    password: '',
    role: 'employee',
    status: 'active',
})

const submit = () => {
    form.post('/users', {
        preserveScroll: true,
    })
}
</script>

<template>
    <UserForm
        :form="form"
        mode="create"
        @submit="submit"
    />
</template>