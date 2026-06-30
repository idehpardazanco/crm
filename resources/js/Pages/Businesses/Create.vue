<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
    business_name: '',
    contact_name: '',
    mobile: '',
    phone: '',
    city: '',
    category: '',
    source: '',
    status: 'new',
    description: ''
})

const submit = () => {
    form.post(route('businesses.store'))
}
</script>

<template>
    <Head title="ایجاد مخاطب" />

    <AuthenticatedLayout>
        <div class="p-6 max-w-2xl">

            <h1 class="text-xl font-bold mb-4">ایجاد مخاطب</h1>

            <form @submit.prevent="submit" class="space-y-3">

                <input v-model="form.business_name" placeholder="نام کسب‌وکار" class="border p-2 w-full" />
                <input v-model="form.contact_name" placeholder="نام شخص" class="border p-2 w-full" />
                <input v-model="form.mobile" placeholder="موبایل" class="border p-2 w-full" />
                <input v-model="form.city" placeholder="شهر" class="border p-2 w-full" />

                <textarea v-model="form.description" placeholder="توضیحات" class="border p-2 w-full"></textarea>

                <button class="bg-green-600 text-white px-4 py-2 rounded">
                    ذخیره
                </button>

            </form>

        </div>
    </AuthenticatedLayout>
</template>