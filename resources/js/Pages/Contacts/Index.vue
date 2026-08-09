<script setup>
import {
    Link,
    router,
} from '@inertiajs/vue3'

import { ref } from 'vue'

const props = defineProps({
    contacts: Object,
    filters: Object,
    isAdmin: Boolean,
})

const search = ref(
    props.filters?.search ?? ''
)

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

const doSearch = () => {
    router.get(
        '/contacts',
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
    if (
        !confirm(
            'مخاطب حذف شود؟'
        )
    ) {
        return
    }

    router.delete(
        `/contacts/${id}`
    )
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
                مخاطبین
            </h1>

            <Link
                href="/contacts/create"
                class="
                    bg-blue-600
                    text-white
                    px-4
                    py-2
                    rounded
                "
            >
                مخاطب جدید
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
                            نام
                        </th>

                        <th class="border p-2">
                            موبایل
                        </th>

                        <th class="border p-2">
                            کسب‌وکار
                        </th>

                        <th class="border p-2">
                            شهر
                        </th>

                        <th class="border p-2">
                            وضعیت
                        </th>

                        <th class="border p-2">
                            مسئول
                        </th>

                        <th class="border p-2">
                            عملیات
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <tr
                        v-for="contact in contacts.data"
                        :key="contact.id"
                    >

                        <td class="border p-2">

                            <Link
                                :href="
                                    `/contacts/${contact.id}`
                                "
                                class="text-blue-600"
                            >
                                {{ contact.name }}
                            </Link>

                        </td>


                        <td class="border p-2">
                            {{ contact.mobile }}
                        </td>


                        <td class="border p-2">

                            {{
                                contact.business_name
                                    ?? '-'
                            }}

                        </td>


                        <td class="border p-2">
                            {{ contact.city ?? '-' }}
                        </td>


                        <td class="border p-2">

                            {{
                                statusLabels[
                                    contact.status
                                ]
                                    ?? contact.status
                            }}

                        </td>


                        <td class="border p-2">

                            {{
                                contact.assigned_user
                                    ?.name
                                    ?? '-'
                            }}

                        </td>


                        <td class="border p-2">

                            <div class="flex gap-3">

                                <Link
                                    :href="
                                        `/contacts/${contact.id}`
                                    "
                                    class="text-green-600"
                                >
                                    مشاهده
                                </Link>

                                <Link
                                    :href="
                                        `/contacts/${contact.id}/edit`
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
                                            contact.id
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
                        v-if="
                            !contacts.data.length
                        "
                    >

                        <td
                            colspan="7"
                            class="
                                border
                                p-4
                                text-center
                            "
                        >
                            مخاطبی یافت نشد
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>
</template>