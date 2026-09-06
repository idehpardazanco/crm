<script setup>
import { useForm } from '@inertiajs/vue3'
import ContactForm from '../../Components/Contacts/ContactForm.vue'

const props = defineProps({
    contact: Object,
    users: Array,
    contactStatuses: Array,
    isAdmin: Boolean,
})

const form = useForm({
    business_name:
        props.contact.business_name ?? '',

    name:
        props.contact.name ?? '',

    mobile:
        props.contact.mobile ?? '',

    phone:
        props.contact.phone ?? '',

    email:
        props.contact.email ?? '',

    city:
        props.contact.city ?? '',

    category:
        props.contact.category ?? '',

    source:
        props.contact.source ?? '',

    status:
        props.contact.status ?? 'new',

    assigned_user_id:
        props.contact.assigned_user_id ?? '',

    address:
        props.contact.address ?? '',

    description:
        props.contact.description ?? '',
})

const submit = () => {
    form.put(
        `/contacts/${props.contact.id}`
    )
}
</script>


<template>
    <ContactForm
        :form="form"
        :users="users"
        :contact-statuses="contactStatuses"
        :is-admin="isAdmin"
        mode="edit"
        :back-href="`/contacts/${contact.id}`"
        @submit="submit"
    />
</template>
