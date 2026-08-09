<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    logs: Object,
})

const search = ref('')

const statusLabels = {
    queued: 'در صف ارسال',
    sent: 'ارسال موفق',
    failed: 'ارسال ناموفق',
}

const doSearch = () => {
    router.get(
        '/sms/logs',
        {
            search: search.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}
</script>

<template>
    <div class="p-6">

        <h1 class="text-xl font-bold mb-5">
            تاریخچه پیامک‌ها
        </h1>

        <div class="mb-5">
            <input
                v-model="search"
                @keyup.enter="doSearch"
                type="text"
                class="border p-2 rounded"
                placeholder="جستجو شماره یا متن پیامک"
            >
        </div>

        <table class="w-full border-collapse border">

            <thead>
                <tr>
                    <th class="border p-2">
                        شماره گیرنده
                    </th>

                    <th class="border p-2">
                        مشتری
                    </th>

                    <th class="border p-2">
                        متن پیام
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
                    v-for="log in logs.data"
                    :key="log.id"
                >
                    <td class="border p-2">
                        {{ log.mobile }}
                    </td>

                    <td class="border p-2">
                        {{ log.sendable?.name ?? '-' }}
                    </td>

                    <td class="border p-2">
                        {{ log.message }}
                    </td>

                    <td class="border p-2">
                        {{ statusLabels[log.status] ?? log.status }}
                    </td>

                    <td class="border p-2">
                        {{ log.created_at }}
                    </td>
                </tr>

                <tr v-if="!logs.data.length">
                    <td
                        colspan="5"
                        class="border p-4 text-center"
                    >
                        موردی یافت نشد
                    </td>
                </tr>
            </tbody>

        </table>

    </div>
</template>