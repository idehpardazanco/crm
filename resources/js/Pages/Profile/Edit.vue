<script setup>
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import { Head } from '@inertiajs/vue3'

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
})
</script>

<template>
    <Head title="پروفایل کاربری" />

    <div
        class="mx-auto w-full max-w-[1200px] pb-8"
        dir="rtl"
    >
        <section
            class="mb-5 rounded-[20px] border border-slate-200 bg-gradient-to-bl from-white to-blue-50/40 p-5 shadow-sm sm:p-6"
        >
            <div class="flex items-center gap-4">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-200"
                >
                    <svg
                        class="h-7 w-7"
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
                </div>

                <div>
                    <div class="text-xs font-extrabold text-blue-600">
                        تنظیمات حساب
                    </div>

                    <h1 class="mt-1 text-2xl font-black text-slate-900">
                        پروفایل کاربری
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        اطلاعات حساب و تنظیمات امنیتی خود را مدیریت کنید.
                    </p>
                </div>
            </div>
        </section>

        <div class="grid gap-5">
            <section
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7"
            >
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </section>

            <section
                class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7"
            >
                <UpdatePasswordForm />
            </section>

            <section
                class="rounded-2xl border border-red-100 bg-white p-5 shadow-sm sm:p-7"
            >
                <DeleteUserForm />
            </section>
        </div>
    </div>
</template>