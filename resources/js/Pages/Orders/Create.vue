<script setup>
import { useForm } from '@inertiajs/vue3'
import OrderForm from '../../Components/Orders/OrderForm.vue'

const props = defineProps({
    contacts: {
        type: Array,
        default: () => [],
    },

    orderStatuses: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    contact_id: '',
    product_name: '',
    amount: '',
    status: 'new',
    description: '',
})

const submit = () => {
    form.post('/orders', {
        preserveScroll: true,
    })
}
</script>

<template>
    <OrderForm
        :form="form"
        :contacts="contacts"
        :order-statuses="orderStatuses"
        mode="create"
        @submit="submit"
    />
</template>