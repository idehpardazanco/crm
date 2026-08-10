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

const clearSearch = () => {
    search.value = ''

    router.get(
        '/contacts',
        {},
        {
            preserveState: true,
            replace: true,
        }
    )
}

const remove = (id) => {
    if (
        ! confirm(
            'آیا از حذف این مخاطب مطمئن هستید؟'
        )
    ) {
        return
    }

    router.delete(
        `/contacts/${id}`,
        {
            preserveScroll: true,
        }
    )
}
</script>

<template>
    <div
        class="p-6"
        dir="rtl"
    >

        <!-- عنوان و عملیات -->
        <div
            class="
                flex
                flex-col
                md:flex-row
                md:items-center
                justify-between
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
                    مخاطبین
                </h1>

                <p
                    class="
                        text-gray-500
                        mt-1
                    "
                >
                    مدیریت مخاطبین و کسب‌وکارها
                </p>
            </div>


            <div
                class="
                    flex
                    flex-wrap
                    gap-3
                "
            >

                <Link
                    href="/contacts/import"
                    class="
                        bg-green-600
                        hover:bg-green-700
                        text-white
                        px-4
                        py-2
                        rounded
                    "
                >
                    ورود از Excel
                </Link>


                <Link
                    href="/contacts/create"
                    class="
                        bg-blue-600
                        hover:bg-blue-700
                        text-white
                        px-4
                        py-2
                        rounded
                    "
                >
                    مخاطب جدید
                </Link>

            </div>

        </div>


        <!-- جستجو -->
        <div
            class="
                border
                rounded
                p-4
                mb-6
            "
        >

            <form
                @submit.prevent="doSearch"
                class="
                    flex
                    flex-col
                    md:flex-row
                    gap-3
                    md:items-center
                "
            >

                <input
                    v-model="search"
                    type="text"
                    class="
                        border
                        p-2
                        rounded
                        w-full
                        md:max-w-md
                    "
                    placeholder="جستجو نام، موبایل، تلفن یا کسب‌وکار"
                >


                <button
                    type="submit"
                    class="
                        bg-gray-800
                        text-white
                        px-5
                        py-2
                        rounded
                    "
                >
                    جستجو
                </button>


                <button
                    v-if="search"
                    type="button"
                    @click="clearSearch"
                    class="
                        border
                        px-5
                        py-2
                        rounded
                    "
                >
                    پاک کردن
                </button>

            </form>

        </div>


        <!-- جدول مخاطبین -->
        <div
            class="
                border
                rounded
                overflow-hidden
            "
        >

            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        border-collapse
                    "
                >

                    <thead
                        class="
                            bg-gray-50
                        "
                    >

                        <tr>

                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                نام
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                کسب‌وکار
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                موبایل
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                تلفن
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                شهر
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                وضعیت
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                مسئول
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                عملیات
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="contact in contacts.data"
                            :key="contact.id"
                            class="
                                hover:bg-gray-50
                            "
                        >

                            <!-- نام -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                            >

                                <Link
                                    :href="
                                        `/contacts/${contact.id}`
                                    "
                                    class="
                                        text-blue-600
                                        hover:underline
                                        font-medium
                                    "
                                >
                                    {{ contact.name }}
                                </Link>

                            </td>


                            <!-- کسب و کار -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                            >

                                {{
                                    contact.business_name
                                    ?? '-'
                                }}

                            </td>


                            <!-- موبایل -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                                dir="ltr"
                            >
                                {{ contact.mobile }}
                            </td>


                            <!-- تلفن -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                                dir="ltr"
                            >

                                {{
                                    contact.phone
                                    ?? '-'
                                }}

                            </td>


                            <!-- شهر -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                            >

                                {{
                                    contact.city
                                    ?? '-'
                                }}

                            </td>


                            <!-- وضعیت -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                            >

                                <span
                                    class="
                                        inline-block
                                        px-3
                                        py-1
                                        rounded-full
                                        text-sm
                                    "
                                    :class="{
                                        'bg-gray-100 text-gray-700':
                                            contact.status === 'new',

                                        'bg-blue-100 text-blue-700':
                                            contact.status === 'contacted',

                                        'bg-yellow-100 text-yellow-700':
                                            contact.status === 'interested',

                                        'bg-orange-100 text-orange-700':
                                            contact.status === 'follow_up',

                                        'bg-purple-100 text-purple-700':
                                            contact.status === 'demo_sent',

                                        'bg-green-100 text-green-700':
                                            contact.status === 'customer',

                                        'bg-red-100 text-red-700':
                                            contact.status === 'rejected',

                                        'bg-slate-100 text-slate-700':
                                            contact.status === 'no_answer',

                                        'bg-green-100 text-green-700':
                                            contact.status === 'active',

                                        'bg-red-100 text-red-700':
                                            contact.status === 'inactive',
                                    }"
                                >

                                    {{
                                        statusLabels[
                                            contact.status
                                        ]
                                        ?? contact.status
                                    }}

                                </span>

                            </td>


                            <!-- مسئول -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                            >

                                {{
                                    contact.assigned_user
                                        ?.name
                                    ?? '-'
                                }}

                            </td>


                            <!-- عملیات -->
                            <td
                                class="
                                    border-b
                                    p-3
                                "
                            >

                                <div
                                    class="
                                        flex
                                        flex-wrap
                                        gap-3
                                    "
                                >

                                    <Link
                                        :href="
                                            `/contacts/${contact.id}`
                                        "
                                        class="
                                            text-green-600
                                            hover:underline
                                        "
                                    >
                                        مشاهده
                                    </Link>


                                    <Link
                                        :href="
                                            `/contacts/${contact.id}/edit`
                                        "
                                        class="
                                            text-blue-600
                                            hover:underline
                                        "
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
                                        class="
                                            text-red-600
                                            hover:underline
                                        "
                                    >
                                        حذف
                                    </button>

                                </div>

                            </td>

                        </tr>


                        <!-- خالی -->
                        <tr
                            v-if="
                                !contacts.data
                                ||
                                !contacts.data.length
                            "
                        >

                            <td
                                colspan="8"
                                class="
                                    p-8
                                    text-center
                                    text-gray-500
                                "
                            >
                                مخاطبی یافت نشد.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Pagination -->
        <div
            v-if="
                contacts.links
                &&
                contacts.links.length > 3
            "
            class="
                flex
                flex-wrap
                gap-2
                mt-6
            "
        >

            <template
                v-for="link in contacts.links"
                :key="link.label"
            >

                <Link
                    v-if="link.url"
                    :href="link.url"
                    preserve-scroll
                    class="
                        border
                        px-3
                        py-2
                        rounded
                    "
                    :class="{
                        'bg-blue-600 text-white border-blue-600':
                            link.active,
                    }"
                    v-html="link.label"
                />


                <span
                    v-else
                    class="
                        border
                        px-3
                        py-2
                        rounded
                        opacity-40
                    "
                    v-html="link.label"
                ></span>

            </template>

        </div>

    </div>
</template>