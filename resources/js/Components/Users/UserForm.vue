<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    form: {
        type: Object,
        required: true,
    },

    mode: {
        type: String,
        default: 'create',
    },
})

const emit = defineEmits(['submit'])
</script>

<template>
    <div
        class="mx-auto w-full max-w-[1450px] pb-8"
        dir="rtl"
    >
        <section
            class="mb-5 flex flex-col gap-5 rounded-[20px] border border-slate-200 bg-gradient-to-bl from-white to-blue-50/40 p-5 shadow-sm md:flex-row md:items-center md:justify-between md:p-6"
        >
            <div class="flex items-center gap-4">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-200"
                >
                    <svg
                        class="h-7 w-7"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <div class="mb-1 text-xs font-extrabold text-blue-600">
                        مدیریت کاربران
                    </div>

                    <h1 class="m-0 text-2xl font-black text-slate-900">
                        {{
                            mode === 'edit'
                                ? 'ویرایش کاربر'
                                : 'ایجاد کاربر جدید'
                        }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        {{
                            mode === 'edit'
                                ? 'اطلاعات و وضعیت دسترسی کاربر را به‌روزرسانی کنید.'
                                : 'حساب کاربری جدید برای همکار مجموعه ایجاد کنید.'
                        }}
                    </p>
                </div>
            </div>

            <Link
                href="/users"
                class="flex min-h-11 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-bold text-slate-600 hover:border-blue-200 hover:text-blue-600"
            >
                <svg
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <path
                        d="M15 18L9 12L15 6"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>

                بازگشت
            </Link>
        </section>

        <form
            class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_330px]"
            @submit.prevent="emit('submit')"
        >
            <main class="grid gap-5">
                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-4"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-xs font-black text-blue-600"
                        >
                            01
                        </div>

                        <div>
                            <h2 class="m-0 text-base font-black text-slate-800">
                                مشخصات کاربر
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                اطلاعات هویتی و راه ارتباطی
                            </p>
                        </div>
                    </div>

                    <div class="grid gap-5 p-5 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-bold">
                                نام و نام خانوادگی
                                <span class="text-red-600">*</span>
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                autocomplete="off"
                                placeholder="نام کامل کاربر"
                                class="w-full"
                            >

                            <div
                                v-if="form.errors.name"
                                class="mt-2 text-xs font-bold text-red-600"
                            >
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold">
                                شماره موبایل
                                <span class="text-red-600">*</span>
                            </label>

                            <input
                                v-model="form.mobile"
                                type="text"
                                inputmode="numeric"
                                autocomplete="off"
                                placeholder="09123456789"
                                class="w-full text-left"
                                dir="ltr"
                            >

                            <div
                                v-if="form.errors.mobile"
                                class="mt-2 text-xs font-bold text-red-600"
                            >
                                {{ form.errors.mobile }}
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-bold">
                                ایمیل
                            </label>

                            <input
                                v-model="form.email"
                                type="email"
                                autocomplete="off"
                                placeholder="user@example.com"
                                class="w-full text-left"
                                dir="ltr"
                            >

                            <div
                                v-if="form.errors.email"
                                class="mt-2 text-xs font-bold text-red-600"
                            >
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-4"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-violet-50 text-xs font-black text-violet-600"
                        >
                            02
                        </div>

                        <div>
                            <h2 class="m-0 text-base font-black text-slate-800">
                                امنیت حساب
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                {{
                                    mode === 'edit'
                                        ? 'در صورت نیاز رمز عبور را تغییر دهید.'
                                        : 'یک رمز عبور امن تعیین کنید.'
                                }}
                            </p>
                        </div>
                    </div>

                    <div class="p-5">
                        <label class="mb-2 block text-sm font-bold">
                            {{
                                mode === 'edit'
                                    ? 'رمز عبور جدید'
                                    : 'رمز عبور'
                            }}

                            <span
                                v-if="mode !== 'edit'"
                                class="text-red-600"
                            >
                                *
                            </span>
                        </label>

                        <input
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            class="w-full"
                            :placeholder="
                                mode === 'edit'
                                    ? 'برای حفظ رمز فعلی خالی بگذارید'
                                    : 'حداقل ۸ کاراکتر'
                            "
                        >

                        <div
                            v-if="form.errors.password"
                            class="mt-2 text-xs font-bold text-red-600"
                        >
                            {{ form.errors.password }}
                        </div>

                        <div
                            v-else-if="mode === 'edit'"
                            class="mt-2 text-xs text-slate-400"
                        >
                            خالی ماندن این قسمت، رمز عبور فعلی را حفظ می‌کند.
                        </div>
                    </div>
                </section>

                <section
                    class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex items-center gap-3 border-b border-slate-100 bg-slate-50/70 px-5 py-4"
                    >
                        <div
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-xs font-black text-emerald-600"
                        >
                            03
                        </div>

                        <div>
                            <h2 class="m-0 text-base font-black text-slate-800">
                                دسترسی و وضعیت
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                نقش و امکان ورود کاربر به سامانه
                            </p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div
                            class="mb-5 flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 p-4"
                        >
                            <span class="text-sm text-slate-500">
                                نقش کاربر
                            </span>

                            <strong
                                class="rounded-lg bg-blue-50 px-3 py-1.5 text-xs text-blue-600"
                            >
                                کارمند
                            </strong>
                        </div>

                        <div
                            v-if="form.errors.role"
                            class="mb-4 text-xs font-bold text-red-600"
                        >
                            {{ form.errors.role }}
                        </div>

                        <div class="grid gap-3 md:grid-cols-2">
                            <label
                                class="relative flex cursor-pointer gap-3 rounded-xl border p-4 transition"
                                :class="
                                    form.status === 'active'
                                        ? 'border-blue-300 bg-blue-50/40 ring-4 ring-blue-50'
                                        : 'border-slate-200 bg-white'
                                "
                            >
                                <input
                                    v-model="form.status"
                                    type="radio"
                                    value="active"
                                    class="absolute opacity-0"
                                >

                                <span
                                    class="mt-1 h-3 w-3 shrink-0 rounded-full bg-emerald-500 ring-4 ring-emerald-50"
                                ></span>

                                <span>
                                    <strong class="block text-sm text-slate-800">
                                        فعال
                                    </strong>

                                    <small
                                        class="mt-1 block leading-6 text-slate-400"
                                    >
                                        کاربر امکان ورود و استفاده از سامانه را
                                        دارد.
                                    </small>
                                </span>
                            </label>

                            <label
                                class="relative flex cursor-pointer gap-3 rounded-xl border p-4 transition"
                                :class="
                                    form.status === 'inactive'
                                        ? 'border-blue-300 bg-blue-50/40 ring-4 ring-blue-50'
                                        : 'border-slate-200 bg-white'
                                "
                            >
                                <input
                                    v-model="form.status"
                                    type="radio"
                                    value="inactive"
                                    class="absolute opacity-0"
                                >

                                <span
                                    class="mt-1 h-3 w-3 shrink-0 rounded-full bg-red-500 ring-4 ring-red-50"
                                ></span>

                                <span>
                                    <strong class="block text-sm text-slate-800">
                                        غیرفعال
                                    </strong>

                                    <small
                                        class="mt-1 block leading-6 text-slate-400"
                                    >
                                        دسترسی کاربر تا زمان فعال‌سازی مجدد قطع
                                        می‌شود.
                                    </small>
                                </span>
                            </label>
                        </div>

                        <div
                            v-if="form.errors.status"
                            class="mt-3 text-xs font-bold text-red-600"
                        >
                            {{ form.errors.status }}
                        </div>
                    </div>
                </section>
            </main>

            <aside class="grid gap-5 lg:sticky lg:top-24">
                <section
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <div
                        class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 font-black text-blue-600"
                    >
                        i
                    </div>

                    <h3 class="text-base font-black text-slate-800">
                        حساب کاربری CRM
                    </h3>

                    <p class="mt-2 text-xs leading-7 text-slate-500">
                        شماره موبایل باید یکتا و قابل دسترس باشد.
                    </p>

                    <ul
                        class="mt-4 list-disc border-t border-slate-100 pt-4 pr-5 text-xs leading-8 text-slate-500"
                    >
                        <li>نام کامل کاربر را ثبت کنید.</li>
                        <li>برای هر همکار حساب جدا بسازید.</li>
                        <li>برای قطع دسترسی، وضعیت را غیرفعال کنید.</li>
                    </ul>
                </section>

                <section
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <h3 class="text-base font-black text-slate-800">
                        {{
                            mode === 'edit'
                                ? 'ذخیره تغییرات'
                                : 'ایجاد حساب'
                        }}
                    </h3>

                    <p class="mt-2 text-xs leading-7 text-slate-500">
                        پس از ذخیره، اطلاعات کاربر در سامانه ثبت می‌شود.
                    </p>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="mt-5 flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 px-4 text-sm font-extrabold text-white shadow-lg shadow-blue-100 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <svg
                            v-if="!form.processing"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M5 12L10 17L19 8"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        {{
                            form.processing
                                ? 'در حال ذخیره...'
                                : mode === 'edit'
                                  ? 'ذخیره تغییرات'
                                  : 'ایجاد کاربر'
                        }}
                    </button>

                    <Link
                        href="/users"
                        class="mt-3 flex min-h-12 w-full items-center justify-center rounded-xl border border-slate-200 text-sm font-bold text-slate-500 hover:bg-slate-50"
                    >
                        انصراف
                    </Link>
                </section>
            </aside>
        </form>
    </div>
</template>