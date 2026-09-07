<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthCard from '@/Components/Auth/AuthCard.vue'
import { Head, useForm } from '@inertiajs/vue3'

defineProps({
    status: String,
})

const form = useForm({
    email: '',
})

const submit = () => {
    form.post(route('password.email'))
}
</script>

<template>
    <GuestLayout>
        <Head title="بازیابی رمز عبور" />

        <AuthCard
            title="بازیابی رمز عبور"
            description="ایمیل حساب خود را وارد کنید تا لینک انتخاب رمز جدید برایتان ارسال شود."
            icon="mail"
            back-to-login
        >
            <div
                v-if="status"
                class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-sm leading-7 text-emerald-700"
            >
                {{ status }}
            </div>

            <form
                class="space-y-5"
                @submit.prevent="submit"
            >
                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-bold text-slate-700"
                    >
                        ایمیل
                    </label>

                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        autofocus
                        autocomplete="username"
                        dir="ltr"
                        class="w-full text-left"
                        placeholder="user@example.com"
                    >

                    <p
                        v-if="form.errors.email"
                        class="mt-2 text-xs font-bold text-red-600"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="min-h-12 w-full rounded-xl bg-blue-600 px-5 text-sm font-extrabold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'در حال ارسال...'
                            : 'ارسال لینک بازیابی'
                    }}
                </button>
            </form>
        </AuthCard>
    </GuestLayout>
</template>