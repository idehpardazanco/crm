<script setup>
import { Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    followUps: Object,
    filters: Object,
    isAdmin: Boolean,
})

const search = ref(
    props.filters?.search ?? ''
)

const statusLabels = {
    pending: 'در انتظار',
    done: 'انجام شده',
    cancelled: 'لغو شده',
}

const doSearch = () => {
    router.get(
        '/followups',
        {
            search: search.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}

const updateStatus = (
    id,
    status
) => {
    router.patch(
        `/followups/${id}/status`,
        {
            status,
        },
        {
            preserveScroll: true,
        }
    )
}

const remove = (id) => {
    if (!confirm('پیگیری حذف شود؟')) {
        return
    }

    router.delete(
        `/followups/${id}`,
        {
            preserveScroll: true,
        }
    )
}

const isOverdue = (item) => {
    if (item.status !== 'pending') {
        return false
    }

    return new Date(item.follow_up_at) <
        new Date()
}
</script>

<template>
    <div class="p-6">

        <div
            class="
                flex
                justify-between
                items-center
                mb-6
            "
        >

            <h1 class="text-xl font-bold">
                پیگیری‌ها
            </h1>

            <Link
                href="/followups/create"
                class="
                    bg-blue-600
                    text-white
                    px-4
                    py-2
                    rounded
                "
            >
                پیگیری جدید
            </Link>

        </div>


        <div class="mb-5">

            <input
                v-model="search"
                @keyup.enter="doSearch"
                type="text"
                class="
                    border
                    p-2
                    rounded
                    w-full
                    max-w-md
                "
                placeholder="جستجو نام، موبایل یا کسب‌وکار"
            >

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
                            تاریخ پیگیری
                        </th>

                        <th class="border p-2">
                            وضعیت
                        </th>

                        <th
                            v-if="isAdmin"
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
                        v-for="item in followUps.data"
                        :key="item.id"
                        :class="{
                            'bg-red-50':
                                isOverdue(item),
                        }"
                    >

                        <td class="border p-2">

                            <Link
                                v-if="item.contact"
                                :href="
                                    `/contacts/${item.contact.id}`
                                "
                                class="text-blue-600"
                            >
                                {{ item.contact.name }}
                            </Link>

                            <span v-else>
                                -
                            </span>

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
                            class="border p-2"
                            :class="{
                                'text-red-600 font-bold':
                                    isOverdue(item),
                            }"
                        >
                            {{ item.follow_up_at }}
                        </td>


                        <td class="border p-2">

                            {{
                                statusLabels[
                                    item.status
                                ] ?? item.status
                            }}

                        </td>


                        <td
                            v-if="isAdmin"
                            class="border p-2"
                        >
                            {{
                                item.user?.name
                                    ?? '-'
                            }}
                        </td>


                        <td class="border p-2">

                            <div
                                class="
                                    flex
                                    flex-wrap
                                    gap-3
                                "
                            >

                                <button
                                    v-if="
                                        item.status !==
                                        'done'
                                    "
                                    type="button"
                                    @click="
                                        updateStatus(
                                            item.id,
                                            'done'
                                        )
                                    "
                                    class="
                                        text-green-600
                                    "
                                >
                                    انجام شد
                                </button>


                                <button
                                    v-if="
                                        item.status !==
                                        'cancelled'
                                    "
                                    type="button"
                                    @click="
                                        updateStatus(
                                            item.id,
                                            'cancelled'
                                        )
                                    "
                                    class="
                                        text-orange-600
                                    "
                                >
                                    لغو
                                </button>


                                <button
                                    v-if="
                                        item.status !==
                                        'pending'
                                    "
                                    type="button"
                                    @click="
                                        updateStatus(
                                            item.id,
                                            'pending'
                                        )
                                    "
                                    class="
                                        text-blue-600
                                    "
                                >
                                    بازگردانی
                                </button>


                                <button
                                    type="button"
                                    @click="
                                        remove(item.id)
                                    "
                                    class="text-red-600"
                                >
                                    حذف
                                </button>

                            </div>

                        </td>

                    </tr>


                    <tr
                        v-if="
                            !followUps.data.length
                        "
                    >

                        <td
                            :colspan="
                                isAdmin ? 8 : 7
                            "
                            class="
                                border
                                p-4
                                text-center
                            "
                        >
                            موردی یافت نشد
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>
</template>