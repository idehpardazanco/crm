<script setup>
import {
    Link,
    router,
    usePage,
} from '@inertiajs/vue3'

import {
    computed,
    ref,
} from 'vue'

const page = usePage()
const sidebarOpen = ref(false)

const user = computed(() => {
    return page.props.auth?.user ?? null
})

const isSuperAdmin = computed(() => {
    return Boolean(
        page.props.auth?.isSuperAdmin
    )
})

const currentUrl = computed(() => {
    return page.url
})

const publicNav = [
    {
        title: 'داشبورد',
        href: '/dashboard',
        icon: 'dashboard',
    },
    {
        title: 'مخاطبین',
        href: '/contacts',
        icon: 'contacts',
    },
    {
        title: 'پیگیری‌ها',
        href: '/followups',
        icon: 'followups',
    },
    {
        title: 'سفارش‌ها',
        href: '/orders',
        icon: 'orders',
    },
    {
        title: 'گزارش پیامک‌ها',
        href: '/sms/logs',
        icon: 'sms',
    },
]

const adminNav = [
    {
        title: 'گزارش‌ها',
        href: '/reports',
        icon: 'reports',
    },
    {
        title: 'مانیتورینگ',
        href: '/monitoring',
        icon: 'monitoring',
    },
    {
        title: 'مدیریت کاربران',
        href: '/users',
        icon: 'users',
    },
    {
        title: 'قالب‌های پیامک',
        href: '/sms/templates',
        icon: 'templates',
    },
    {
        title: 'تنظیمات پیامک',
        href: '/sms/settings',
        icon: 'settings',
    },
]

function isActive(href) {
    if (href === '/dashboard') {
        return currentUrl.value === '/dashboard'
    }

    return currentUrl.value.startsWith(href)
}

const pageTitle = computed(() => {
    const url = currentUrl.value

    if (url.startsWith('/contacts')) {
        return 'مخاطبین'
    }

    if (url.startsWith('/followups')) {
        return 'پیگیری‌ها'
    }

    if (url.startsWith('/orders')) {
        return 'سفارش‌ها'
    }

    if (url.startsWith('/users')) {
        return 'مدیریت کاربران'
    }

    if (url.startsWith('/sms/templates')) {
        return 'قالب‌های پیامک'
    }

    if (url.startsWith('/sms/settings')) {
        return 'تنظیمات پیامک'
    }

    if (url.startsWith('/sms')) {
        return 'پیامک‌ها'
    }

    if (url.startsWith('/monitoring')) {
        return 'مانیتورینگ'
    }

    if (url.startsWith('/reports')) {
        return 'گزارش‌ها'
    }

    if (url.startsWith('/profile')) {
        return 'پروفایل کاربری'
    }

    return 'داشبورد'
})

function closeSidebar() {
    sidebarOpen.value = false
}

function logout() {
    router.post('/logout')
}
</script>

<template>
    <div
        class="crm-shell flex min-h-screen"
        dir="rtl"
    >
        <!-- Mobile overlay -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-40 bg-slate-950/50 backdrop-blur-sm md:hidden"
            @click="closeSidebar"
        ></div>

        <!-- Sidebar -->
        <aside
            class="crm-sidebar fixed inset-y-0 right-0 z-50 flex w-[270px] flex-col text-white transition-transform duration-200 md:translate-x-0"
            :class="
                sidebarOpen
                    ? 'translate-x-0'
                    : 'translate-x-full md:translate-x-0'
            "
        >
            <!-- Brand -->
            <div
                class="flex h-[78px] shrink-0 items-center border-b border-white/10 px-6"
            >
                <div
                    class="ml-3 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500 text-sm font-black shadow-lg shadow-blue-950/30"
                >
                    CRM
                </div>

                <div class="min-w-0">
                    <div
                        class="truncate text-base font-bold"
                    >
                        CRM ایده‌پردازان
                    </div>

                    <div
                        class="mt-1 truncate text-[11px] text-blue-100/70"
                    >
                        مدیریت ارتباط با مشتری
                    </div>
                </div>

                <button
                    type="button"
                    class="mr-auto flex h-9 w-9 items-center justify-center rounded-lg text-xl text-blue-100/70 transition hover:bg-white/10 hover:text-white md:hidden"
                    aria-label="بستن منو"
                    @click="closeSidebar"
                >
                    ×
                </button>
            </div>

            <!-- Navigation -->
            <div
                class="flex-1 overflow-y-auto px-4 py-5"
            >
                <div
                    class="mb-2 px-3 text-[11px] font-bold tracking-wider text-blue-100/50"
                >
                    عملیات
                </div>

                <nav class="space-y-1">
                    <Link
                        v-for="item in publicNav"
                        :key="item.href"
                        :href="item.href"
                        class="group flex min-h-11 items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition"
                        :class="
                            isActive(item.href)
                                ? 'bg-blue-500 text-white shadow-lg shadow-blue-950/20'
                                : 'text-blue-50/80 hover:bg-white/10 hover:text-white'
                        "
                        @click="closeSidebar"
                    >
                        <span
                            class="h-2 w-2 shrink-0 rounded-full transition"
                            :class="
                                isActive(item.href)
                                    ? 'bg-white'
                                    : 'bg-blue-300/50 group-hover:bg-blue-300'
                            "
                        ></span>

                        {{ item.title }}
                    </Link>
                </nav>

                <template v-if="isSuperAdmin">
                    <div
                        class="mb-2 mt-7 px-3 text-[11px] font-bold tracking-wider text-blue-100/50"
                    >
                        مدیریت
                    </div>

                    <nav class="space-y-1">
                        <Link
                            v-for="item in adminNav"
                            :key="item.href"
                            :href="item.href"
                            class="group flex min-h-11 items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition"
                            :class="
                                isActive(item.href)
                                    ? 'bg-blue-500 text-white shadow-lg shadow-blue-950/20'
                                    : 'text-blue-50/80 hover:bg-white/10 hover:text-white'
                            "
                            @click="closeSidebar"
                        >
                            <span
                                class="h-2 w-2 shrink-0 rounded-full transition"
                                :class="
                                    isActive(item.href)
                                        ? 'bg-white'
                                        : 'bg-blue-300/50 group-hover:bg-blue-300'
                                "
                            ></span>

                            {{ item.title }}
                        </Link>
                    </nav>
                </template>
            </div>

            <!-- User -->
            <div
                class="shrink-0 border-t border-white/10 p-4"
            >
                <div
                    class="mb-3 rounded-xl bg-white/5 px-4 py-3"
                >
                    <div
                        class="truncate text-sm font-bold text-white"
                    >
                        {{
                            user?.name
                            ?? 'کاربر سیستم'
                        }}
                    </div>

                    <div
                        class="mt-1 text-xs text-blue-100/60"
                    >
                        {{
                            isSuperAdmin
                                ? 'مدیر سیستم'
                                : 'کارمند'
                        }}
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2">
                    <Link
                        href="/profile"
                        class="flex min-h-10 items-center justify-center rounded-xl border border-white/10 px-3 text-sm text-blue-50/80 transition hover:bg-white/10 hover:text-white"
                        :class="
                            isActive('/profile')
                                ? 'bg-white/10 text-white'
                                : ''
                        "
                        @click="closeSidebar"
                    >
                        پروفایل
                    </Link>

                    <button
                        type="button"
                        class="min-h-10 rounded-xl border border-white/10 px-3 text-sm text-blue-50/80 transition hover:bg-red-500/20 hover:text-white"
                        @click="logout"
                    >
                        خروج
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main -->
        <div
            class="crm-main flex min-h-screen min-w-0 flex-1 flex-col md:mr-[270px]"
        >
            <!-- Topbar -->
            <header
                class="crm-topbar sticky top-0 z-30 flex h-[78px] shrink-0 items-center justify-between px-4 sm:px-5 md:px-8"
            >
                <div
                    class="flex min-w-0 items-center gap-3"
                >
                    <button
                        type="button"
                        class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white text-xl text-slate-700 shadow-sm md:hidden"
                        aria-label="بازکردن منو"
                        @click="sidebarOpen = true"
                    >
                        ☰
                    </button>

                    <div class="min-w-0">
                        <h2
                            class="truncate text-lg font-extrabold text-slate-900"
                        >
                            {{ pageTitle }}
                        </h2>

                        <div
                            class="mt-1 hidden text-xs text-slate-400 sm:block"
                        >
                            سامانه مدیریت ارتباط با مشتریان
                        </div>
                    </div>
                </div>

                <Link
                    href="/profile"
                    class="hidden items-center gap-3 rounded-xl px-2 py-1.5 transition hover:bg-slate-50 sm:flex"
                >
                    <div
                        class="max-w-[180px] truncate text-sm text-slate-500"
                    >
                        {{ user?.name }}
                    </div>

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 font-bold text-blue-600"
                    >
                        {{
                            user?.name
                                ? user.name
                                    .trim()
                                    .substring(0, 1)
                                : 'U'
                        }}
                    </div>
                </Link>
            </header>

            <!-- Content -->
            <main
                class="crm-content min-w-0 flex-1 overflow-x-hidden p-4 sm:p-5 md:p-8"
            >
                <div
                    v-if="page.props.flash?.success"
                    class="crm-flash crm-flash-success"
                >
                    {{ page.props.flash.success }}
                </div>

                <div
                    v-if="page.props.flash?.error"
                    class="crm-flash crm-flash-error"
                >
                    {{ page.props.flash.error }}
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>