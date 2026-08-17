<script setup>
import { ref } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
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
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password')
        },
    })
}
</script>

<template>
    <GuestLayout>
        <Head title="ورود به CRM" />

        <div
            class="grid w-full max-w-5xl overflow-hidden rounded-[28px] border border-white/10 bg-white shadow-2xl shadow-black/30 lg:grid-cols-2"
        >
            <!-- Right / Login -->
            <div class="order-2 bg-white p-6 sm:p-10 lg:order-1 lg:p-12">
                <!-- Mobile Logo -->
                <div class="mb-8 flex items-center gap-3 lg:hidden">
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/25"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            class="h-6 w-6"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 19.5V9.75A2.75 2.75 0 0 1 6.75 7h10.5A2.75 2.75 0 0 1 20 9.75v9.75M8 7V5.75A1.75 1.75 0 0 1 9.75 4h4.5A1.75 1.75 0 0 1 16 5.75V7M3 19.5h18M8 11h.01M12 11h.01M16 11h.01M8 15h.01M12 15h.01M16 15h.01"
                            />
                        </svg>
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
                        شماره موبایل و رمز عبور خود را برای ورود به سامانه وارد کنید.
                    </p>
                </div>

                <!-- Status -->
                <div
                    v-if="status"
                    class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
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
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="h-5 w-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M8.25 3.75h7.5A2.25 2.25 0 0 1 18 6v12a2.25 2.25 0 0 1-2.25 2.25h-7.5A2.25 2.25 0 0 1 6 18V6a2.25 2.25 0 0 1 2.25-2.25Z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        d="M10 17.25h4"
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
                            />
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
                        <label
                            for="password"
                            class="mb-2 block text-sm font-semibold text-slate-700"
                        >
                            رمز عبور
                        </label>

                        <div class="relative">
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="h-5 w-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <rect
                                        x="5"
                                        y="10"
                                        width="14"
                                        height="10"
                                        rx="2"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        d="M8 10V7.5a4 4 0 0 1 8 0V10"
                                    />
                                </svg>
                            </div>

                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                autocomplete="current-password"
                                required
                                dir="ltr"
                                placeholder="••••••••"
                                class="block w-full rounded-xl border border-slate-200 bg-slate-50 py-3.5 pl-12 pr-12 text-left text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                            />

                            <button
                                type="button"
                                class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition hover:text-slate-700"
                                @click="showPassword = !showPassword"
                            >
                                <!-- Eye -->
                                <svg
                                    v-if="!showPassword"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="h-5 w-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"
                                    />
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="2.5"
                                    />
                                </svg>

                                <!-- Eye off -->
                                <svg
                                    v-else
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    class="h-5 w-5"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        stroke-linecap="round"
                                        d="M3 3l18 18"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M10.6 6.2A10.7 10.7 0 0 1 12 6c6 0 9.5 6 9.5 6a15 15 0 0 1-2.2 2.8M6.2 6.2C3.8 8 2.5 12 2.5 12s3.5 6 9.5 6a9.7 9.7 0 0 0 3-.45"
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
                        />

                        <span
                            class="text-sm text-slate-600"
                        >
                            مرا به خاطر بسپار
                        </span>
                    </label>

                    <!-- Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-600/25 focus:outline-none focus:ring-4 focus:ring-blue-500/20 disabled:cursor-not-allowed disabled:opacity-60"
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
                                d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4Z"
                            />
                        </svg>

                        <span>
                            {{ form.processing ? 'در حال ورود...' : 'ورود به سیستم' }}
                        </span>

                        <svg
                            v-if="!form.processing"
                            viewBox="0 0 24 24"
                            fill="none"
                            class="h-5 w-5 rotate-180"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M5 12h14m-6-6 6 6-6 6"
                            />
                        </svg>
                    </button>
                </form>

                <div
                    class="mt-8 border-t border-slate-100 pt-6 text-center"
                >
                    <p class="text-xs text-slate-400">
                        دسترسی به این سامانه فقط برای کاربران مجاز شرکت امکان‌پذیر است.
                    </p>
                </div>
            </div>

            <!-- Left / Brand -->
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
                        class="mb-8 flex h-14 w-14 items-center justify-center rounded-2xl border border-white/20 bg-white/10 backdrop-blur"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            class="h-8 w-8"
                            stroke="currentColor"
                            stroke-width="1.6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M4 19.5V9.75A2.75 2.75 0 0 1 6.75 7h10.5A2.75 2.75 0 0 1 20 9.75v9.75M8 7V5.75A1.75 1.75 0 0 1 9.75 4h4.5A1.75 1.75 0 0 1 16 5.75V7M3 19.5h18"
                            />

                            <path
                                stroke-linecap="round"
                                d="M8 11h.01M12 11h.01M16 11h.01M8 15h.01M12 15h.01M16 15h.01"
                            />
                        </svg>
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
                        class="mt-5 max-w-md text-sm leading-7 text-blue-100/90"
                    >
                        مدیریت مخاطبین، تماس‌ها، پیگیری‌ها، پیامک‌ها و سفارش‌ها در یک محیط یکپارچه.
                    </p>
                </div>

                <div class="relative">
                    <div
                        class="mb-6 grid grid-cols-3 gap-3"
                    >
                        <div
                            class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="mb-3 h-6 w-6 text-blue-100"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M8 7V5a4 4 0 0 1 8 0v2M5 9h14v10H5V9Z"
                                />
                            </svg>

                            <span class="text-xs text-blue-50">
                                مدیریت امن
                            </span>
                        </div>

                        <div
                            class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="mb-3 h-6 w-6 text-blue-100"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 5h14v10H8l-3 3V5Z"
                                />
                            </svg>

                            <span class="text-xs text-blue-50">
                                پیامک سریع
                            </span>
                        </div>

                        <div
                            class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur-sm"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                class="mb-3 h-6 w-6 text-blue-100"
                                stroke="currentColor"
                                stroke-width="1.7"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M5 19V9m7 10V5m7 14v-7"
                                />
                            </svg>

                            <span class="text-xs text-blue-50">
                                گزارش دقیق
                            </span>
                        </div>
                    </div>

                    <p class="text-xs text-blue-200/80">
                        CRM Idehpardazan
                    </p>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>