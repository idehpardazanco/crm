<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({}),
    },
})

const form = useForm({
    sms_from: props.settings?.sms_from ?? '',
    sms_username: props.settings?.sms_username ?? '',
    sms_password: '',
    demo_link: props.settings?.demo_link ?? '',
    product_name: props.settings?.product_name ?? '',
    order_link: props.settings?.order_link ?? '',
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
        <h1 class="mb-6 text-xl font-bold">
            تنظیمات پیامک
        </h1>

        <form
            class="max-w-2xl"
            @submit.prevent="submit"
        >
            <div class="mb-4">
                <label class="mb-2 block">
                    شماره فرستنده
                </label>

                <input
                    v-model="form.sms_from"
                    type="text"
                    class="w-full border p-2"
                >

                <div
                    v-if="form.errors.sms_from"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.sms_from }}
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-2 block">
                    نام کاربری پنل پیامک
                </label>

                <input
                    v-model="form.sms_username"
                    type="text"
                    class="w-full border p-2"
                >

                <div
                    v-if="form.errors.sms_username"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.sms_username }}
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-2 block">
                    رمز عبور پنل پیامک
                </label>

                <input
                    v-model="form.sms_password"
                    type="password"
                    class="w-full border p-2"
                    placeholder="برای عدم تغییر خالی بگذارید"
                >

                <div
                    v-if="form.errors.sms_password"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.sms_password }}
                </div>
            </div>

            <hr class="my-6">

            <div class="mb-4">
                <label class="mb-2 block">
                    لینک دمو
                </label>

                <input
                    v-model="form.demo_link"
                    type="text"
                    class="w-full border p-2"
                >

                <div
                    v-pre
                    class="mt-1 text-sm text-gray-500"
                >
                    {{demo_link}}
                </div>

                <div
                    v-if="form.errors.demo_link"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.demo_link }}
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-2 block">
                    نام محصول
                </label>

                <input
                    v-model="form.product_name"
                    type="text"
                    class="w-full border p-2"
                >

                <div
                    v-pre
                    class="mt-1 text-sm text-gray-500"
                >
                    {{product_name}}
                </div>

                <div
                    v-if="form.errors.product_name"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.product_name }}
                </div>
            </div>

            <div class="mb-4">
                <label class="mb-2 block">
                    لینک سفارش
                </label>

                <input
                    v-model="form.order_link"
                    type="text"
                    class="w-full border p-2"
                >

                <div
                    v-pre
                    class="mt-1 text-sm text-gray-500"
                >
                    {{order_link}}
                </div>

                <div
                    v-if="form.errors.order_link"
                    class="mt-1 text-sm text-red-600"
                >
                    {{ form.errors.order_link }}
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="rounded bg-blue-600 px-5 py-2 text-white disabled:opacity-50"
            >
                {{ form.processing ? 'در حال ذخیره...' : 'ذخیره تنظیمات' }}
            </button>
        </form>
    </div>
</template>