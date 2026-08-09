<script setup>
import {
    Link,
    router,
} from '@inertiajs/vue3'

import { ref } from 'vue'

const props = defineProps({
    orders: Object,
    filters: Object,
    isAdmin: Boolean,
})

const search = ref(
    props.filters?.search ?? ''
)

const statusLabels = {
    new: 'جدید',
    reviewing: 'در حال بررسی',
    awaiting_payment: 'در انتظار پرداخت',
    paid: 'پرداخت شده',
    completed: 'انجام شده',
    cancelled: 'لغو شده',
}

const doSearch = () => {
    router.get(
        '/orders',
        {
            search: search.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}

const remove = (id) => {
    if (!confirm('سفارش حذف شود؟')) {
        return
    }

    router.delete(
        `/orders/${id}`
    )
}

const formatAmount = (amount) => {
    return Number(
        amount ?? 0
    ).toLocaleString('fa-IR')
}
</script>

<template>
    <div class="p-6">

        <div
            class="flex justify-between items-center mb-6"
        >
            <h1 class="text-xl font-bold">
                سفارش‌ها
            </h1>

            <Link
                href="/orders/create"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                سفارش جدید
            </Link>
        </div>


        <div class="mb-5">

            <input
                v-model="search"
                @keyup.enter="doSearch"
                type="text"
                class="border p-2 rounded w-full max-w-md"
                placeholder="جستجو محصول، مخاطب یا موبایل"
            >

        </div>


        <div class="overflow-x-auto">

            <table
                class="w-full border-collapse border"
            >

                <thead>

                    <tr>

                        <th class="border p-2">
                            مخاطب
                        </th>

                        <th class="border p-2">
                            کسب‌وکار
                        </th>

                        <th class="border p-2">
                            محصول
                        </th>

                        <th class="border p-2">
                            مبلغ
                        </th>

                        <th class="border p-2">
                            وضعیت
                        </th>

                        <th
                            v-if="isAdmin"
                            class="border p-2"
                        >
                            ثبت‌کننده
                        </th>

                        <th class="border p-2">
                            عملیات
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr
                        v-for="order in orders.data"
                        :key="order.id"
                    >

                        <td class="border p-2">

                            <Link
                                v-if="order.contact"
                                :href="
                                    `/contacts/${order.contact.id}`
                                "
                                class="text-blue-600"
                            >
                                {{ order.contact.name }}
                            </Link>

                            <span v-else>
                                -
                            </span>

                        </td>


                        <td class="border p-2">

                            {{
                                order.contact
                                    ?.business_name
                                    ?? '-'
                            }}

                        </td>


                        <td class="border p-2">
                            {{ order.product_name }}
                        </td>


                        <td class="border p-2">

                            {{
                                formatAmount(
                                    order.amount
                                )
                            }}

                        </td>


                        <td class="border p-2">

                            {{
                                statusLabels[
                                    order.status
                                ] ?? order.status
                            }}

                        </td>


                        <td
                            v-if="isAdmin"
                            class="border p-2"
                        >

                            {{
                                order.user?.name
                                    ?? '-'
                            }}

                        </td>


                        <td class="border p-2">

                            <div class="flex gap-3">

                                <Link
                                    :href="
                                        `/orders/${order.id}/edit`
                                    "
                                    class="text-blue-600"
                                >
                                    ویرایش
                                </Link>

                                <button
                                    v-if="isAdmin"
                                    type="button"
                                    @click="
                                        remove(
                                            order.id
                                        )
                                    "
                                    class="text-red-600"
                                >
                                    حذف
                                </button>

                            </div>

                        </td>

                    </tr>


                    <tr
                        v-if="!orders.data.length"
                    >

                        <td
                            :colspan="
                                isAdmin ? 7 : 6
                            "
                            class="border p-4 text-center"
                        >
                            سفارشی وجود ندارد
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>
</template>