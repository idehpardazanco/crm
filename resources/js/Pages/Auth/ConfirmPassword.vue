<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthCard from '@/Components/Auth/AuthCard.vue'
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({
    password: '',
})

const submit = () => {
    form.post(
        route('password.confirm'),
        {
            onFinish: () => {
                form.reset('password')
            },
        }
    )
}
</script>

<template>
    <GuestLayout>
        <Head title="تأیید رمز عبور" />

        <AuthCard
            title="تأیید رمز عبور"
            description="برای ادامه عملیات، رمز عبور حساب خود را دوباره وارد کنید."
        >
            <form
                class="space-y-5"
                @submit.prevent="submit"
            >
                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-bold text-slate-700"
                    >
                        رمز عبور
                    </label>

                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autofocus
                        autocomplete="current-password"
                        class="w-full"
                        placeholder="رمز عبور فعلی"
                    >

                    <p
                        v-if="form.errors.password"
                        class="mt-2 text-xs font-bold text-red-600"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="min-h-12 w-full rounded-xl bg-blue-600 px-5 text-sm font-extrabold text-white shadow-lg shadow-blue-100 transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'در حال بررسی...'
                            : 'تأیید و ادامه'
                    }}
                </button>
            </form>
        </AuthCard>
    </GuestLayout>
</template>