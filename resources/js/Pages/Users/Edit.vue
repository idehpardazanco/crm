<script setup>
import { useForm } from '@inertiajs/vue3'
import UserForm from '../../Components/Users/UserForm.vue'

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
})

const form = useForm({
    name: props.user.name ?? '',
    mobile: props.user.mobile ?? '',
    email: props.user.email ?? '',
    password: '',
    role: 'employee',
    status: props.user.status ?? 'active',
})

const submit = () => {
    form.put(`/users/${props.user.id}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <UserForm
        :form="form"
        mode="edit"
        @submit="submit"
    />
</template>