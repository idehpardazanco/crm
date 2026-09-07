<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    title: {
        type: String,
        required: true,
    },

    description: {
        type: String,
        default: '',
    },

    icon: {
        type: String,
        default: 'lock',
    },

    backToLogin: {
        type: Boolean,
        default: false,
    },
})
</script>

<template>
    <div
        class="w-full max-w-md"
        dir="rtl"
    >
        <div
            class="mb-6 flex items-center justify-center gap-3 text-white"
        >
            <div
                class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 font-black shadow-lg shadow-blue-950/30"
            >
                CRM
            </div>

            <div>
                <strong class="block">
                    CRM ایده‌پردازان
                </strong>

                <span class="text-xs text-blue-100/60">
                    مدیریت ارتباط با مشتری
                </span>
            </div>
        </div>

        <section
            class="rounded-[24px] border border-white/10 bg-white p-6 shadow-2xl shadow-black/30 sm:p-8"
        >
            <div
                class="mb-6 flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-600"
            >
                <svg
                    v-if="icon === 'mail'"
                    class="h-6 w-6"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <path
                        d="M3 5H21V19H3V5ZM3 7L12 13L21 7"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>

                <svg
                    v-else-if="icon === 'user'"
                    class="h-6 w-6"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <path
                        d="M20 21V19C20 16.8 18.2 15 16 15H8C5.8 15 4 16.8 4 19V21M12 11C14.2 11 16 9.2 16 7C16 4.8 14.2 3 12 3C9.8 3 8 4.8 8 7C8 9.2 9.8 11 12 11Z"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                    />
                </svg>

                <svg
                    v-else
                    class="h-6 w-6"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <path
                        d="M7 10V8C7 5.2 9.2 3 12 3C14.8 3 17 5.2 17 8V10M5 10H19V21H5V10Z"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
            </div>

            <h1 class="text-2xl font-black text-slate-900">
                {{ title }}
            </h1>

            <p
                v-if="description"
                class="mt-3 text-sm leading-7 text-slate-500"
            >
                {{ description }}
            </p>

            <div class="mt-7">
                <slot />
            </div>

            <Link
                v-if="backToLogin"
                :href="route('login')"
                class="mt-6 flex min-h-11 items-center justify-center rounded-xl border border-slate-200 text-sm font-bold text-slate-500 transition hover:bg-slate-50 hover:text-blue-600"
            >
                بازگشت به صفحه ورود
            </Link>
        </section>
    </div>
</template>