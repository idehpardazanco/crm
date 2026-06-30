<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    business: Object
})

const form = useForm({
    business_name: props.business.business_name,
    contact_name: props.business.contact_name,
    mobile: props.business.mobile,
    phone: props.business.phone,
    city: props.business.city,
    category: props.business.category,
    source: props.business.source,
    status: props.business.status,
    description: props.business.description
})

const submit = () => {
    form.put(route('businesses.update', props.business.id))
}
</script>

<template>
    <Head title="ویرایش مخاطب" />

    <AuthenticatedLayout>
        <div class="p-6 max-w-2xl">

            <h1 class="text-xl font-bold mb-4">ویرایش مخاطب</h1>

            <form @submit.prevent="submit" class="space-y-3">

                <input v-model="form.business_name" class="border p-2 w-full" />
                <input v-model="form.contact_name" class="border p-2 w-full" />
                <input v-model="form.mobile" class="border p-2 w-full" />
                <input v-model="form.city" class="border p-2 w-full" />

                <textarea v-model="form.description" class="border p-2 w-full"></textarea>

                <button class="bg-blue-600 text-white px-4 py-2 rounded">
                    بروزرسانی
                </button>

            </form>

        </div>
    </AuthenticatedLayout>
</template>