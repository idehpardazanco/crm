<script setup>
import {
    Link,
    router,
} from '@inertiajs/vue3'

import { ref } from 'vue'

const props = defineProps({
    filters: Object,

    users: Array,

    callResults: Array,

    summary: Object,

    employeePerformance: Array,

    contactStatusBreakdown: Array,

    callResultBreakdown: Array,

    latestCalls: Array,

    latestOrders: Array,
})

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

const from = ref(
    props.filters?.from ?? ''
)

const to = ref(
    props.filters?.to ?? ''
)

const userId = ref(
    props.filters?.user_id ?? ''
)

const callResult = ref(
    props.filters?.call_result ?? ''
)


/*
|--------------------------------------------------------------------------
| Labels
|--------------------------------------------------------------------------
*/

const contactStatusLabels = {
    new: 'جدید',

    contacted:
        'تماس گرفته شد',

    interested:
        'علاقه‌مند',

    follow_up:
        'نیاز به پیگیری',

    demo_sent:
        'دمو ارسال شد',

    customer:
        'مشتری شد',

    rejected:
        'رد شد',

    no_answer:
        'پاسخ نداد',

    active:
        'فعال',

    inactive:
        'غیرفعال',
}


const callResultLabels = {
    no_answer:
        'پاسخ نداد',

    unavailable:
        'در دسترس نبود',

    interested:
        'علاقه‌مند بود',

    demo_requested:
        'درخواست دمو داشت',

    price_requested:
        'درخواست قیمت داشت',

    call_later:
        'بعداً تماس گرفته شود',

    customer:
        'مشتری شد',

    not_interested:
        'تمایل نداشت',
}


const orderStatusLabels = {
    new:
        'جدید',

    reviewing:
        'در حال بررسی',

    awaiting_payment:
        'در انتظار پرداخت',

    paid:
        'پرداخت شده',

    completed:
        'انجام شده',

    cancelled:
        'لغو شده',
}


/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

const applyFilters = () => {
    router.get(
        '/reports',
        {
            from:
                from.value,

            to:
                to.value,

            user_id:
                userId.value,

            call_result:
                callResult.value,
        },
        {
            preserveState: true,

            preserveScroll: true,

            replace: true,
        }
    )
}


const resetFilters = () => {
    from.value = ''

    to.value = ''

    userId.value = ''

    callResult.value = ''

    router.get(
        '/reports'
    )
}


/*
|--------------------------------------------------------------------------
| Format
|--------------------------------------------------------------------------
*/

const formatAmount = (amount) => {
    return Number(
        amount ?? 0
    ).toLocaleString(
        'fa-IR'
    )
}


const formatDate = (date) => {
    if (!date) {
        return '-'
    }

    return new Date(
        date
    ).toLocaleString(
        'fa-IR'
    )
}
</script>


<template>
    <div
        class="p-6"
        dir="rtl"
    >

        <!-- Header -->
        <div
            class="
                flex
                flex-col
                md:flex-row
                justify-between
                md:items-center
                gap-4
                mb-6
            "
        >

            <div>

                <h1
                    class="
                        text-2xl
                        font-bold
                    "
                >
                    گزارش‌های پیشرفته
                </h1>

                <p
                    class="
                        text-gray-500
                        mt-2
                    "
                >
                    گزارش عملکرد فروش،
                    تماس‌ها، پیامک‌ها
                    و سفارش‌ها
                </p>

            </div>


            <Link
                href="/monitoring"
                class="
                    border
                    px-4
                    py-2
                    rounded
                "
            >
                گزارش فعالیت‌ها
            </Link>

        </div>


        <!-- Filters -->
        <div
            class="
                border
                rounded
                p-5
                mb-6
            "
        >

            <h2
                class="
                    font-bold
                    mb-4
                "
            >
                فیلتر گزارش
            </h2>


            <form
                @submit.prevent="
                    applyFilters
                "
            >

                <div
                    class="
                        grid
                        grid-cols-1
                        md:grid-cols-2
                        lg:grid-cols-4
                        gap-4
                    "
                >

                    <!-- From -->
                    <div>

                        <label
                            class="
                                block
                                mb-2
                                font-medium
                            "
                        >
                            از تاریخ
                        </label>

                        <input
                            v-model="from"
                            type="date"
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                    </div>


                    <!-- To -->
                    <div>

                        <label
                            class="
                                block
                                mb-2
                                font-medium
                            "
                        >
                            تا تاریخ
                        </label>

                        <input
                            v-model="to"
                            type="date"
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                    </div>


                    <!-- Employee -->
                    <div>

                        <label
                            class="
                                block
                                mb-2
                                font-medium
                            "
                        >
                            کارمند
                        </label>

                        <select
                            v-model="userId"
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                            <option value="">
                                همه کارمندان
                            </option>

                            <option
                                v-for="
                                    user in users
                                "
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>

                        </select>

                    </div>


                    <!-- Call Result -->
                    <div>

                        <label
                            class="
                                block
                                mb-2
                                font-medium
                            "
                        >
                            نتیجه تماس
                        </label>

                        <select
                            v-model="
                                callResult
                            "
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                            <option value="">
                                همه نتایج
                            </option>

                            <option
                                v-for="
                                    result in
                                    callResults
                                "
                                :key="
                                    result.value
                                "
                                :value="
                                    result.value
                                "
                            >
                                {{ result.label }}
                            </option>

                        </select>

                    </div>

                </div>


                <div
                    class="
                        flex
                        flex-wrap
                        gap-3
                        mt-5
                    "
                >

                    <button
                        type="submit"
                        class="
                            bg-blue-600
                            text-white
                            px-5
                            py-2
                            rounded
                        "
                    >
                        اعمال فیلتر
                    </button>


                    <button
                        type="button"
                        @click="
                            resetFilters
                        "
                        class="
                            border
                            px-5
                            py-2
                            rounded
                        "
                    >
                        حذف فیلترها
                    </button>

                </div>

            </form>

        </div>


        <!-- Summary -->
        <div
            class="
                grid
                grid-cols-1
                sm:grid-cols-2
                lg:grid-cols-4
                gap-4
                mb-8
            "
        >

            <!-- Contacts -->
            <div
                class="
                    border
                    rounded
                    p-5
                "
            >

                <div
                    class="
                        text-gray-500
                        mb-2
                    "
                >
                    مخاطبین
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                    "
                >
                    {{ summary.contacts }}
                </div>

            </div>


            <!-- Customers -->
            <div
                class="
                    border
                    rounded
                    p-5
                "
            >

                <div
                    class="
                        text-gray-500
                        mb-2
                    "
                >
                    مشتریان
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                    "
                >
                    {{ summary.customers }}
                </div>

            </div>


            <!-- Conversion -->
            <div
                class="
                    border
                    border-green-300
                    rounded
                    p-5
                "
            >

                <div
                    class="
                        text-green-700
                        mb-2
                    "
                >
                    نرخ تبدیل
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                        text-green-700
                    "
                >
                    {{
                        summary
                            .conversionRate
                    }}%
                </div>

            </div>


            <!-- Calls -->
            <div
                class="
                    border
                    rounded
                    p-5
                "
            >

                <div
                    class="
                        text-gray-500
                        mb-2
                    "
                >
                    تماس‌ها
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                    "
                >
                    {{ summary.calls }}
                </div>

            </div>


            <!-- SMS -->
            <div
                class="
                    border
                    rounded
                    p-5
                "
            >

                <div
                    class="
                        text-gray-500
                        mb-2
                    "
                >
                    پیامک موفق
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                    "
                >
                    {{ summary.sms }}
                </div>

            </div>


            <!-- Orders -->
            <div
                class="
                    border
                    rounded
                    p-5
                "
            >

                <div
                    class="
                        text-gray-500
                        mb-2
                    "
                >
                    سفارش‌ها
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                    "
                >
                    {{ summary.orders }}
                </div>

            </div>


            <!-- Amount -->
            <div
                class="
                    border
                    rounded
                    p-5
                    lg:col-span-2
                "
            >

                <div
                    class="
                        text-gray-500
                        mb-2
                    "
                >
                    مجموع مبلغ سفارش‌ها
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                    "
                >
                    {{
                        formatAmount(
                            summary
                                .orderAmount
                        )
                    }}
                </div>

            </div>

        </div>


        <!-- Employee Performance -->
        <div
            class="
                border
                rounded
                p-5
                mb-8
            "
        >

            <h2
                class="
                    text-lg
                    font-bold
                    mb-4
                "
            >
                عملکرد کارمندان
            </h2>


            <div
                class="
                    overflow-x-auto
                "
            >

                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
                >

                    <thead>

                        <tr>

                            <th class="border p-2">
                                کارمند
                            </th>

                            <th class="border p-2">
                                مخاطب
                            </th>

                            <th class="border p-2">
                                مشتری
                            </th>

                            <th class="border p-2">
                                تماس
                            </th>

                            <th class="border p-2">
                                تماس موفق
                            </th>

                            <th class="border p-2">
                                موفقیت تماس
                            </th>

                            <th class="border p-2">
                                پیامک
                            </th>

                            <th class="border p-2">
                                سفارش
                            </th>

                            <th class="border p-2">
                                مبلغ سفارش
                            </th>

                            <th class="border p-2">
                                نرخ تبدیل
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                employee in
                                employeePerformance
                            "
                            :key="
                                employee.id
                            "
                        >

                            <td
                                class="
                                    border
                                    p-2
                                    font-medium
                                "
                            >
                                {{ employee.name }}
                            </td>


                            <td class="border p-2">
                                {{ employee.contacts }}
                            </td>


                            <td class="border p-2">
                                {{ employee.customers }}
                            </td>


                            <td class="border p-2">
                                {{ employee.calls }}
                            </td>


                            <td class="border p-2">
                                {{
                                    employee
                                        .successfulCalls
                                }}
                            </td>


                            <td class="border p-2">
                                {{
                                    employee
                                        .callSuccessRate
                                }}%
                            </td>


                            <td class="border p-2">
                                {{ employee.sms }}
                            </td>


                            <td class="border p-2">
                                {{ employee.orders }}
                            </td>


                            <td class="border p-2">

                                {{
                                    formatAmount(
                                        employee
                                            .orderAmount
                                    )
                                }}

                            </td>


                            <td
                                class="
                                    border
                                    p-2
                                    font-bold
                                "
                            >

                                {{
                                    employee
                                        .conversionRate
                                }}%

                            </td>

                        </tr>


                        <tr
                            v-if="
                                !employeePerformance
                                    .length
                            "
                        >

                            <td
                                colspan="10"
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                اطلاعاتی وجود ندارد.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Breakdown -->
        <div
            class="
                grid
                grid-cols-1
                lg:grid-cols-2
                gap-6
                mb-8
            "
        >

            <!-- Contact Status -->
            <div
                class="
                    border
                    rounded
                    p-5
                "
            >

                <h2
                    class="
                        font-bold
                        mb-4
                    "
                >
                    وضعیت مخاطبین
                </h2>


                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
                >

                    <thead>

                        <tr>

                            <th class="border p-2">
                                وضعیت
                            </th>

                            <th class="border p-2">
                                تعداد
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                item in
                                contactStatusBreakdown
                            "
                            :key="
                                item.value
                            "
                        >

                            <td class="border p-2">
                                {{ item.label }}
                            </td>

                            <td
                                class="
                                    border
                                    p-2
                                    font-bold
                                "
                            >
                                {{ item.total }}
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>


            <!-- Call Results -->
            <div
                class="
                    border
                    rounded
                    p-5
                "
            >

                <h2
                    class="
                        font-bold
                        mb-4
                    "
                >
                    نتایج تماس‌ها
                </h2>


                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
                >

                    <thead>

                        <tr>

                            <th class="border p-2">
                                نتیجه
                            </th>

                            <th class="border p-2">
                                تعداد
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                item in
                                callResultBreakdown
                            "
                            :key="
                                item.value
                            "
                        >

                            <td class="border p-2">
                                {{ item.label }}
                            </td>

                            <td
                                class="
                                    border
                                    p-2
                                    font-bold
                                "
                            >
                                {{ item.total }}
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Latest Calls -->
        <div
            class="
                border
                rounded
                p-5
                mb-8
            "
        >

            <h2
                class="
                    font-bold
                    text-lg
                    mb-4
                "
            >
                آخرین تماس‌ها
            </h2>


            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
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
                                کارمند
                            </th>

                            <th class="border p-2">
                                نتیجه
                            </th>

                            <th class="border p-2">
                                وضعیت بعد تماس
                            </th>

                            <th class="border p-2">
                                تاریخ
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                call in
                                latestCalls
                            "
                            :key="
                                call.id
                            "
                        >

                            <td class="border p-2">

                                <Link
                                    v-if="
                                        call.contact
                                    "
                                    :href="
                                        `/contacts/${call.contact.id}`
                                    "
                                    class="
                                        text-blue-600
                                    "
                                >
                                    {{
                                        call.contact
                                            .name
                                    }}
                                </Link>

                                <span v-else>
                                    -
                                </span>

                            </td>


                            <td class="border p-2">

                                {{
                                    call.contact
                                        ?.business_name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    call.user?.name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    callResultLabels[
                                        call.result
                                    ]
                                    ?? call.result
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    contactStatusLabels[
                                        call
                                            .status_after_call
                                    ]
                                    ??
                                    call
                                        .status_after_call
                                    ??
                                    '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    formatDate(
                                        call.created_at
                                    )
                                }}

                            </td>

                        </tr>


                        <tr
                            v-if="
                                !latestCalls.length
                            "
                        >

                            <td
                                colspan="6"
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                تماسی یافت نشد.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Latest Orders -->
        <div
            class="
                border
                rounded
                p-5
            "
        >

            <h2
                class="
                    font-bold
                    text-lg
                    mb-4
                "
            >
                آخرین سفارش‌ها
            </h2>


            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
                >

                    <thead>

                        <tr>

                            <th class="border p-2">
                                مشتری
                            </th>

                            <th class="border p-2">
                                محصول
                            </th>

                            <th class="border p-2">
                                کارمند
                            </th>

                            <th class="border p-2">
                                مبلغ
                            </th>

                            <th class="border p-2">
                                وضعیت
                            </th>

                            <th class="border p-2">
                                تاریخ
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                order in
                                latestOrders
                            "
                            :key="
                                order.id
                            "
                        >

                            <td class="border p-2">

                                <Link
                                    v-if="
                                        order.contact
                                    "
                                    :href="
                                        `/contacts/${order.contact.id}`
                                    "
                                    class="
                                        text-blue-600
                                    "
                                >
                                    {{
                                        order.contact
                                            .name
                                    }}
                                </Link>

                                <span v-else>
                                    -
                                </span>

                            </td>


                            <td class="border p-2">

                                {{
                                    order
                                        .product_name
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    order.user?.name
                                    ?? '-'
                                }}

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
                                    orderStatusLabels[
                                        order.status
                                    ]
                                    ?? order.status
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    formatDate(
                                        order.created_at
                                    )
                                }}

                            </td>

                        </tr>


                        <tr
                            v-if="
                                !latestOrders.length
                            "
                        >

                            <td
                                colspan="6"
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                سفارشی یافت نشد.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</template>