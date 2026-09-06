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


const user = computed(
    () => page.props.auth?.user ?? null
)


const isSuperAdmin = computed(
    () => Boolean(
        page.props.auth?.isSuperAdmin
    )
)


const currentUrl = computed(
    () => page.url
)


const publicNav = [

    {
        title: 'داشبورد',
        href: '/dashboard',
    },

    {
        title: 'مخاطبین',
        href: '/contacts',
    },

    {
        title: 'پیگیری‌ها',
        href: '/followups',
    },

    {
        title: 'سفارش‌ها',
        href: '/orders',
    },

    {
        title: 'گزارش پیامک‌ها',
        href: '/sms/logs',
    },

]


const adminNav = [

    {
        title: 'گزارش‌ها',
        href: '/reports',
    },

    {
        title: 'مانیتورینگ',
        href: '/monitoring',
    },

    {
        title: 'مدیریت کاربران',
        href: '/users',
    },

    {
        title: 'قالب‌های پیامک',
        href: '/sms/templates',
    },

    {
        title: 'تنظیمات پیامک',
        href: '/sms/settings',
    },

]


function isActive(href) {

    if (href === '/dashboard') {
        return currentUrl.value === '/dashboard'
    }

    return currentUrl.value.startsWith(
        href
    )

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

    return 'داشبورد'

})


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
            class="fixed inset-0 z-40 bg-slate-950/40 md:hidden"
            @click="sidebarOpen = false"
        ></div>


        <!-- Sidebar -->
        <aside
            class="
                crm-sidebar
                fixed
                inset-y-0
                right-0
                z-50
                flex
                w-[270px]
                flex-col
                text-white
                transition-transform
                duration-200
                md:translate-x-0
            "
            :class="
                sidebarOpen
                    ? 'translate-x-0'
                    : 'translate-x-full md:translate-x-0'
            "
        >

            <!-- Brand -->
            <div
                class="
                    flex
                    h-[78px]
                    items-center
                    border-b
                    border-white/10
                    px-6
                "
            >

                <div
                    class="
                        ml-3
                        flex
                        h-11
                        w-11
                        items-center
                        justify-center
                        rounded-xl
                        bg-blue-500
                        font-black
                        shadow-lg
                        shadow-blue-950/30
                    "
                >
                    CRM
                </div>


                <div>

                    <div
                        class="
                            text-base
                            font-bold
                        "
                    >
                        CRM ایده‌پردازان
                    </div>

                    <div
                        class="
                            mt-1
                            text-[11px]
                            text-blue-100/70
                        "
                    >
                        مدیریت ارتباط با مشتری
                    </div>

                </div>

            </div>


            <!-- Menu -->
            <div
                class="
                    flex-1
                    overflow-y-auto
                    px-4
                    py-5
                "
            >

                <div
                    class="
                        mb-2
                        px-3
                        text-[11px]
                        font-bold
                        uppercase
                        tracking-wider
                        text-blue-100/50
                    "
                >
                    عملیات
                </div>


                <nav class="space-y-1">

                    <Link
                        v-for="item in publicNav"
                        :key="item.href"
                        :href="item.href"
                        class="
                            group
                            flex
                            items-center
                            gap-3
                            rounded-xl
                            px-3
                            py-3
                            text-sm
                            font-medium
                            transition
                        "
                        :class="
                            isActive(item.href)
                                ? 'bg-blue-500 text-white shadow-lg shadow-blue-950/20'
                                : 'text-blue-50/80 hover:bg-white/8 hover:text-white'
                        "
                        @click="sidebarOpen = false"
                    >

                        <span
                            class="
                                h-2
                                w-2
                                rounded-full
                            "
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
                        class="
                            mb-2
                            mt-7
                            px-3
                            text-[11px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-blue-100/50
                        "
                    >
                        مدیریت
                    </div>


                    <nav class="space-y-1">

                        <Link
                            v-for="item in adminNav"
                            :key="item.href"
                            :href="item.href"
                            class="
                                group
                                flex
                                items-center
                                gap-3
                                rounded-xl
                                px-3
                                py-3
                                text-sm
                                font-medium
                                transition
                            "
                            :class="
                                isActive(item.href)
                                    ? 'bg-blue-500 text-white shadow-lg shadow-blue-950/20'
                                    : 'text-blue-50/80 hover:bg-white/8 hover:text-white'
                            "
                            @click="sidebarOpen = false"
                        >

                            <span
                                class="
                                    h-2
                                    w-2
                                    rounded-full
                                "
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
                class="
                    border-t
                    border-white/10
                    p-4
                "
            >

                <div
                    class="
                        mb-3
                        rounded-xl
                        bg-white/5
                        px-4
                        py-3
                    "
                >

                    <div
                        class="
                            truncate
                            text-sm
                            font-bold
                            text-white
                        "
                    >
                        {{ user?.name ?? 'کاربر سیستم' }}
                    </div>


                    <div
                        class="
                            mt-1
                            text-xs
                            text-blue-100/60
                        "
                    >
                        {{
                            isSuperAdmin
                                ? 'مدیر سیستم'
                                : 'کارمند'
                        }}
                    </div>

                </div>


                <button
                    type="button"
                    class="
                        w-full
                        rounded-xl
                        border
                        border-white/10
                        px-4
                        py-2.5
                        text-sm
                        text-blue-50/80
                        transition
                        hover:bg-white/10
                        hover:text-white
                    "
                    @click="logout"
                >
                    خروج از حساب
                </button>

            </div>

        </aside>


        <!-- Main -->
        <div
            class="
                crm-main
                flex
                min-h-screen
                flex-1
                flex-col
                md:mr-[270px]
            "
        >

            <!-- Header -->
            <header
                class="
                    crm-topbar
                    sticky
                    top-0
                    z-30
                    flex
                    h-[78px]
                    items-center
                    justify-between
                    px-5
                    md:px-8
                "
            >

                <div
                    class="
                        flex
                        items-center
                        gap-3
                    "
                >

                    <button
                        type="button"
                        class="
                            flex
                            h-10
                            w-10
                            items-center
                            justify-center
                            rounded-lg
                            border
                            border-slate-200
                            bg-white
                            text-xl
                            text-slate-700
                            md:hidden
                        "
                        @click="sidebarOpen = true"
                    >
                        ☰
                    </button>


                    <div>

                        <h2
                            class="
                                text-lg
                                font-extrabold
                                text-slate-900
                            "
                        >
                            {{ pageTitle }}
                        </h2>

                        <div
                            class="
                                mt-1
                                hidden
                                text-xs
                                text-slate-400
                                sm:block
                            "
                        >
                            سامانه مدیریت ارتباط با مشتریان
                        </div>

                    </div>

                </div>


                <div
                    class="
                        hidden
                        items-center
                        gap-3
                        text-sm
                        sm:flex
                    "
                >

                    <div class="text-slate-500">
                        {{ user?.name }}
                    </div>

                    <div
                        class="
                            h-9
                            w-9
                            rounded-full
                            bg-blue-50
                            text-center
                            font-bold
                            leading-9
                            text-blue-600
                        "
                    >
                        {{
                            user?.name
                                ? user.name.substring(0, 1)
                                : 'U'
                        }}
                    </div>

                </div>

            </header>


            <!-- Content -->
            <main
                class="
                    crm-content
                    flex-1
                    overflow-x-auto
                    p-5
                    md:p-8
                "
            >

                <div
                    v-if="page.props.flash?.success"
                    class="
                        crm-flash
                        crm-flash-success
                    "
                >
                    {{ page.props.flash.success }}
                </div>


                <div
                    v-if="page.props.flash?.error"
                    class="
                        crm-flash
                        crm-flash-error
                    "
                >
                    {{ page.props.flash.error }}
                </div>


                <slot />

            </main>

        </div>

    </div>

</template>
