<script setup>
import { Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
})

const search = ref('')
const selectedUser = ref(null)
const deleting = ref(false)

const totalUsers = computed(() => {
    return props.users?.total ?? props.users?.data?.length ?? 0
})

const activeUsers = computed(() => {
    return (props.users?.data ?? []).filter(
        user => user.status === 'active'
    ).length
})

function doSearch() {
    router.get(
        '/users',
        {
            search: search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

function clearSearch() {
    search.value = ''

    router.get(
        '/users',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

function confirmDelete() {
    if (!selectedUser.value || deleting.value) {
        return
    }

    deleting.value = true

    router.delete(
        `/users/${selectedUser.value.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {
                selectedUser.value = null
            },

            onFinish: () => {
                deleting.value = false
            },
        }
    )
}
</script>

<template>
    <div
        class="mx-auto w-full max-w-[1450px] pb-8"
        dir="rtl"
    >
        <section
            class="mb-5 flex flex-col gap-5 rounded-[20px] border border-slate-200 bg-gradient-to-bl from-white to-blue-50/40 p-5 shadow-sm md:flex-row md:items-center md:justify-between md:p-6"
        >
            <div class="flex items-center gap-4">
                <div
                    class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-blue-500 text-white shadow-lg shadow-blue-200"
                >
                    <svg
                        class="h-7 w-7"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M16 21V19C16 16.7909 14.2091 15 12 15H6C3.79086 15 2 16.7909 2 19V21M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11ZM17 8H22M19.5 5.5V10.5"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <div class="text-xs font-extrabold text-blue-600">
                        کنترل دسترسی
                    </div>

                    <h1 class="mt-1 text-2xl font-black text-slate-900">
                        مدیریت کاربران
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        ایجاد حساب و مدیریت وضعیت همکاران
                    </p>
                </div>
            </div>

            <Link
                href="/users/create"
                class="flex min-h-12 items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-blue-700 px-5 text-sm font-extrabold text-white shadow-lg shadow-blue-100"
            >
                <span class="text-xl">+</span>
                کاربر جدید
            </Link>
        </section>

        <section class="mb-5 grid gap-4 md:grid-cols-2">
            <div
                class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <span class="text-sm text-slate-500">
                    کل کاربران
                </span>

                <strong class="text-2xl font-black text-slate-900">
                    {{ totalUsers }}
                </strong>
            </div>

            <div
                class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
            >
                <span class="text-sm text-slate-500">
                    کاربران فعال در این صفحه
                </span>

                <strong class="text-2xl font-black text-emerald-600">
                    {{ activeUsers }}
                </strong>
            </div>
        </section>

        <section
            class="mb-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
        >
            <form
                class="flex flex-col gap-3 md:flex-row"
                @submit.prevent="doSearch"
            >
                <input
                    v-model="search"
                    type="search"
                    placeholder="جستجوی نام، شماره موبایل یا ایمیل"
                    class="min-w-0 flex-1"
                >

                <button
                    type="submit"
                    class="min-h-11 rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white"
                >
                    جستجو
                </button>

                <button
                    v-if="search"
                    type="button"
                    class="min-h-11 rounded-xl bg-slate-100 px-5 text-sm font-bold text-slate-500"
                    @click="clearSearch"
                >
                    پاک کردن
                </button>
            </form>
        </section>

        <section
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
        >
            <div class="overflow-x-auto">
                <table class="min-w-[850px]">
                    <thead>
                        <tr>
                            <th>کاربر</th>
                            <th>موبایل</th>
                            <th>ایمیل</th>
                            <th>نقش</th>
                            <th>وضعیت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="user in users.data"
                            :key="user.id"
                        >
                            <td>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 font-black text-blue-600"
                                    >
                                        {{
                                            user.name
                                                ?.trim()
                                                .substring(0, 1) ?? '؟'
                                        }}
                                    </span>

                                    <strong class="text-slate-800">
                                        {{ user.name }}
                                    </strong>
                                </div>
                            </td>

                            <td>
                                <span dir="ltr">
                                    {{ user.mobile }}
                                </span>
                            </td>

                            <td>
                                <span dir="ltr">
                                    {{ user.email ?? '-' }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="inline-flex rounded-lg bg-violet-50 px-3 py-1.5 text-xs font-extrabold text-violet-600"
                                >
                                    کارمند
                                </span>
                            </td>

                            <td>
                                <span
                                    class="inline-flex rounded-lg px-3 py-1.5 text-xs font-extrabold"
                                    :class="
                                        user.status === 'active'
                                            ? 'bg-emerald-50 text-emerald-600'
                                            : 'bg-red-50 text-red-600'
                                    "
                                >
                                    {{
                                        user.status === 'active'
                                            ? 'فعال'
                                            : 'غیرفعال'
                                    }}
                                </span>
                            </td>

                            <td>
                                <div class="flex items-center gap-4">
                                    <Link
                                        :href="`/users/${user.id}/edit`"
                                        class="font-bold text-blue-600"
                                    >
                                        ویرایش
                                    </Link>

                                    <button
                                        type="button"
                                        class="font-bold text-red-600"
                                        @click="selectedUser = user"
                                    >
                                        حذف
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr v-if="!users.data?.length">
                            <td
                                colspan="6"
                                class="py-14 text-center"
                            >
                                <strong class="block text-slate-700">
                                    کاربری یافت نشد
                                </strong>

                                <span
                                    class="mt-2 block text-xs text-slate-400"
                                >
                                    عبارت جستجو را تغییر دهید یا کاربر جدید
                                    ایجاد کنید.
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <div
            v-if="users.links && users.links.length > 3"
            class="mt-5 flex flex-col gap-4 text-sm text-slate-500 md:flex-row md:items-center md:justify-between"
        >
            <div>
                صفحه
                <strong>{{ users.current_page ?? 1 }}</strong>
                از
                <strong>{{ users.last_page ?? 1 }}</strong>
            </div>

            <div class="flex flex-wrap gap-2">
                <template
                    v-for="link in users.links"
                    :key="`${link.label}-${link.url}`"
                >
                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-scroll
                        class="flex min-h-10 min-w-10 items-center justify-center rounded-xl border px-3"
                        :class="
                            link.active
                                ? 'border-blue-600 bg-blue-600 text-white'
                                : 'border-slate-200 bg-white text-slate-500'
                        "
                        v-html="link.label"
                    />

                    <span
                        v-else
                        class="flex min-h-10 min-w-10 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-3 text-slate-300"
                        v-html="link.label"
                    ></span>
                </template>
            </div>
        </div>

        <Teleport to="body">
            <div
                v-if="selectedUser"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/50 p-5 backdrop-blur-sm"
                dir="rtl"
                @click.self="!deleting && (selectedUser = null)"
            >
                <div
                    class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
                >
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-xl font-black text-red-600"
                    >
                        !
                    </div>

                    <h3 class="mt-5 text-lg font-black text-slate-900">
                        حذف کاربر؟
                    </h3>

                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        آیا از حذف کاربر
                        <strong>«{{ selectedUser.name }}»</strong>
                        مطمئن هستید؟
                    </p>

                    <p class="mt-2 text-xs text-slate-400">
                        این عملیات قابل بازگشت نیست.
                    </p>

                    <div class="mt-6 flex gap-3">
                        <button
                            type="button"
                            :disabled="deleting"
                            class="min-h-11 flex-1 rounded-xl border border-slate-200 font-bold text-slate-600"
                            @click="selectedUser = null"
                        >
                            انصراف
                        </button>

                        <button
                            type="button"
                            :disabled="deleting"
                            class="min-h-11 flex-1 rounded-xl bg-red-600 font-bold text-white disabled:opacity-50"
                            @click="confirmDelete"
                        >
                            {{
                                deleting
                                    ? 'در حال حذف...'
                                    : 'بله، حذف شود'
                            }}
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>