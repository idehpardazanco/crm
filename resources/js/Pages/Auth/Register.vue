<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthCard from '@/Components/Auth/AuthCard.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post(
        route('register'),
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
        <Head title="ایجاد حساب" />

        <AuthCard
            title="ایجاد حساب کاربری"
            description="اطلاعات حساب جدید را وارد کنید."
            icon="user"
        >
            <form
                class="space-y-4"
                @submit.prevent="submit"
            >
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-bold text-slate-700"
                    >
                        نام و نام خانوادگی
                    </label>

                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full"
                        placeholder="نام کامل"
                    >

                    <p
                        v-if="form.errors.name"
                        class="mt-2 text-xs font-bold text-red-600"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

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
                        placeholder="user@example.com"
                    >

                    <p
                        v-if="form.errors.email"
                        class="mt-2 text-xs font-bold text-red-600"
                    >
                        {{ form.errors.email }}
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
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
                            تکرار رمز عبور
                        </label>

                        <input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            class="w-full"
                            placeholder="تکرار رمز عبور"
                        >

                        <p
                            v-if="form.errors.password_confirmation"
                            class="mt-2 text-xs font-bold text-red-600"
                        >
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="min-h-12 w-full rounded-xl bg-blue-600 px-5 text-sm font-extrabold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'در حال ثبت...'
                            : 'ایجاد حساب'
                    }}
                </button>

                <Link
                    :href="route('login')"
                    class="flex min-h-11 items-center justify-center text-sm font-bold text-slate-500 transition hover:text-blue-600"
                >
                    قبلاً حساب دارید؟ ورود
                </Link>
            </form>
        </AuthCard>
    </GuestLayout>
</template>