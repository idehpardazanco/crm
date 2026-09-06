<script setup>
import { useForm } from '@inertiajs/vue3'
import ContactForm from '../../Components/Contacts/ContactForm.vue'

defineProps({
    users: Array,
    contactStatuses: Array,
    isAdmin: Boolean,
})

const form = useForm({
    business_name: '',
    name: '',
    mobile: '',
    phone: '',
    email: '',
    city: '',
    category: '',
    source: '',
    status: 'new',
    assigned_user_id: '',
    address: '',
    description: '',
})

const submit = () => {
    form.post('/contacts')
}
</script>


<template>
    <ContactForm
        :form="form"
        :users="users"
        :contact-statuses="contactStatuses"
        :is-admin="isAdmin"
        mode="create"
        back-href="/contacts"
        @submit="submit"
    />
</template>
