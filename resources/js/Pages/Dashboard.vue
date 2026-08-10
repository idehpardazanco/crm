<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    dashboardType: String,
    stats: Object,
    todayFollowUps: Array,
    overdueFollowUps: Array,
    latestCalls: Array,
    employeePerformance: Array,
})

const statusLabels = {
    new: 'جدید',
    contacted: 'تماس گرفته شد',
    interested: 'علاقه‌مند',
    follow_up: 'نیاز به پیگیری',
    demo_sent: 'دمو ارسال شد',
    customer: 'مشتری شد',
    rejected: 'رد شد',
    no_answer: 'پاسخ نداد',
    active: 'فعال',
    inactive: 'غیرفعال',
}

const callResultLabels = {
    no_answer: 'پاسخ نداد',
    unavailable: 'در دسترس نبود',
    interested: 'علاقه‌مند بود',
    demo_requested: 'درخواست دمو داشت',
    price_requested: 'قیمت خواست',
    call_later: 'بعداً تماس بگیریم',
    customer: 'مشتری شد',
    not_interested: 'تمایل نداشت',
}
</script>

<template>
    <div
        class="p-6"
        dir="rtl"
    >

        <div class="mb-6">

            <h1 class="text-2xl font-bold">

                {{
                    dashboardType === 'admin'
                        ? 'داشبورد مدیریت'
                        : 'داشبورد من'
                }}

            </h1>

        </div>


        <!-- داشبورد مدیر -->
        <div
            v-if="dashboardType === 'admin'"
            class="
                grid
                grid-cols-1
                sm:grid-cols-2
                lg:grid-cols-4
                gap-4
                mb-8
            "
        >

            <div class="border rounded p-5">
                <div class="text-gray-500 mb-2">
                    کل مخاطبین
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.contacts }}
                </div>
            </div>


            <div class="border rounded p-5">
                <div class="text-gray-500 mb-2">
                    تماس‌های امروز
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.todayCalls }}
                </div>
            </div>


            <div class="border rounded p-5">
                <div class="text-gray-500 mb-2">
                    پیامک‌های ارسال‌شده امروز
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.todaySms }}
                </div>
            </div>


            <div class="border rounded p-5">
                <div class="text-gray-500 mb-2">
                    یادآوری‌های امروز
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.todayFollowUps }}
                </div>
            </div>


            <div class="border rounded p-5">
                <div class="text-gray-500 mb-2">
                    سفارش‌های جدید
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.newOrders }}
                </div>
            </div>


            <div class="border rounded p-5">
                <div class="text-gray-500 mb-2">
                    مشتریان
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.customers }}
                </div>
            </div>


            <div
                class="
                    border
                    border-red-300
                    rounded
                    p-5
                "
            >
                <div class="text-red-600 mb-2">
                    پیگیری‌های عقب‌افتاده
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                        text-red-600
                    "
                >
                    {{ stats.overdueFollowUps }}
                </div>
            </div>


            <div
                class="
                    border
                    border-green-300
                    rounded
                    p-5
                "
            >
                <div class="text-green-700 mb-2">
                    نرخ تبدیل به مشتری
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                        text-green-700
                    "
                >
                    {{ stats.conversionRate }}%
                </div>
            </div>

        </div>


        <!-- داشبورد کارمند -->
        <div
            v-else
            class="
                grid
                grid-cols-1
                sm:grid-cols-2
                lg:grid-cols-3
                gap-4
                mb-8
            "
        >

            <div class="border rounded p-5">

                <div class="text-gray-500 mb-2">
                    تماس‌های امروز
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.todayCalls }}
                </div>

            </div>


            <div class="border rounded p-5">

                <div class="text-gray-500 mb-2">
                    یادآوری‌های امروز
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.todayFollowUps }}
                </div>

            </div>


            <div class="border rounded p-5">

                <div class="text-gray-500 mb-2">
                    مخاطبین جدید
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.newContacts }}
                </div>

            </div>


            <div
                class="
                    border
                    border-red-300
                    rounded
                    p-5
                "
            >

                <div class="text-red-600 mb-2">
                    پیگیری‌های عقب‌افتاده
                </div>

                <div
                    class="
                        text-2xl
                        font-bold
                        text-red-600
                    "
                >
                    {{ stats.overdueFollowUps }}
                </div>

            </div>


            <div class="border rounded p-5">

                <div class="text-gray-500 mb-2">
                    پیامک‌های امروز من
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.todaySms }}
                </div>

            </div>


            <div class="border rounded p-5">

                <div class="text-gray-500 mb-2">
                    سفارش‌های ثبت‌شده من
                </div>

                <div class="text-2xl font-bold">
                    {{ stats.orders }}
                </div>

            </div>

        </div>


        <!-- عملکرد کارمندان -->
        <div
            v-if="dashboardType === 'admin'"
            class="border rounded p-5 mb-8"
        >

            <h2 class="text-lg font-bold mb-4">
                عملکرد کارمندان
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
                                کارمند
                            </th>

                            <th class="border p-2">
                                مخاطبین
                            </th>

                            <th class="border p-2">
                                مشتریان
                            </th>

                            <th class="border p-2">
                                کل تماس‌ها
                            </th>

                            <th class="border p-2">
                                تماس امروز
                            </th>

                            <th class="border p-2">
                                پیامک موفق
                            </th>

                            <th class="border p-2">
                                سفارش
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
                            :key="employee.id"
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
                                {{ employee.todayCalls }}
                            </td>


                            <td class="border p-2">
                                {{ employee.sms }}
                            </td>


                            <td class="border p-2">
                                {{ employee.orders }}
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
                                colspan="8"
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                کارمند فعالی وجود ندارد
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- پیگیری‌های عقب افتاده -->
        <div
            v-if="overdueFollowUps.length"
            class="
                border
                border-red-300
                rounded
                p-5
                mb-8
            "
        >

            <div
                class="
                    flex
                    justify-between
                    items-center
                    mb-4
                "
            >

                <h2
                    class="
                        font-bold
                        text-red-600
                    "
                >
                    پیگیری‌های عقب‌افتاده
                </h2>

                <Link
                    href="/followups"
                    class="text-blue-600"
                >
                    مشاهده همه
                </Link>

            </div>


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
                                موبایل
                            </th>

                            <th class="border p-2">
                                عنوان
                            </th>

                            <th class="border p-2">
                                زمان پیگیری
                            </th>

                            <th
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                                class="border p-2"
                            >
                                کارمند
                            </th>

                            <th class="border p-2">
                                عملیات
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                item in
                                overdueFollowUps
                            "
                            :key="item.id"
                        >

                            <td class="border p-2">

                                {{
                                    item.contact?.name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    item.contact
                                        ?.business_name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    item.contact?.mobile
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">
                                {{ item.title }}
                            </td>


                            <td
                                class="
                                    border
                                    p-2
                                    text-red-600
                                "
                            >
                                {{ item.follow_up_at }}
                            </td>


                            <td
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                                class="border p-2"
                            >

                                {{
                                    item.user?.name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                <Link
                                    v-if="item.contact"
                                    :href="
                                        `/contacts/${item.contact.id}`
                                    "
                                    class="text-blue-600"
                                >
                                    مشاهده مخاطب
                                </Link>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- پیگیری امروز -->
        <div class="border rounded p-5 mb-8">

            <div
                class="
                    flex
                    justify-between
                    items-center
                    mb-4
                "
            >

                <h2 class="font-bold">
                    پیگیری‌های امروز
                </h2>

                <Link
                    href="/followups"
                    class="text-blue-600"
                >
                    مشاهده همه
                </Link>

            </div>


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
                                موبایل
                            </th>

                            <th class="border p-2">
                                عنوان
                            </th>

                            <th class="border p-2">
                                زمان
                            </th>

                            <th
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                                class="border p-2"
                            >
                                کارمند
                            </th>

                            <th class="border p-2">
                                عملیات
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                item in
                                todayFollowUps
                            "
                            :key="item.id"
                        >

                            <td class="border p-2">

                                {{
                                    item.contact?.name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    item.contact
                                        ?.business_name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    item.contact?.mobile
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">
                                {{ item.title }}
                            </td>


                            <td class="border p-2">
                                {{ item.follow_up_at }}
                            </td>


                            <td
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                                class="border p-2"
                            >

                                {{
                                    item.user?.name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                <Link
                                    v-if="item.contact"
                                    :href="
                                        `/contacts/${item.contact.id}`
                                    "
                                    class="text-blue-600"
                                >
                                    مشاهده مخاطب
                                </Link>

                            </td>

                        </tr>


                        <tr
                            v-if="
                                !todayFollowUps.length
                            "
                        >

                            <td
                                :colspan="
                                    dashboardType ===
                                    'admin'
                                        ? 7
                                        : 6
                                "
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                پیگیری برای امروز وجود ندارد
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- آخرین تماس ها -->
        <div class="border rounded p-5">

            <h2 class="font-bold mb-4">
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
                                نتیجه تماس
                            </th>

                            <th class="border p-2">
                                وضعیت بعد از تماس
                            </th>

                            <th
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                                class="border p-2"
                            >
                                کارمند
                            </th>

                            <th class="border p-2">
                                تاریخ
                            </th>

                            <th class="border p-2">
                                عملیات
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                item in latestCalls
                            "
                            :key="item.id"
                        >

                            <td class="border p-2">

                                {{
                                    item.contact?.name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    callResultLabels[
                                        item.result
                                    ]
                                    ?? item.result
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    statusLabels[
                                        item
                                            .status_after_call
                                    ]
                                    ??
                                    item
                                        .status_after_call
                                    ??
                                    '-'
                                }}

                            </td>


                            <td
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                                class="border p-2"
                            >

                                {{
                                    item.user?.name
                                    ?? '-'
                                }}

                            </td>


                            <td class="border p-2">
                                {{ item.created_at }}
                            </td>


                            <td class="border p-2">

                                <Link
                                    v-if="item.contact"
                                    :href="
                                        `/contacts/${item.contact.id}`
                                    "
                                    class="text-blue-600"
                                >
                                    مشاهده
                                </Link>

                            </td>

                        </tr>


                        <tr
                            v-if="!latestCalls.length"
                        >

                            <td
                                :colspan="
                                    dashboardType ===
                                    'admin'
                                        ? 6
                                        : 5
                                "
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                تماسی ثبت نشده است
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</template>