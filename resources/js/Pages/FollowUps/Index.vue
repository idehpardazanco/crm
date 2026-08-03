<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    followUps: Object,
})

const search = ref('')

const doSearch = () => {
    router.get(
        '/followups',
        {
            search: search.value,
        },
        {
            preserveState: true,
        }
    )
}

const remove = (id) => {
    if (confirm('حذف شود؟')) {
        router.delete(`/followups/${id}`)
    }
}
</script>

<template>
    <div class="p-6">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-xl font-bold">
                پیگیری‌ها
            </h1>

            <a
                href="/followups/create"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                پیگیری جدید
            </a>

        </div>


        <div class="mb-5">

            <input
                v-model="search"
                @keyup.enter="doSearch"
                type="text"
                class="border p-2 rounded w-64"
                placeholder="جستجو مشتری"
            >

        </div>


        <table class="w-full border-collapse border">

            <thead>

                <tr>

                    <th class="border p-2">
                        مشتری
                    </th>

                    <th class="border p-2">
                        موبایل
                    </th>

                    <th class="border p-2">
                        عنوان
                    </th>

                    <th class="border p-2">
                        تاریخ پیگیری
                    </th>

                    <th class="border p-2">
                        وضعیت
                    </th>

                    <th class="border p-2">
                        عملیات
                    </th>

                </tr>

            </thead>


            <tbody>

                <tr
                    v-for="item in followUps.data"
                    :key="item.id"
                >

                    <td class="border p-2">
                        {{ item.contact?.name ?? '-' }}
                    </td>


                    <td class="border p-2">
                        {{ item.contact?.mobile ?? '-' }}
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


                    <td class="border p-2">

                        <button
                            @click="remove(item.id)"
                            class="text-red-600"
                        >
                            حذف
                        </button>

                    </td>

                </tr>


                <tr v-if="!followUps.data.length">

                    <td
                        colspan="6"
                        class="border p-4 text-center"
                    >
                        موردی یافت نشد
                    </td>

                </tr>

            </tbody>

        </table>

    </div>
</template>