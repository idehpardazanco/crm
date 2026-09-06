<script setup>
import { Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    orders: {
        type: Object,
        required: true,
    },

    filters: {
        type: Object,
        default: () => ({}),
    },

    isAdmin: Boolean,
})

const search = ref(props.filters?.search ?? '')
const selectedOrder = ref(null)
const deleting = ref(false)

const totalOrders = computed(() => {
    return props.orders?.total ?? props.orders?.data?.length ?? 0
})

const pageAmount = computed(() => {
    return (props.orders?.data ?? []).reduce(
        (total, order) => total + Number(order.amount ?? 0),
        0
    )
})

const statusLabels = {
    new: 'جدید',
    reviewing: 'در حال بررسی',
    awaiting_payment: 'در انتظار پرداخت',
    paid: 'پرداخت شده',
    completed: 'انجام شده',
    cancelled: 'لغو شده',
}

function statusClasses(status) {
    return {
        new: 'bg-blue-50 text-blue-600',
        reviewing: 'bg-violet-50 text-violet-600',
        awaiting_payment: 'bg-amber-50 text-amber-700',
        paid: 'bg-emerald-50 text-emerald-600',
        completed: 'bg-emerald-50 text-emerald-600',
        cancelled: 'bg-red-50 text-red-600',
    }[status] ?? 'bg-slate-100 text-slate-500'
}

function formatAmount(amount) {
    return Number(amount ?? 0).toLocaleString('fa-IR')
}

function doSearch() {
    router.get(
        '/orders',
        {
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

function clearSearch() {
    search.value = ''

    router.get(
        '/orders',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

function confirmDelete() {
    if (!selectedOrder.value || deleting.value) {
        return
    }

    deleting.value = true

    router.delete(
        `/orders/${selectedOrder.value.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {
                selectedOrder.value = null
            },

            onFinish: () => {
                deleting.value = false
            },
        }
    )
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
                            d="M3 6H21L19 20H5L3 6ZM8 6V5C8 2.79086 9.79086 1 12 1C14.2091 1 16 2.79086 16 5V6"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <div>
                    <div class="text-xs font-extrabold text-blue-600">
                        مدیریت فروش
                    </div>

                    <h1 class="mt-1 text-2xl font-black text-slate-900">
                        سفارش‌ها
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        ثبت و پیگیری سفارش‌های مشتریان
                    </p>
                </div>
            </div>

            <Link
                href="/orders/create"
                class="flex min-h-12 items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 px-5 text-sm font-extrabold text-white shadow-lg shadow-blue-100"
            >
                <span class="text-xl">+</span>
                سفارش جدید
            </Link>
        </section>

        <section class="mb-5 grid gap-4 md:grid-cols-2">
            <div
                class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <span class="text-sm text-slate-500">
                    کل سفارش‌ها
                </span>

                <strong class="text-2xl font-black text-slate-900">
                    {{ totalOrders }}
                </strong>
            </div>

            <div
                class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <span class="text-sm text-slate-500">
                    جمع مبلغ این صفحه
                </span>

                <strong class="text-xl font-black text-slate-900">
                    {{ formatAmount(pageAmount) }}
                    <small class="text-xs text-slate-400">تومان</small>
                </strong>
            </div>
        </section>

        <section
            class="mb-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
        >
            <form
                class="flex flex-col gap-3 md:flex-row"
                @submit.prevent="doSearch"
            >
                <input
                    v-model="search"
                    type="search"
                    placeholder="جستجوی محصول، مخاطب یا شماره موبایل"
                    class="min-w-0 flex-1"
                >

                <button
                    type="submit"
                    class="min-h-11 rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white"
                >
                    جستجو
                </button>

                <button
                    v-if="search"
                    type="button"
                    class="min-h-11 rounded-xl bg-slate-100 px-5 text-sm font-bold text-slate-500"
                    @click="clearSearch"
                >
                    پاک کردن
                </button>
            </form>
        </section>

        <section
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
        >
            <div class="overflow-x-auto">
                <table class="min-w-[1000px]">
                    <thead>
                        <tr>
                            <th>مخاطب</th>
                            <th>کسب‌وکار</th>
                            <th>محصول</th>
                            <th>مبلغ</th>
                            <th>وضعیت</th>
                            <th v-if="isAdmin">ثبت‌کننده</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="order in orders.data"
                            :key="order.id"
                        >
                            <td>
                                <Link
                                    v-if="order.contact"
                                    :href="`/contacts/${order.contact.id}`"
                                    class="flex items-center gap-3"
                                >
                                    <span
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 font-black text-blue-600"
                                    >
                                        {{
                                            order.contact.name
                                                ?.trim()
                                                .substring(0, 1) ?? '؟'
                                        }}
                                    </span>

                                    <strong class="text-slate-800">
                                        {{ order.contact.name }}
                                    </strong>
                                </Link>

                                <span v-else>-</span>
                            </td>

                            <td>
                                {{ order.contact?.business_name ?? '-' }}
                            </td>

                            <td>
                                <strong class="text-slate-700">
                                    {{ order.product_name }}
                                </strong>
                            </td>

                            <td class="whitespace-nowrap">
                                {{ formatAmount(order.amount) }}
                                <small class="text-slate-400">تومان</small>
                            </td>

                            <td>
                                <span
                                    class="inline-flex rounded-lg px-3 py-1.5 text-xs font-extrabold"
                                    :class="statusClasses(order.status)"
                                >
                                    {{
                                        statusLabels[order.status]
                                        ?? order.status
                                    }}
                                </span>
                            </td>

                            <td v-if="isAdmin">
                                {{ order.user?.name ?? '-' }}
                            </td>

                            <td>
                                <div class="flex items-center gap-4">
                                    <Link
                                        :href="`/orders/${order.id}/edit`"
                                        class="font-bold text-blue-600"
                                    >
                                        ویرایش
                                    </Link>

                                    <button
                                        v-if="isAdmin"
                                        type="button"
                                        class="font-bold text-red-600"
                                        @click="selectedOrder = order"
                                    >
                                        حذف
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="!orders.data?.length">
                            <td
                                :colspan="isAdmin ? 7 : 6"
                                class="py-14 text-center"
                            >
                                <strong class="block text-slate-700">
                                    سفارشی یافت نشد
                                </strong>

                                <span
                                    class="mt-2 block text-xs text-slate-400"
                                >
                                    عبارت جستجو را تغییر دهید یا سفارش جدید ثبت
                                    کنید.
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div
            v-if="orders.links && orders.links.length > 3"
            class="mt-5 flex flex-col gap-4 text-sm text-slate-500 md:flex-row md:items-center md:justify-between"
        >
            <div>
                صفحه
                <strong>{{ orders.current_page ?? 1 }}</strong>
                از
                <strong>{{ orders.last_page ?? 1 }}</strong>
            </div>

            <div class="flex flex-wrap gap-2">
                <template
                    v-for="link in orders.links"
                    :key="`${link.label}-${link.url}`"
                >
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-scroll
                        class="flex min-h-10 min-w-10 items-center justify-center rounded-xl border px-3"
                        :class="
                            link.active
                                ? 'border-blue-600 bg-blue-600 text-white'
                                : 'border-slate-200 bg-white text-slate-500'
                        "
                        v-html="link.label"
                    />

                    <span
                        v-else
                        class="flex min-h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3 text-slate-300"
                        v-html="link.label"
                    ></span>
                </template>
            </div>
        </div>

        <Teleport to="body">
            <div
                v-if="selectedOrder"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/50 p-5 backdrop-blur-sm"
                dir="rtl"
                @click.self="!deleting && (selectedOrder = null)"
            >
                <div
                    class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-xl font-black text-red-600"
                    >
                        !
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        حذف سفارش؟
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        سفارش
                        <strong>
                            «{{ selectedOrder.product_name }}»
                        </strong>
                        حذف شود؟
                    </p>

                    <p class="mt-2 text-xs text-slate-400">
                        این عملیات قابل بازگشت نیست.
                    </p>

                    <div class="mt-6 flex gap-3">
                        <button
                            type="button"
                            :disabled="deleting"
                            class="min-h-11 flex-1 rounded-xl border border-slate-200 font-bold text-slate-600"
                            @click="selectedOrder = null"
                        >
                            انصراف
                        </button>

                        <button
                            type="button"
                            :disabled="deleting"
                            class="min-h-11 flex-1 rounded-xl bg-red-600 font-bold text-white disabled:opacity-50"
                            @click="confirmDelete"
                        >
                            {{
                                deleting
                                    ? 'در حال حذف...'
                                    : 'بله، حذف شود'
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>