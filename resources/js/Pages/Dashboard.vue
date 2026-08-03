<script setup>
defineProps({
    stats: Object,
    latestInteractions: Array,
})
</script>


<template>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            داشبورد مدیریت
        </h1>


        <div class="grid grid-cols-4 gap-4 mb-8">

            <div class="border rounded p-5">
                <h3>
                    مخاطبین
                </h3>

                <p class="text-2xl font-bold">
                    {{ stats.contacts }}
                </p>
            </div>


            <div class="border rounded p-5">
                <h3>
                    مشتریان
                </h3>

                <p class="text-2xl font-bold">
                    {{ stats.customers }}
                </p>
            </div>


            <div class="border rounded p-5">
                <h3>
                    پیامک‌ها
                </h3>

                <p class="text-2xl font-bold">
                    {{ stats.sms }}
                </p>
            </div>


            <div class="border rounded p-5">
                <h3>
                    تعاملات
                </h3>

                <p class="text-2xl font-bold">
                    {{ stats.interactions }}
                </p>
            </div>

        </div>



        <div class="border rounded p-5">

            <h2 class="text-lg font-bold mb-4">
                آخرین فعالیت‌ها
            </h2>


            <table class="w-full border-collapse border">

                <thead>

                    <tr>

                        <th class="border p-2">
                            مشتری
                        </th>


                        <th class="border p-2">
                            نوع
                        </th>


                        <th class="border p-2">
                            کاربر
                        </th>


                        <th class="border p-2">
                            تاریخ
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr
                        v-for="item in latestInteractions"
                        :key="item.id"
                    >

                        <td class="border p-2">
                            {{ item.contact?.name ?? '-' }}
                        </td>


                        <td class="border p-2">
                            {{ item.type }}
                        </td>


                        <td class="border p-2">
                            {{ item.user?.name ?? '-' }}
                        </td>


                        <td class="border p-2">
                            {{ item.created_at }}
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>
</template>