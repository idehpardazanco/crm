<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { computed } from 'vue'
import PersianDateTimePicker from '../../Components/PersianDateTimePicker.vue'

const props = defineProps({
    contacts: {
        type: Array,
        default: () => [],
    },
})

const form = useForm({
    contact_id: '',
    title: '',
    description: '',
    follow_up_at: '',
    status: 'pending',
})

const selectedContact = computed(() => {
    return props.contacts.find(
        contact =>
            String(contact.id)
            === String(form.contact_id)
    ) ?? null
})

const submit = () => {
    form.post('/followups', {
        preserveScroll: true,
    })
}
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
                            d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <div class="text-xs font-extrabold text-blue-600">
                        مدیریت ارتباط با مشتری
                    </div>

                    <h1 class="mt-1 text-2xl font-black text-slate-900">
                        ایجاد پیگیری جدید
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        زمان و موضوع پیگیری بعدی مشتری را ثبت کنید.
                    </p>
                </div>
            </div>

            <Link
                href="/followups"
                class="flex min-h-11 items-center justify-center rounded-xl border border-slate-200 bg-white px-5 text-sm font-bold text-slate-600"
            >
                بازگشت
            </Link>
        </section>

        <form
            class="grid items-start gap-5 lg:grid-cols-[minmax(0,1fr)_330px]"
            @submit.prevent="submit"
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
                                انتخاب مخاطب
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                مخاطب مرتبط با پیگیری را مشخص کنید.
                            </p>
                        </div>
                    </div>

                    <div class="p-5">
                        <label class="mb-2 block text-sm font-bold">
                            مخاطب
                            <span class="text-red-600">*</span>
                        </label>

                        <select
                            v-model="form.contact_id"
                            class="w-full"
                        >
                            <option value="">
                                انتخاب مخاطب
                            </option>

                            <option
                                v-for="contact in contacts"
                                :key="contact.id"
                                :value="contact.id"
                            >
                                {{ contact.name }}
                                -
                                {{
                                    contact.business_name
                                    ?? 'بدون کسب‌وکار'
                                }}
                                -
                                {{ contact.mobile }}
                            </option>
                        </select>

                        <div
                            v-if="form.errors.contact_id"
                            class="mt-2 text-xs font-bold text-red-600"
                        >
                            {{ form.errors.contact_id }}
                        </div>

                        <div
                            v-if="selectedContact"
                            class="mt-4 flex flex-wrap items-center gap-3 rounded-xl border border-slate-200 bg-blue-50/30 p-4"
                        >
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100 font-black text-blue-600"
                            >
                                {{
                                    selectedContact.name
                                        ?.trim()
                                        .substring(0, 1) ?? '؟'
                                }}
                            </span>

                            <div class="min-w-0 flex-1">
                                <strong class="block text-sm text-slate-800">
                                    {{ selectedContact.name }}
                                </strong>

                                <small class="mt-1 block text-slate-400">
                                    {{
                                        selectedContact.business_name
                                        ?? 'بدون کسب‌وکار'
                                    }}
                                </small>
                            </div>

                            <a
                                v-if="selectedContact.mobile"
                                :href="`tel:${selectedContact.mobile}`"
                                class="text-sm font-bold text-blue-600"
                                dir="ltr"
                            >
                                {{ selectedContact.mobile }}
                            </a>
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
                                جزئیات پیگیری
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                موضوع و توضیحات موردنیاز را وارد کنید.
                            </p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div>
                            <label class="mb-2 block text-sm font-bold">
                                عنوان پیگیری
                                <span class="text-red-600">*</span>
                            </label>

                            <input
                                v-model="form.title"
                                type="text"
                                placeholder="مثلاً تماس برای پیگیری دمو"
                                class="w-full"
                            >

                            <div
                                v-if="form.errors.title"
                                class="mt-2 text-xs font-bold text-red-600"
                            >
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <div class="mt-5">
                            <label class="mb-2 block text-sm font-bold">
                                توضیحات
                            </label>

                            <textarea
                                v-model="form.description"
                                rows="5"
                                placeholder="نکات لازم برای تماس بعدی..."
                                class="w-full"
                            ></textarea>

                            <div
                                v-if="form.errors.description"
                                class="mt-2 text-xs font-bold text-red-600"
                            >
                                {{ form.errors.description }}
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
                            class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-xs font-black text-amber-700"
                        >
                            03
                        </div>

                        <div>
                            <h2 class="m-0 text-base font-black text-slate-800">
                                زمان پیگیری
                            </h2>

                            <p class="mt-1 text-xs text-slate-400">
                                تاریخ و ساعت دقیق یادآوری را انتخاب کنید.
                            </p>
                        </div>
                    </div>

                    <div class="p-5">
                        <label class="mb-2 block text-sm font-bold">
                            تاریخ و ساعت
                            <span class="text-red-600">*</span>
                        </label>

                        <PersianDateTimePicker
                            v-model="form.follow_up_at"
                            placeholder="تاریخ و ساعت پیگیری را انتخاب کنید"
                        />

                        <div
                            v-if="form.errors.follow_up_at"
                            class="mt-2 text-xs font-bold text-red-600"
                        >
                            {{ form.errors.follow_up_at }}
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
                        پیگیری مؤثر
                    </h3>

                    <p class="mt-2 text-xs leading-7 text-slate-500">
                        موضوع پیگیری را واضح و زمان آن را واقع‌بینانه انتخاب
                        کنید.
                    </p>

                    <ul
                        class="mt-4 list-disc border-t border-slate-100 pt-4 pr-5 text-xs leading-8 text-slate-500"
                    >
                        <li>عنوان کوتاه و مشخص باشد.</li>
                        <li>نکات مکالمه قبلی را بنویسید.</li>
                        <li>زمان مناسب تماس مشتری را در نظر بگیرید.</li>
                    </ul>
                </section>

                <section
                    class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
                >
                    <h3 class="text-base font-black text-slate-800">
                        ثبت پیگیری
                    </h3>

                    <p class="mt-2 text-xs leading-7 text-slate-500">
                        یادآوری در داشبورد و فهرست پیگیری‌ها نمایش داده می‌شود.
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
                                : 'ثبت پیگیری'
                        }}
                    </button>

                    <Link
                        href="/followups"
                        class="mt-3 flex min-h-12 w-full items-center justify-center rounded-xl border border-slate-200 text-sm font-bold text-slate-500"
                    >
                        انصراف
                    </Link>
                </section>
            </aside>
        </form>
    </div>
</template>