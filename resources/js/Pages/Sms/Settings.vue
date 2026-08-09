<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    settings: Object,
})

const form = useForm({
    sms_from:
        props.settings.sms_from ?? '',

    sms_username:
        props.settings.sms_username ?? '',

    sms_password: '',

    demo_link:
        props.settings.demo_link ?? '',

    product_name:
        props.settings.product_name ?? '',

    order_link:
        props.settings.order_link ?? '',
})

const submit = () => {
    form.post('/sms/settings', {
        preserveScroll: true,

        onSuccess: () => {
            form.sms_password = ''
        },
    })
}
</script>

<template>
    <div class="p-6">

        <h1 class="text-xl font-bold mb-6">
            تنظیمات پیامک
        </h1>

        <form
            @submit.prevent="submit"
            class="max-w-2xl"
        >

            <div class="mb-4">

                <label class="block mb-2">
                    شماره فرستنده
                </label>

                <input
                    v-model="form.sms_from"
                    type="text"
                    class="border p-2 w-full"
                >

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    نام کاربری پنل پیامک
                </label>

                <input
                    v-model="form.sms_username"
                    type="text"
                    class="border p-2 w-full"
                >

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    رمز عبور پنل پیامک
                </label>

                <input
                    v-model="form.sms_password"
                    type="password"
                    class="border p-2 w-full"
                    placeholder="برای عدم تغییر خالی بگذارید"
                >

            </div>


            <hr class="my-6">


            <div class="mb-4">

                <label class="block mb-2">
                    لینک دمو
                </label>

                <input
                    v-model="form.demo_link"
                    type="text"
                    class="border p-2 w-full"
                >

                <div class="text-sm text-gray-500 mt-1">
                    {{ '{{demo_link}}' }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    نام محصول
                </label>

                <input
                    v-model="form.product_name"
                    type="text"
                    class="border p-2 w-full"
                >

                <div class="text-sm text-gray-500 mt-1">
                    {{ '{{product_name}}' }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    لینک سفارش
                </label>

                <input
                    v-model="form.order_link"
                    type="text"
                    class="border p-2 w-full"
                >

                <div class="text-sm text-gray-500 mt-1">
                    {{ '{{order_link}}' }}
                </div>

            </div>


            <button
                type="submit"
                :disabled="form.processing"
                class="bg-blue-600 text-white px-5 py-2 rounded"
            >
                ذخیره تنظیمات
            </button>

        </form>

    </div>
</template>