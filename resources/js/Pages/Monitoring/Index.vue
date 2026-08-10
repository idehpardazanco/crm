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

/*
|--------------------------------------------------------------------------
| عنوان عملیات‌ها
|--------------------------------------------------------------------------
*/

const actionLabels = {
    /*
     * ورود و خروج
     */
    login: 'ورود به سیستم',
    logout: 'خروج از سیستم',

    /*
     * کاربران
     */
    user_created: 'ایجاد کاربر',
    user_updated: 'ویرایش کاربر',
    user_deleted: 'حذف کاربر',

    /*
     * مخاطبین
     */
    contact_created: 'ایجاد مخاطب',
    contact_updated: 'ویرایش مخاطب',
    contact_deleted: 'حذف مخاطب',

    contacts_imported:
        'ورود مخاطبین از Excel',

    contacts_exported:
        'خروجی Excel مخاطبین',

    /*
     * ارتباطات
     */
    interaction_created:
        'ثبت ارتباط',

    interaction_deleted:
        'حذف ارتباط',

    /*
     * پیگیری‌ها
     */
    follow_up_created:
        'ایجاد پیگیری',

    follow_up_status_updated:
        'تغییر وضعیت پیگیری',

    follow_up_deleted:
        'حذف پیگیری',

    /*
     * پیامک
     */
    sms_queued:
        'قرار گرفتن پیامک در صف',

    sms_sent:
        'ارسال موفق پیامک',

    sms_failed:
        'خطا در ارسال پیامک',

    /*
     * سفارش‌ها
     */
    order_created:
        'ثبت سفارش',

    order_updated:
        'ویرایش سفارش',

    order_deleted:
        'حذف سفارش',
}

/*
|--------------------------------------------------------------------------
| نام فارسی ماژول‌ها
|--------------------------------------------------------------------------
*/

const moduleLabels = {
    Auth: 'ورود و خروج',
    Users: 'کاربران',
    Contacts: 'مخاطبین',
    Interactions: 'ارتباطات',
    FollowUps: 'پیگیری‌ها',
    Sms: 'پیامک',
    Orders: 'سفارش‌ها',
}

/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

const applyFilters = () => {
    router.get(
        '/monitoring',
        {
            search:
                search.value,

            module:
                moduleFilter.value,

            user_id:
                userFilter.value,

            from:
                from.value,

            to:
                to.value,
        },
        {
            preserveState: true,
            replace: true,
            preserveScroll: true,
        }
    )
}

const resetFilters = () => {
    search.value = ''

    moduleFilter.value = ''

    userFilter.value = ''

    from.value = ''

    to.value = ''

    router.get(
        '/monitoring',
        {},
        {
            replace: true,
        }
    )
}

/*
|--------------------------------------------------------------------------
| نمایش جزئیات
|--------------------------------------------------------------------------
*/

const toggle = (id) => {
    expanded.value =
        expanded.value === id
            ? null
            : id
}

/*
|--------------------------------------------------------------------------
| Labels
|--------------------------------------------------------------------------
*/

const actionLabel = (action) => {
    return actionLabels[action]
        ?? action
        ?? '-'
}

const moduleLabel = (module) => {
    return moduleLabels[module]
        ?? module
        ?? '-'
}

/*
|--------------------------------------------------------------------------
| Meta
|--------------------------------------------------------------------------
*/

const metaText = (meta) => {
    if (!meta) {
        return '{}'
    }

    try {
        return JSON.stringify(
            meta,
            null,
            2
        )
    } catch {
        return '{}'
    }
}
</script>


<template>
    <div
        class="p-6"
        dir="rtl"
    >

        <!-- Header -->
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
                    گزارش فعالیت کاربران
                </h1>

                <p
                    class="
                        text-gray-500
                        mt-2
                    "
                >
                    مشاهده و بررسی فعالیت‌های انجام‌شده در CRM
                </p>

            </div>

        </div>


        <!-- Filters -->
        <div
            class="
                border
                rounded
                p-5
                mb-6
            "
        >

            <h2
                class="
                    font-bold
                    mb-4
                "
            >
                فیلتر گزارش‌ها
            </h2>


            <form
                @submit.prevent="
                    applyFilters
                "
            >

                <div
                    class="
                        grid
                        grid-cols-1
                        md:grid-cols-2
                        lg:grid-cols-5
                        gap-4
                    "
                >

                    <!-- Search -->
                    <div>

                        <label
                            class="
                                block
                                text-sm
                                font-medium
                                mb-2
                            "
                        >
                            جستجو
                        </label>

                        <input
                            v-model="search"
                            type="text"
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                            placeholder="عملیات، بخش یا کاربر"
                        >

                    </div>


                    <!-- Module -->
                    <div>

                        <label
                            class="
                                block
                                text-sm
                                font-medium
                                mb-2
                            "
                        >
                            بخش
                        </label>

                        <select
                            v-model="
                                moduleFilter
                            "
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                            <option value="">
                                همه بخش‌ها
                            </option>

                            <option
                                v-for="
                                    module in
                                    modules
                                "
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

                    </div>


                    <!-- User -->
                    <div>

                        <label
                            class="
                                block
                                text-sm
                                font-medium
                                mb-2
                            "
                        >
                            کاربر
                        </label>

                        <select
                            v-model="
                                userFilter
                            "
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                            <option value="">
                                همه کاربران
                            </option>

                            <option
                                v-for="
                                    user in users
                                "
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>

                        </select>

                    </div>


                    <!-- From -->
                    <div>

                        <label
                            class="
                                block
                                text-sm
                                font-medium
                                mb-2
                            "
                        >
                            از تاریخ
                        </label>

                        <input
                            v-model="from"
                            type="date"
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                    </div>


                    <!-- To -->
                    <div>

                        <label
                            class="
                                block
                                text-sm
                                font-medium
                                mb-2
                            "
                        >
                            تا تاریخ
                        </label>

                        <input
                            v-model="to"
                            type="date"
                            class="
                                border
                                rounded
                                p-2
                                w-full
                            "
                        >

                    </div>

                </div>


                <div
                    class="
                        flex
                        flex-wrap
                        gap-3
                        mt-5
                    "
                >

                    <button
                        type="submit"
                        class="
                            bg-blue-600
                            hover:bg-blue-700
                            text-white
                            px-5
                            py-2
                            rounded
                        "
                    >
                        اعمال فیلتر
                    </button>


                    <button
                        type="button"
                        @click="
                            resetFilters
                        "
                        class="
                            border
                            px-5
                            py-2
                            rounded
                        "
                    >
                        حذف فیلترها
                    </button>

                </div>

            </form>

        </div>


        <!-- Result Count -->
        <div
            class="
                text-sm
                text-gray-500
                mb-3
            "
        >

            تعداد گزارش‌ها:

            <strong>
                {{
                    logs.total
                    ?? logs.data?.length
                    ?? 0
                }}
            </strong>

        </div>


        <!-- Logs Table -->
        <div
            class="
                border
                rounded
                overflow-hidden
            "
        >

            <div
                class="
                    overflow-x-auto
                "
            >

                <table
                    class="
                        w-full
                        border-collapse
                    "
                >

                    <thead
                        class="bg-gray-50"
                    >

                        <tr>

                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                کاربر
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


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                بخش
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                تاریخ
                            </th>


                            <th
                                class="
                                    border-b
                                    p-3
                                    text-right
                                "
                            >
                                جزئیات
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <template
                            v-for="
                                log in logs.data
                            "
                            :key="log.id"
                        >

                            <!-- Main Row -->
                            <tr
                                class="
                                    hover:bg-gray-50
                                "
                            >

                                <!-- User -->
                                <td
                                    class="
                                        border-b
                                        p-3
                                    "
                                >

                                    <span
                                        v-if="
                                            log.user
                                        "
                                        class="
                                            font-medium
                                        "
                                    >
                                        {{
                                            log.user.name
                                        }}
                                    </span>

                                    <span
                                        v-else
                                        class="
                                            text-gray-500
                                        "
                                    >
                                        سیستم
                                    </span>

                                </td>


                                <!-- Action -->
                                <td
                                    class="
                                        border-b
                                        p-3
                                    "
                                >

                                    <span
                                        class="
                                            inline-block
                                            bg-blue-50
                                            text-blue-700
                                            px-3
                                            py-1
                                            rounded
                                            text-sm
                                        "
                                    >
                                        {{
                                            actionLabel(
                                                log.action
                                            )
                                        }}
                                    </span>

                                </td>


                                <!-- Module -->
                                <td
                                    class="
                                        border-b
                                        p-3
                                    "
                                >

                                    {{
                                        moduleLabel(
                                            log.module
                                        )
                                    }}

                                </td>


                                <!-- Created At -->
                                <td
                                    class="
                                        border-b
                                        p-3
                                        whitespace-nowrap
                                    "
                                >

                                    {{
                                        log.created_at
                                    }}

                                </td>


                                <!-- Details -->
                                <td
                                    class="
                                        border-b
                                        p-3
                                    "
                                >

                                    <button
                                        type="button"
                                        @click="
                                            toggle(
                                                log.id
                                            )
                                        "
                                        class="
                                            text-blue-600
                                            hover:underline
                                        "
                                    >

                                        {{
                                            expanded ===
                                            log.id
                                                ? 'بستن جزئیات'
                                                : 'نمایش جزئیات'
                                        }}

                                    </button>

                                </td>

                            </tr>


                            <!-- Meta Row -->
                            <tr
                                v-if="
                                    expanded ===
                                    log.id
                                "
                            >

                                <td
                                    colspan="5"
                                    class="
                                        border-b
                                        p-4
                                        bg-gray-50
                                    "
                                >

                                    <div
                                        class="
                                            mb-2
                                            font-medium
                                        "
                                    >
                                        اطلاعات ثبت‌شده:
                                    </div>


                                    <pre
                                        class="
                                            bg-white
                                            border
                                            rounded
                                            p-4
                                            text-sm
                                            whitespace-pre-wrap
                                            break-words
                                            overflow-x-auto
                                        "
                                        dir="ltr"
                                    >{{ metaText(log.meta) }}</pre>

                                </td>

                            </tr>

                        </template>


                        <!-- Empty -->
                        <tr
                            v-if="
                                !logs.data
                                ||
                                !logs.data.length
                            "
                        >

                            <td
                                colspan="5"
                                class="
                                    p-8
                                    text-center
                                    text-gray-500
                                "
                            >
                                هیچ فعالیتی با این فیلترها یافت نشد.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- Pagination -->
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
                v-for="
                    link in logs.links
                "
                :key="link.label"
            >

                <Link
                    v-if="
                        link.url
                    "
                    :href="
                        link.url
                    "
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
                    v-html="
                        link.label
                    "
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
                    v-html="
                        link.label
                    "
                ></span>

            </template>

        </div>

    </div>
</template>