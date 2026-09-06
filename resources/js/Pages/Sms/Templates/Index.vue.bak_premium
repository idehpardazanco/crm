<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    templates: Object,
    filters: Object,
})

const search = ref(
    props.filters?.search ?? ''
)

const doSearch = () => {
    router.get(
        '/sms/templates',
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
    if (!confirm('قالب پیامک حذف شود؟')) {
        return
    }

    router.delete(
        `/sms/templates/${id}`
    )
}
</script>

<template>
    <div class="p-6">

        <div class="flex justify-between items-center mb-6">

            <h1 class="text-xl font-bold">
                قالب‌های پیامک
            </h1>

            <Link
                href="/sms/templates/create"
                class="bg-green-600 text-white px-4 py-2 rounded"
            >
                قالب جدید
            </Link>

        </div>

        <div class="mb-5">
            <input
                v-model="search"
                @keyup.enter="doSearch"
                type="text"
                class="border p-2 rounded w-full max-w-md"
                placeholder="جستجوی قالب پیامک"
            >
        </div>

        <table class="w-full border-collapse border">

            <thead>
                <tr>
                    <th class="border p-2">
                        عنوان
                    </th>

                    <th class="border p-2">
                        نوع
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
                    v-for="item in templates.data"
                    :key="item.id"
                >
                    <td class="border p-2">
                        {{ item.title }}
                    </td>

                    <td class="border p-2">
                        {{ item.type ?? '-' }}
                    </td>

                    <td class="border p-2">
                        {{
                            item.status === 'active'
                                ? 'فعال'
                                : 'غیرفعال'
                        }}
                    </td>

                    <td class="border p-2">

                        <Link
                            :href="`/sms/templates/${item.id}/edit`"
                            class="text-blue-600 ml-4"
                        >
                            ویرایش
                        </Link>

                        <button
                            type="button"
                            @click="remove(item.id)"
                            class="text-red-600"
                        >
                            حذف
                        </button>

                    </td>
                </tr>

                <tr v-if="!templates.data.length">
                    <td
                        colspan="4"
                        class="border p-4 text-center"
                    >
                        قالبی وجود ندارد
                    </td>
                </tr>

            </tbody>

        </table>

    </div>
</template>