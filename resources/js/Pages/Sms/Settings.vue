<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    settings: Object,
})

const form = useForm({
    sms_from: props.settings.sms_from ?? '',
    sms_username: props.settings.sms_username ?? '',
    sms_password: '',
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

        <h1 class="text-xl font-bold mb-5">
            تنظیمات پیامک
        </h1>

        <form @submit.prevent="submit">

            <div class="mb-4">

                <label class="block mb-2">
                    شماره فرستنده
                </label>

                <input
                    v-model="form.sms_from"
                    type="text"
                    class="border p-2 w-full"
                >

                <div
                    v-if="form.errors.sms_from"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.sms_from }}
                </div>

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    نام کاربری
                </label>

                <input
                    v-model="form.sms_username"
                    type="text"
                    class="border p-2 w-full"
                >

                <div
                    v-if="form.errors.sms_username"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.sms_username }}
                </div>

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    رمز عبور
                </label>

                <input
                    v-model="form.sms_password"
                    type="password"
                    class="border p-2 w-full"
                    placeholder="برای عدم تغییر خالی بگذارید"
                >

                <div
                    v-if="form.errors.sms_password"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.sms_password }}
                </div>

            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-blue-600 text-white px-5 py-2 rounded"
            >
                ذخیره
            </button>

        </form>

    </div>
</template>