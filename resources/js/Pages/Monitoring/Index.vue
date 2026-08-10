<script setup>
import {
    Link,
    router,
} from '@inertiajs/vue3'

import { ref } from 'vue'

const props = defineProps({
    logs: Object,
    users: Array,
    modules: Array,
    filters: Object,
})

const search = ref(
    props.filters?.search ?? ''
)

const moduleFilter = ref(
    props.filters?.module ?? ''
)

const userFilter = ref(
    props.filters?.user_id ?? ''
)

const from = ref(
    props.filters?.from ?? ''
)

const to = ref(
    props.filters?.to ?? ''
)

const expanded = ref(null)

const actionLabels = {
    login: 'ورود به سیستم',
    logout: 'خروج از سیستم',

    user_created: 'ایجاد کاربر',
    user_updated: 'ویرایش کاربر',
    user_deleted: 'حذف کاربر',

    contact_created: 'ایجاد مخاطب',
    contact_updated: 'ویرایش مخاطب',
    contact_deleted: 'حذف مخاطب',

    interaction_created: 'ثبت ارتباط',
    interaction_deleted: 'حذف ارتباط',

    follow_up_created: 'ایجاد پیگیری',
    follow_up_status_updated: 'تغییر وضعیت پیگیری',
    follow_up_deleted: 'حذف پیگیری',

    sms_queued: 'قرار گرفتن پیامک در صف',
    sms_sent: 'ارسال موفق پیامک',
    sms_failed: 'خطا در ارسال پیامک',

    order_created: 'ثبت سفارش',
    order_updated: 'ویرایش سفارش',
    order_deleted: 'حذف سفارش',
}

const applyFilters = () => {
    router.get(
        '/monitoring',
        {
            search: search.value,
            module: moduleFilter.value,
            user_id: userFilter.value,
            from: from.value,
            to: to.value,
        },
        {
            preserveState: true,
            replace: true,
        }
    )
}

const resetFilters = () => {
    search.value = ''
    moduleFilter.value = ''
    userFilter.value = ''
    from.value = ''
    to.value = ''

    router.get('/monitoring')
}

const toggle = (id) => {
    expanded.value =
        expanded.value === id
            ? null
            : id
}

const moduleLabel = (module) => {
    const labels = {
        Auth: 'ورود و خروج',
        Users: 'کاربران',
        Contacts: 'مخاطبین',
        Interactions: 'ارتباطات',
        FollowUps: 'پیگیری‌ها',
        Sms: 'پیامک',
        Orders: 'سفارش‌ها',
    }

    return labels[module] ?? module
}
</script>

<template>
    <div
        class="p-6"
        dir="rtl"
    >

        <div class="mb-6">

            <h1 class="text-2xl font-bold">
                گزارش فعالیت کاربران
            </h1>

            <p class="text-gray-500 mt-2">
                تاریخچه عملیات انجام‌شده در CRM
            </p>

        </div>


        <div
            class="
                border
                rounded
                p-4
                mb-6
            "
        >

            <div
                class="
                    grid
                    grid-cols-1
                    md:grid-cols-2
                    lg:grid-cols-5
                    gap-3
                "
            >

                <input
                    v-model="search"
                    @keyup.enter="
                        applyFilters
                    "
                    type="text"
                    class="
                        border
                        rounded
                        p-2
                    "
                    placeholder="جستجو..."
                >


                <select
                    v-model="moduleFilter"
                    class="
                        border
                        rounded
                        p-2
                    "
                >

                    <option value="">
                        همه بخش‌ها
                    </option>

                    <option
                        v-for="module in modules"
                        :key="module"
                        :value="module"
                    >
                        {{
                            moduleLabel(
                                module
                            )
                        }}
                    </option>

                </select>


                <select
                    v-model="userFilter"
                    class="
                        border
                        rounded
                        p-2
                    "
                >

                    <option value="">
                        همه کاربران
                    </option>

                    <option
                        v-for="user in users"
                        :key="user.id"
                        :value="user.id"
                    >
                        {{ user.name }}
                    </option>

                </select>


                <input
                    v-model="from"
                    type="date"
                    class="
                        border
                        rounded
                        p-2
                    "
                >


                <input
                    v-model="to"
                    type="date"
                    class="
                        border
                        rounded
                        p-2
                    "
                >

            </div>


            <div class="flex gap-3 mt-4">

                <button
                    type="button"
                    @click="applyFilters"
                    class="
                        bg-blue-600
                        text-white
                        px-4
                        py-2
                        rounded
                    "
                >
                    اعمال فیلتر
                </button>

                <button
                    type="button"
                    @click="resetFilters"
                    class="
                        border
                        px-4
                        py-2
                        rounded
                    "
                >
                    حذف فیلترها
                </button>

            </div>

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
                            کاربر
                        </th>

                        <th class="border p-2">
                            عملیات
                        </th>

                        <th class="border p-2">
                            بخش
                        </th>

                        <th class="border p-2">
                            تاریخ
                        </th>

                        <th class="border p-2">
                            جزئیات
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <template
                        v-for="log in logs.data"
                        :key="log.id"
                    >

                        <tr>

                            <td class="border p-2">

                                {{
                                    log.user?.name
                                    ?? 'سیستم'
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    actionLabels[
                                        log.action
                                    ]
                                    ?? log.action
                                }}

                            </td>


                            <td class="border p-2">

                                {{
                                    moduleLabel(
                                        log.module
                                    )
                                }}

                            </td>


                            <td class="border p-2">
                                {{ log.created_at }}
                            </td>


                            <td class="border p-2">

                                <button
                                    type="button"
                                    @click="
                                        toggle(log.id)
                                    "
                                    class="text-blue-600"
                                >
                                    {{
                                        expanded ===
                                        log.id
                                            ? 'بستن'
                                            : 'نمایش'
                                    }}
                                </button>

                            </td>

                        </tr>


                        <tr
                            v-if="
                                expanded ===
                                log.id
                            "
                        >

                            <td
                                colspan="5"
                                class="
                                    border
                                    p-4
                                    bg-gray-50
                                "
                            >

                                <pre
                                    class="
                                        text-sm
                                        whitespace-pre-wrap
                                        break-words
                                        text-left
                                    "
                                    dir="ltr"
                                >{{ JSON.stringify(log.meta ?? {}, null, 2) }}</pre>

                            </td>

                        </tr>

                    </template>


                    <tr
                        v-if="!logs.data.length"
                    >

                        <td
                            colspan="5"
                            class="
                                border
                                p-6
                                text-center
                            "
                        >
                            فعالیتی یافت نشد
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>


        <div
            v-if="
                logs.links
                &&
                logs.links.length > 3
            "
            class="
                flex
                flex-wrap
                gap-2
                mt-6
            "
        >

            <template
                v-for="link in logs.links"
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
                        'bg-blue-600 text-white':
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