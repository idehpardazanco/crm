<script setup>
defineProps({
    stats: Object,
    latestInteractions: Array,
    latestFollowUps: Array,
})
</script>

<template>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            داشبورد مدیریت
        </h1>

        <div class="grid grid-cols-4 gap-4 mb-8">

            <div class="border rounded p-5">
                <p>
                    مخاطبین
                </p>

                <strong class="text-2xl">
                    {{ stats.contacts }}
                </strong>
            </div>

            <div class="border rounded p-5">
                <p>
                    مشتریان
                </p>

                <strong class="text-2xl">
                    {{ stats.customers }}
                </strong>
            </div>

            <div class="border rounded p-5">
                <p>
                    پیامک‌ها
                </p>

                <strong class="text-2xl">
                    {{ stats.sms }}
                </strong>
            </div>

            <div class="border rounded p-5">
                <p>
                    تعاملات
                </p>

                <strong class="text-2xl">
                    {{ stats.interactions }}
                </strong>
            </div>

        </div>

        <div class="grid grid-cols-3 gap-4 mb-8">

            <div class="border rounded p-5">
                <p>
                    پیگیری‌های باز
                </p>

                <strong class="text-2xl">
                    {{ stats.pendingFollowUps }}
                </strong>
            </div>

            <div class="border rounded p-5">
                <p>
                    پیگیری امروز
                </p>

                <strong class="text-2xl">
                    {{ stats.todayFollowUps }}
                </strong>
            </div>

            <div class="border rounded p-5">
                <p>
                    پیگیری عقب افتاده
                </p>

                <strong class="text-2xl">
                    {{ stats.overdueFollowUps }}
                </strong>
            </div>

        </div>

        <div class="border rounded p-5 mb-8">

            <h2 class="font-bold mb-4">
                آخرین پیگیری‌ها
            </h2>

            <table class="w-full border-collapse border">

                <thead>
                    <tr>
                        <th class="border p-2">
                            مشتری
                        </th>

                        <th class="border p-2">
                            عنوان
                        </th>

                        <th class="border p-2">
                            تاریخ
                        </th>

                        <th class="border p-2">
                            وضعیت
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="item in latestFollowUps"
                        :key="item.id"
                    >
                        <td class="border p-2">
                            {{ item.contact?.name ?? '-' }}
                        </td>

                        <td class="border p-2">
                            {{ item.title }}
                        </td>

                        <td class="border p-2">
                            {{ item.follow_up_at }}
                        </td>

                        <td class="border p-2">
                            {{ item.status }}
                        </td>
                    </tr>
                </tbody>

            </table>

        </div>

    </div>
</template>