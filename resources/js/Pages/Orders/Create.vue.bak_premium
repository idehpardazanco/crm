<script setup>
import {
    Link,
    useForm,
} from '@inertiajs/vue3'

const props = defineProps({
    contacts: Array,
    orderStatuses: Array,
})

const form = useForm({
    contact_id: '',
    product_name: '',
    amount: '',
    status: 'new',
    description: '',
})

const submit = () => {
    form.post('/orders')
}
</script>

<template>
    <div class="p-6">

        <h1 class="text-xl font-bold mb-6">
            ثبت سفارش
        </h1>


        <form
            @submit.prevent="submit"
            class="max-w-2xl"
        >

            <div class="mb-4">

                <label class="block mb-2">
                    مخاطب
                </label>

                <select
                    v-model="form.contact_id"
                    class="border p-2 rounded w-full"
                >

                    <option value="">
                        انتخاب مخاطب
                    </option>

                    <option
                        v-for="contact in contacts"
                        :key="contact.id"
                        :value="contact.id"
                    >
                        {{ contact.name }}
                        -
                        {{ contact.business_name ?? 'بدون کسب‌وکار' }}
                        -
                        {{ contact.mobile }}
                    </option>

                </select>

                <div
                    v-if="form.errors.contact_id"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.contact_id }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    محصول
                </label>

                <input
                    v-model="form.product_name"
                    type="text"
                    class="border p-2 rounded w-full"
                >

                <div
                    v-if="form.errors.product_name"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.product_name }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    مبلغ
                </label>

                <input
                    v-model="form.amount"
                    type="number"
                    min="0"
                    step="0.01"
                    class="border p-2 rounded w-full"
                >

                <div
                    v-if="form.errors.amount"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.amount }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    وضعیت سفارش
                </label>

                <select
                    v-model="form.status"
                    class="border p-2 rounded w-full"
                >

                    <option
                        v-for="status in orderStatuses"
                        :key="status.value"
                        :value="status.value"
                    >
                        {{ status.label }}
                    </option>

                </select>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    توضیحات
                </label>

                <textarea
                    v-model="form.description"
                    rows="5"
                    class="border p-2 rounded w-full"
                ></textarea>

            </div>


            <div class="flex gap-3">

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-green-600 text-white px-5 py-2 rounded"
                >
                    ثبت سفارش
                </button>

                <Link
                    href="/orders"
                    class="border px-5 py-2 rounded"
                >
                    بازگشت
                </Link>

            </div>

        </form>

    </div>
</template>