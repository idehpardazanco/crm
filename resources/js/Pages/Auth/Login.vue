<script setup>
import { ref } from 'vue'
import {
    Head,
    Link,
    useForm,
} from '@inertiajs/vue3'

import GuestLayout from '@/Layouts/GuestLayout.vue'

defineProps({
    status: String,
})

const showPassword = ref(false)

const form = useForm({
    mobile: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(
        route('login'),
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
        <Head title="ورود به CRM" />

        <div
            class="grid w-full max-w-5xl overflow-hidden rounded-[28px] border border-white/10 bg-white shadow-2xl shadow-black/30 lg:grid-cols-2"
            dir="rtl"
        >
            <!-- Login form -->
            <div
                class="order-2 bg-white p-6 sm:p-10 lg:order-1 lg:p-12"
            >
                <!-- Mobile logo -->
                <div
                    class="mb-8 flex items-center gap-3 lg:hidden"
                >
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-xs font-black text-white shadow-lg shadow-blue-600/25"
                    >
                        CRM
                    </div>

                    <div>
                        <div class="font-bold text-slate-900">
                            CRM ایده‌پردازان
                        </div>

                        <div class="text-xs text-slate-500">
                            سامانه مدیریت ارتباط با مشتریان
                        </div>
                    </div>
                </div>

                <!-- Heading -->
                <div class="mb-8">
                    <div
                        class="mb-3 inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-blue-700"
                    >
                        <span
                            class="h-2 w-2 rounded-full bg-blue-600"
                        ></span>

                        پنل داخلی شرکت
                    </div>

                    <h1
                        class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl"
                    >
                        ورود به حساب کاربری
                    </h1>

                    <p
                        class="mt-3 text-sm leading-7 text-slate-500"
                    >
                        شماره موبایل و رمز عبور خود را برای ورود به سامانه
                        وارد کنید.
                    </p>
                </div>

                <!-- Status -->
                <div
                    v-if="status"
                    class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium leading-7 text-emerald-700"
                >
                    {{ status }}
                </div>

                <form
                    class="space-y-5"
                    @submit.prevent="submit"
                >
                    <!-- Mobile -->
                    <div>
                        <label
                            for="mobile"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            شماره موبایل
                        </label>

                        <div class="relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400"
                            >
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M8.25 3.75H15.75C16.9926 3.75 18 4.75736 18 6V18C18 19.2426 16.9926 20.25 15.75 20.25H8.25C7.00736 20.25 6 19.2426 6 18V6C6 4.75736 7.00736 3.75 8.25 3.75Z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />

                                    <path
                                        d="M10 17.25H14"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </div>

                            <input
                                id="mobile"
                                v-model="form.mobile"
                                type="tel"
                                inputmode="numeric"
                                autocomplete="username"
                                autofocus
                                required
                                dir="ltr"
                                placeholder="09121234567"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-4 pr-12 text-left text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >
                        </div>

                        <p
                            v-if="form.errors.mobile"
                            class="mt-2 text-xs font-medium text-red-600"
                        >
                            {{ form.errors.mobile }}
                        </p>
                    </div>

                    <!-- Password -->
                    <div>
                        <div
                            class="mb-2 flex items-center justify-between gap-3"
                        >
                            <label
                                for="password"
                                class="text-sm font-semibold text-slate-700"
                            >
                                رمز عبور
                            </label>

                            <Link
                                :href="route('password.request')"
                                class="text-xs font-bold text-blue-600 transition hover:text-blue-800"
                            >
                                رمز عبور را فراموش کرده‌اید؟
                            </Link>
                        </div>

                        <div class="relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400"
                            >
                                <svg
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="10"
                                        rx="2"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />

                                    <path
                                        d="M8 10V7.5C8 5.29086 9.79086 3.5 12 3.5C14.2091 3.5 16 5.29086 16 7.5V10"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />
                                </svg>
                            </div>

                            <input
                                id="password"
                                v-model="form.password"
                                :type="
                                    showPassword
                                        ? 'text'
                                        : 'password'
                                "
                                autocomplete="current-password"
                                required
                                dir="ltr"
                                placeholder="••••••••"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-12 text-left text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            >

                            <button
                                type="button"
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition hover:text-slate-700"
                                :aria-label="
                                    showPassword
                                        ? 'مخفی‌کردن رمز عبور'
                                        : 'نمایش رمز عبور'
                                "
                                @click="
                                    showPassword =
                                        !showPassword
                                "
                            >
                                <svg
                                    v-if="!showPassword"
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M2.5 12C2.5 12 6 6 12 6C18 6 21.5 12 21.5 12C21.5 12 18 18 12 18C6 18 2.5 12 2.5 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />

                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="2.5"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    />
                                </svg>

                                <svg
                                    v-else
                                    class="h-5 w-5"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M3 3L21 21"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />

                                    <path
                                        d="M10.6 6.2C11.056 6.06667 11.5227 6 12 6C18 6 21.5 12 21.5 12C20.9394 12.9478 20.2645 13.8232 19.49 14.61M6.2 6.2C3.8 8 2.5 12 2.5 12C2.5 12 6 18 12 18C13.02 18 14 17.82 14.9 17.5"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </button>
                        </div>

                        <p
                            v-if="form.errors.password"
                            class="mt-2 text-xs font-medium text-red-600"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember -->
                    <label
                        class="flex cursor-pointer items-center gap-3"
                    >
                        <input
                            v-model="form.remember"
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        >

                        <span class="text-sm text-slate-600">
                            مرا به خاطر بسپار
                        </span>
                    </label>

                    <!-- Submit -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex min-h-[52px] w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/25 focus:outline-none focus:ring-4 focus:ring-blue-500/20 disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <svg
                            v-if="form.processing"
                            class="h-5 w-5 animate-spin"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <circle
                                class="opacity-25"
                                cx="12"
                                cy="12"
                                r="10"
                                stroke="currentColor"
                                stroke-width="4"
                            />

                            <path
                                class="opacity-75"
                                fill="currentColor"
                                d="M4 12A8 8 0 0 1 12 4V8A4 4 0 0 0 8 12H4Z"
                            />
                        </svg>

                        <span>
                            {{
                                form.processing
                                    ? 'در حال ورود...'
                                    : 'ورود به سیستم'
                            }}
                        </span>

                        <svg
                            v-if="!form.processing"
                            class="h-5 w-5 rotate-180"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M5 12H19M13 6L19 12L13 18"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </button>
                </form>

                <div
                    class="mt-8 border-t border-slate-100 pt-6 text-center"
                >
                    <p class="text-xs leading-6 text-slate-400">
                        دسترسی به این سامانه فقط برای کاربران مجاز شرکت
                        امکان‌پذیر است.
                    </p>
                </div>
            </div>

            <!-- Brand panel -->
            <div
                class="relative order-1 hidden min-h-[620px] overflow-hidden bg-gradient-to-br from-blue-700 via-blue-600 to-indigo-700 p-12 text-white lg:flex lg:flex-col lg:justify-between"
            >
                <div
                    class="absolute -left-20 -top-20 h-64 w-64 rounded-full border border-white/10"
                ></div>

                <div
                    class="absolute -left-10 -top-10 h-44 w-44 rounded-full border border-white/10"
                ></div>

                <div
                    class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-white/5"
                ></div>

                <div class="relative">
                    <div
                        class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 text-sm font-black backdrop-blur"
                    >
                        CRM
                    </div>

                    <p
                        class="mb-3 text-sm font-medium text-blue-100"
                    >
                        سامانه یکپارچه مدیریت
                    </p>

                    <h2
                        class="max-w-md text-4xl font-black leading-[1.4]"
                    >
                        ارتباط با مشتریان،
                        ساده‌تر و منظم‌تر
                    </h2>

                    <p
                        class="mt-5 max-w-md text-sm leading-8 text-blue-100/90"
                    >
                        مدیریت مخاطبین، تماس‌ها، پیگیری‌ها، پیامک‌ها و
                        سفارش‌ها در یک محیط یکپارچه.
                    </p>
                </div>

                <div
                    class="relative grid grid-cols-2 gap-3"
                >
                    <div
                        class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur"
                    >
                        <div class="text-2xl font-black">
                            ۱۰۰٪
                        </div>

                        <div
                            class="mt-1 text-xs text-blue-100/70"
                        >
                            مدیریت یکپارچه
                        </div>
                    </div>

                    <div
                        class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur"
                    >
                        <div class="text-2xl font-black">
                            ۲۴/۷
                        </div>

                        <div
                            class="mt-1 text-xs text-blue-100/70"
                        >
                            دسترسی به اطلاعات
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>