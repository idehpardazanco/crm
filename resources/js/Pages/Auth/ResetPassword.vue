<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthCard from '@/Components/Auth/AuthCard.vue'
import { Head, useForm } from '@inertiajs/vue3'

const props = defineProps({
    email: {
        type: String,
        required: true,
    },

    token: {
        type: String,
        required: true,
    },
})

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(
        route('password.store'),
        {
            onFinish: () => {
                form.reset(
                    'password',
                    'password_confirmation'
                )
            },
        }
    )
}
</script>

<template>
    <GuestLayout>
        <Head title="انتخاب رمز جدید" />

        <AuthCard
            title="انتخاب رمز جدید"
            description="برای حساب خود یک رمز عبور امن و جدید تعیین کنید."
        >
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
                        autocomplete="username"
                        dir="ltr"
                        class="w-full text-left"
                    >

                    <p
                        v-if="form.errors.email"
                        class="mt-2 text-xs font-bold text-red-600"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-bold text-slate-700"
                    >
                        رمز عبور جدید
                    </label>

                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full"
                        placeholder="حداقل ۸ کاراکتر"
                    >

                    <p
                        v-if="form.errors.password"
                        class="mt-2 text-xs font-bold text-red-600"
                    >
                        {{ form.errors.password }}
                    </p>
                </div>

                <div>
                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-bold text-slate-700"
                    >
                        تکرار رمز عبور جدید
                    </label>

                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full"
                        placeholder="رمز عبور را دوباره وارد کنید"
                    >

                    <p
                        v-if="form.errors.password_confirmation"
                        class="mt-2 text-xs font-bold text-red-600"
                    >
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="min-h-12 w-full rounded-xl bg-blue-600 px-5 text-sm font-extrabold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'در حال ذخیره...'
                            : 'ذخیره رمز جدید'
                    }}
                </button>
            </form>
        </AuthCard>
    </GuestLayout>
</template>