<script setup>
import { useForm } from '@inertiajs/vue3'
import OrderForm from '../../Components/Orders/OrderForm.vue'

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },

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
    contact_id: props.order.contact_id ?? '',
    product_name: props.order.product_name ?? '',
    amount: props.order.amount ?? '',
    status: props.order.status ?? 'new',
    description: props.order.description ?? '',
})

const submit = () => {
    form.put(`/orders/${props.order.id}`, {
        preserveScroll: true,
    })
}
</script>

<template>
    <OrderForm
        :form="form"
        :contacts="contacts"
        :order-statuses="orderStatuses"
        mode="edit"
        @submit="submit"
    />
</template>