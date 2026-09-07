<script setup>
import { computed } from 'vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import AuthCard from '@/Components/Auth/AuthCard.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    status: String,
})

const form = useForm({})

const verificationLinkSent = computed(() => {
    return props.status === 'verification-link-sent'
})

const submit = () => {
    form.post(route('verification.send'))
}
</script>

<template>
    <GuestLayout>
        <Head title="تأیید ایمیل" />

        <AuthCard
            title="تأیید ایمیل"
            description="برای فعال‌سازی حساب، لینک ارسال‌شده به ایمیل خود را باز کنید."
            icon="mail"
        >
            <div
                v-if="verificationLinkSent"
                class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-7 text-emerald-700"
            >
                لینک تأیید جدید برای ایمیل شما ارسال شد.
            </div>

            <div
                class="mb-5 rounded-xl border border-blue-100 bg-blue-50/70 p-4"
            >
                <div class="flex items-start gap-3">
                    <div
                        class="mt-1 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600"
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 8V12M12 16H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <p class="text-xs leading-7 text-blue-800">
                        اگر ایمیل را دریافت نکرده‌اید، پوشه Spam را بررسی
                        کنید یا لینک جدیدی درخواست دهید.
                    </p>
                </div>
            </div>

            <form
                class="space-y-3"
                @submit.prevent="submit"
            >
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="min-h-12 w-full rounded-xl bg-blue-600 px-5 text-sm font-extrabold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'در حال ارسال...'
                            : 'ارسال مجدد لینک تأیید'
                    }}
                </button>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="min-h-11 w-full rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-500 transition hover:bg-slate-50 hover:text-red-600"
                >
                    خروج از حساب
                </Link>
            </form>
        </AuthCard>
    </GuestLayout>
</template>