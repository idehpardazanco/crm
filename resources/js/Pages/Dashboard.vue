<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { formatPersianDateTime } from '../utils/date'

const props = defineProps({
    dashboardType: String,
    stats: Object,
    todayFollowUps: Array,
    overdueFollowUps: Array,
    latestCalls: Array,
    employeePerformance: Array,
})

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

const callResultLabels = {
    no_answer: 'پاسخ نداد',
    unavailable: 'در دسترس نبود',
    interested: 'علاقه‌مند بود',
    demo_requested: 'درخواست دمو داشت',
    price_requested: 'قیمت خواست',
    call_later: 'بعداً تماس بگیریم',
    customer: 'مشتری شد',
    not_interested: 'تمایل نداشت',
}

const todayLabel = new Intl.DateTimeFormat(
    'fa-IR-u-ca-persian-nu-latn',
    {
        timeZone: 'Asia/Tehran',
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    },
).format(new Date())

const conversionRate = computed(() => {
    const value = Number(
        props.stats?.conversionRate ?? 0
    )

    if (Number.isNaN(value)) {
        return 0
    }

    return Math.min(
        Math.max(value, 0),
        100
    )
})

const statusBadgeClass = (status) => {
    const map = {
        new: 'badge-blue',
        contacted: 'badge-slate',
        interested: 'badge-violet',
        follow_up: 'badge-amber',
        demo_sent: 'badge-indigo',
        customer: 'badge-green',
        rejected: 'badge-red',
        no_answer: 'badge-orange',
        active: 'badge-green',
        inactive: 'badge-slate',
    }

    return map[status] ?? 'badge-slate'
}

const callResultBadgeClass = (result) => {
    const map = {
        no_answer: 'badge-orange',
        unavailable: 'badge-slate',
        interested: 'badge-violet',
        demo_requested: 'badge-indigo',
        price_requested: 'badge-amber',
        call_later: 'badge-blue',
        customer: 'badge-green',
        not_interested: 'badge-red',
    }

    return map[result] ?? 'badge-slate'
}

const firstLetter = (name) => {
    if (!name) {
        return '؟'
    }

    return String(name)
        .trim()
        .substring(0, 1)
}
</script>


<template>
    <div
        class="dashboard-page"
        dir="rtl"
    >

        <!-- =================================================
             HERO
        ================================================== -->

        <section class="dashboard-hero">

            <div class="hero-decoration hero-decoration-one"></div>
            <div class="hero-decoration hero-decoration-two"></div>

            <div class="hero-content">

                <div class="hero-copy">

                    <div class="hero-eyebrow">
                        <span class="hero-eyebrow-dot"></span>

                        {{
                            dashboardType === 'admin'
                                ? 'مرکز کنترل مدیریت'
                                : 'فضای کاری امروز شما'
                        }}
                    </div>


                    <h1 class="hero-title">
                        {{
                            dashboardType === 'admin'
                                ? 'داشبورد مدیریت CRM'
                                : 'داشبورد من'
                        }}
                    </h1>


                    <p class="hero-description">
                        {{
                            dashboardType === 'admin'
                                ? 'نمای یکپارچه‌ای از فروش، تماس‌ها، پیگیری‌ها و عملکرد تیم شما'
                                : 'تماس‌ها، پیگیری‌ها و فعالیت‌های امروزتان را از همین‌جا مدیریت کنید'
                        }}
                    </p>


                    <div class="hero-date">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            aria-hidden="true"
                        >
                            <path
                                d="M7 3V6M17 3V6M4 9H20M5 5H19C20.1046 5 21 5.89543 21 7V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V7C3 5.89543 3.89543 5 5 5Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>

                        {{ todayLabel }}

                    </div>

                </div>


                <div class="hero-actions">

                    <Link
                        href="/contacts"
                        class="hero-button hero-button-primary"
                    >
                        <span class="hero-button-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M16 21V19C16 16.7909 14.2091 15 12 15H6C3.79086 15 2 16.7909 2 19V21M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11ZM19 8V14M22 11H16"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                        </span>

                        مخاطبین
                    </Link>


                    <Link
                        href="/followups"
                        class="hero-button hero-button-secondary"
                    >
                        <span class="hero-button-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </span>

                        پیگیری‌ها
                    </Link>


                    <Link
                        href="/orders"
                        class="hero-button hero-button-secondary"
                    >
                        <span class="hero-button-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M3 6H21L19 20H5L3 6ZM8 6V5C8 2.79086 9.79086 1 12 1C14.2091 1 16 2.79086 16 5V6"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                        </span>

                        سفارش‌ها
                    </Link>

                </div>

            </div>

        </section>


        <!-- =================================================
             ADMIN STATS
        ================================================== -->

        <section
            v-if="dashboardType === 'admin'"
            class="stats-grid"
        >

            <!-- Contacts -->
            <div class="stat-card stat-blue">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M16 21V19C16 16.7909 14.2091 15 12 15H6C3.79086 15 2 16.7909 2 19V21M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11ZM17 11L19 13L23 9"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        کل
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.contacts ?? 0 }}
                </div>

                <div class="stat-label">
                    کل مخاطبین
                </div>

                <div class="stat-footer">
                    پایگاه مخاطبین CRM
                </div>

            </div>


            <!-- Calls -->
            <div class="stat-card stat-cyan">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M22 16.92V20C22 20.5523 21.5523 21 21 21C11.0589 21 3 12.9411 3 3C3 2.44772 3.44772 2 4 2H7.08C7.55685 2 7.96745 2.33652 8.06002 2.8043L8.76002 6.3043C8.82498 6.63118 8.72221 6.96929 8.484 7.203L6.68 8.98C8.0489 11.9367 10.4433 14.3311 13.4 15.7L15.177 13.896C15.4107 13.6578 15.7488 13.555 16.0757 13.62L19.5757 14.32C20.0435 14.4126 20.38 14.8232 20.38 15.3V16.92H22Z"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        امروز
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.todayCalls ?? 0 }}
                </div>

                <div class="stat-label">
                    تماس‌های امروز
                </div>

                <div class="stat-footer">
                    فعالیت تماس تیم
                </div>

            </div>


            <!-- SMS -->
            <div class="stat-card stat-violet">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M4 4H20C21.1046 4 22 4.89543 22 6V16C22 17.1046 21.1046 18 20 18H7L2 22V6C2 4.89543 2.89543 4 4 4ZM6 8H18M6 12H14"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        امروز
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.todaySms ?? 0 }}
                </div>

                <div class="stat-label">
                    پیامک‌های ارسال‌شده
                </div>

                <div class="stat-footer">
                    پیامک‌های موفق امروز
                </div>

            </div>


            <!-- Today followups -->
            <div class="stat-card stat-amber">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        امروز
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.todayFollowUps ?? 0 }}
                </div>

                <div class="stat-label">
                    یادآوری‌های امروز
                </div>

                <div class="stat-footer">
                    پیگیری‌های برنامه‌ریزی‌شده
                </div>

            </div>


            <!-- Orders -->
            <div class="stat-card stat-indigo">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M3 6H21L19 20H5L3 6ZM8 6V5C8 2.79086 9.79086 1 12 1C14.2091 1 16 2.79086 16 5V6"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        جدید
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.newOrders ?? 0 }}
                </div>

                <div class="stat-label">
                    سفارش‌های جدید
                </div>

                <div class="stat-footer">
                    سفارش‌های نیازمند بررسی
                </div>

            </div>


            <!-- Customers -->
            <div class="stat-card stat-green">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 21C16.9706 21 21 16.9706 21 12C21 7.02944 16.9706 3 12 3C7.02944 3 3 7.02944 3 12C3 16.9706 7.02944 21 12 21ZM8 12L11 15L16 9"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        موفق
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.customers ?? 0 }}
                </div>

                <div class="stat-label">
                    مشتریان
                </div>

                <div class="stat-footer">
                    مخاطبین تبدیل‌شده
                </div>

            </div>


            <!-- Overdue -->
            <div class="stat-card stat-red">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 9V13M12 17H12.01M10.29 3.86L2.82 17C2.64 17.31 2.55 17.66 2.56 18.02C2.57 18.38 2.68 18.73 2.87 19.03C3.06 19.34 3.32 19.59 3.64 19.76C3.95 19.93 4.3 20.01 4.66 20H19.34C19.7 20.01 20.05 19.93 20.36 19.76C20.68 19.59 20.94 19.34 21.13 19.03C21.32 18.73 21.43 18.38 21.44 18.02C21.45 17.66 21.36 17.31 21.18 17L13.71 3.86C13.52 3.56 13.26 3.31 12.95 3.14C12.65 2.97 12.3 2.88 11.95 2.88C11.6 2.88 11.25 2.97 10.95 3.14C10.64 3.31 10.38 3.56 10.19 3.86H10.29Z"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        مهم
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.overdueFollowUps ?? 0 }}
                </div>

                <div class="stat-label">
                    پیگیری‌های عقب‌افتاده
                </div>

                <div class="stat-footer">
                    نیازمند اقدام فوری
                </div>

            </div>


            <!-- Conversion -->
            <div class="stat-card stat-emerald">

                <div class="stat-card-top">

                    <div class="stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M4 19L9 14L13 18L20 9M15 9H20V14"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <span class="stat-tag">
                        KPI
                    </span>

                </div>


                <div class="stat-value">
                    {{ stats.conversionRate ?? 0 }}%
                </div>

                <div class="stat-label">
                    نرخ تبدیل به مشتری
                </div>


                <div class="conversion-track">

                    <div
                        class="conversion-bar"
                        :style="{
                            width: `${conversionRate}%`
                        }"
                    ></div>

                </div>

            </div>

        </section>


        <!-- =================================================
             EMPLOYEE STATS
        ================================================== -->

        <section
            v-else
            class="stats-grid stats-grid-employee"
        >

            <div class="stat-card stat-cyan">

                <div class="stat-card-top">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M22 16.92V20C22 20.5523 21.5523 21 21 21C11.0589 21 3 12.9411 3 3C3 2.44772 3.44772 2 4 2H7.08C7.55685 2 7.96745 2.33652 8.06002 2.8043L8.76 6.3C8.82 6.63 8.72 6.97 8.48 7.2L6.68 8.98C8.05 11.94 10.44 14.33 13.4 15.7L15.18 13.9C15.41 13.66 15.75 13.55 16.08 13.62L19.58 14.32C20.04 14.41 20.38 14.82 20.38 15.3V16.92H22Z"
                                stroke="currentColor"
                                stroke-width="1.6"
                            />
                        </svg>
                    </div>

                    <span class="stat-tag">
                        امروز
                    </span>
                </div>

                <div class="stat-value">
                    {{ stats.todayCalls ?? 0 }}
                </div>

                <div class="stat-label">
                    تماس‌های امروز
                </div>

                <div class="stat-footer">
                    تماس‌های ثبت‌شده شما
                </div>

            </div>


            <div class="stat-card stat-amber">

                <div class="stat-card-top">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <span class="stat-tag">
                        امروز
                    </span>
                </div>

                <div class="stat-value">
                    {{ stats.todayFollowUps ?? 0 }}
                </div>

                <div class="stat-label">
                    یادآوری‌های امروز
                </div>

                <div class="stat-footer">
                    برنامه پیگیری شما
                </div>

            </div>


            <div class="stat-card stat-blue">

                <div class="stat-card-top">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M16 21V19C16 16.7909 14.2091 15 12 15H6C3.79086 15 2 16.7909 2 19V21M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11ZM19 8V14M22 11H16"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <span class="stat-tag">
                        جدید
                    </span>
                </div>

                <div class="stat-value">
                    {{ stats.newContacts ?? 0 }}
                </div>

                <div class="stat-label">
                    مخاطبین جدید
                </div>

                <div class="stat-footer">
                    ورودی جدید شما
                </div>

            </div>


            <div class="stat-card stat-red">

                <div class="stat-card-top">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 9V13M12 17H12.01M10.29 3.86L2.82 17C2.64 17.31 2.55 17.66 2.56 18.02C2.57 18.38 2.68 18.73 2.87 19.03C3.06 19.34 3.32 19.59 3.64 19.76C3.95 19.93 4.3 20.01 4.66 20H19.34C19.7 20.01 20.05 19.93 20.36 19.76C20.68 19.59 20.94 19.34 21.13 19.03C21.32 18.73 21.43 18.38 21.44 18.02C21.45 17.66 21.36 17.31 21.18 17L13.71 3.86"
                                stroke="currentColor"
                                stroke-width="1.6"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <span class="stat-tag">
                        مهم
                    </span>
                </div>

                <div class="stat-value">
                    {{ stats.overdueFollowUps ?? 0 }}
                </div>

                <div class="stat-label">
                    پیگیری‌های عقب‌افتاده
                </div>

                <div class="stat-footer">
                    نیازمند اقدام
                </div>

            </div>


            <div class="stat-card stat-violet">

                <div class="stat-card-top">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M4 4H20C21.1046 4 22 4.89543 22 6V16C22 17.1046 21.1046 18 20 18H7L2 22V6C2 4.89543 2.89543 4 4 4ZM6 8H18M6 12H14"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <span class="stat-tag">
                        امروز
                    </span>
                </div>

                <div class="stat-value">
                    {{ stats.todaySms ?? 0 }}
                </div>

                <div class="stat-label">
                    پیامک‌های امروز من
                </div>

                <div class="stat-footer">
                    ارسال‌های موفق
                </div>

            </div>


            <div class="stat-card stat-indigo">

                <div class="stat-card-top">
                    <div class="stat-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M3 6H21L19 20H5L3 6ZM8 6V5C8 2.79086 9.79086 1 12 1C14.2091 1 16 2.79086 16 5V6"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <span class="stat-tag">
                        من
                    </span>
                </div>

                <div class="stat-value">
                    {{ stats.orders ?? 0 }}
                </div>

                <div class="stat-label">
                    سفارش‌های ثبت‌شده
                </div>

                <div class="stat-footer">
                    سفارش‌های شما
                </div>

            </div>

        </section>


        <!-- =================================================
             OVERDUE FOLLOWUPS
        ================================================== -->

        <section
            v-if="overdueFollowUps.length"
            class="dashboard-panel danger-panel"
        >

            <div class="panel-header">

                <div class="panel-title-area">

                    <div class="panel-icon panel-icon-red">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 9V13M12 17H12.01M10.29 3.86L2.82 17C2.64 17.31 2.55 17.66 2.56 18.02C2.57 18.38 2.68 18.73 2.87 19.03C3.06 19.34 3.32 19.59 3.64 19.76C3.95 19.93 4.3 20.01 4.66 20H19.34C19.7 20.01 20.05 19.93 20.36 19.76C20.68 19.59 20.94 19.34 21.13 19.03C21.32 18.73 21.43 18.38 21.44 18.02C21.45 17.66 21.36 17.31 21.18 17L13.71 3.86"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />
                        </svg>

                    </div>


                    <div>
                        <h2 class="panel-title">
                            پیگیری‌های عقب‌افتاده
                        </h2>

                        <p class="panel-subtitle">
                            مواردی که زمان پیگیری آن‌ها گذشته است
                        </p>
                    </div>

                </div>


                <Link
                    href="/followups"
                    class="panel-link danger-link"
                >
                    مشاهده همه

                    <span>←</span>
                </Link>

            </div>


            <div class="table-container">

                <table class="premium-table">

                    <thead>
                        <tr>
                            <th>مخاطب</th>
                            <th>کسب‌وکار</th>
                            <th>موبایل</th>
                            <th>عنوان</th>
                            <th>زمان پیگیری</th>

                            <th
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                            >
                                کارمند
                            </th>

                            <th>عملیات</th>
                        </tr>
                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                item in
                                overdueFollowUps
                            "
                            :key="item.id"
                        >

                            <td>
                                <div class="contact-cell">

                                    <div class="mini-avatar danger-avatar">
                                        {{
                                            firstLetter(
                                                item.contact?.name
                                            )
                                        }}
                                    </div>

                                    <span class="contact-name">
                                        {{
                                            item.contact?.name
                                            ?? 'بدون نام'
                                        }}
                                    </span>

                                </div>
                            </td>


                            <td>
                                {{
                                    item.contact
                                        ?.business_name
                                    ?? '—'
                                }}
                            </td>


                            <td>
                                <span class="ltr-number">
                                    {{
                                        item.contact?.mobile
                                        ?? '—'
                                    }}
                                </span>
                            </td>


                            <td>
                                {{ item.title ?? '—' }}
                            </td>


                            <td>
                                <span class="overdue-time">
                                    {{
                                        formatPersianDateTime(
                                            item.follow_up_at
                                        )
                                    }}
                                </span>
                            </td>


                            <td
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                            >
                                {{
                                    item.user?.name
                                    ?? '—'
                                }}
                            </td>


                            <td>

                                <Link
                                    v-if="item.contact"
                                    :href="
                                        `/contacts/${item.contact.id}`
                                    "
                                    class="table-action"
                                >
                                    مشاهده
                                </Link>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- =================================================
             TODAY + PERFORMANCE
        ================================================== -->

        <div
            class="dashboard-layout"
            :class="{
                'dashboard-layout-admin':
                    dashboardType === 'admin'
            }"
        >

            <!-- Today followups -->
            <section class="dashboard-panel">

                <div class="panel-header">

                    <div class="panel-title-area">

                        <div class="panel-icon panel-icon-blue">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>

                            <h2 class="panel-title">
                                پیگیری‌های امروز
                            </h2>

                            <p class="panel-subtitle">
                                برنامه تماس و پیگیری امروز
                            </p>

                        </div>

                    </div>


                    <Link
                        href="/followups"
                        class="panel-link"
                    >
                        مشاهده همه
                        <span>←</span>
                    </Link>

                </div>


                <div
                    v-if="todayFollowUps.length"
                    class="followup-list"
                >

                    <div
                        v-for="
                            item in
                            todayFollowUps.slice(0, 6)
                        "
                        :key="item.id"
                        class="followup-item"
                    >

                        <div class="followup-person">

                            <div class="mini-avatar">
                                {{
                                    firstLetter(
                                        item.contact?.name
                                    )
                                }}
                            </div>


                            <div class="followup-copy">

                                <div class="followup-name">
                                    {{
                                        item.contact?.name
                                        ?? 'بدون نام'
                                    }}
                                </div>


                                <div class="followup-business">
                                    {{
                                        item.contact
                                            ?.business_name
                                        ?? item.title
                                        ?? 'بدون عنوان'
                                    }}
                                </div>

                            </div>

                        </div>


                        <div class="followup-meta">

                            <div class="followup-time">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                    />
                                </svg>

                                {{
                                    formatPersianDateTime(
                                        item.follow_up_at
                                    )
                                }}

                            </div>


                            <Link
                                v-if="item.contact"
                                :href="
                                    `/contacts/${item.contact.id}`
                                "
                                class="round-action"
                                title="مشاهده مخاطب"
                            >
                                ←
                            </Link>

                        </div>

                    </div>

                </div>


                <div
                    v-else
                    class="empty-state"
                >

                    <div class="empty-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M8 12L11 15L16 9M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>

                    <div class="empty-title">
                        پیگیری عقب‌مانده‌ای برای امروز ندارید
                    </div>

                    <div class="empty-description">
                        برنامه امروز مرتب و به‌روز است.
                    </div>

                </div>

            </section>


            <!-- Small conversion card -->
            <section
                v-if="dashboardType === 'admin'"
                class="conversion-panel"
            >

                <div class="conversion-orbit">

                    <div
                        class="conversion-circle"
                        :style="{
                            '--value':
                                `${conversionRate * 3.6}deg`
                        }"
                    >

                        <div class="conversion-circle-inner">

                            <strong>
                                {{ stats.conversionRate ?? 0 }}%
                            </strong>

                            <span>
                                نرخ تبدیل
                            </span>

                        </div>

                    </div>

                </div>


                <h3>
                    تبدیل مخاطب به مشتری
                </h3>

                <p>
                    نسبت مشتریان نهایی به کل مخاطبین ثبت‌شده
                </p>


                <div class="conversion-panel-stats">

                    <div>
                        <strong>
                            {{ stats.contacts ?? 0 }}
                        </strong>

                        <span>
                            مخاطب
                        </span>
                    </div>

                    <div class="conversion-divider"></div>

                    <div>
                        <strong>
                            {{ stats.customers ?? 0 }}
                        </strong>

                        <span>
                            مشتری
                        </span>
                    </div>

                </div>

            </section>

        </div>


        <!-- =================================================
             EMPLOYEE PERFORMANCE
        ================================================== -->

        <section
            v-if="dashboardType === 'admin'"
            class="dashboard-panel"
        >

            <div class="panel-header">

                <div class="panel-title-area">

                    <div class="panel-icon panel-icon-violet">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M16 21V19C16 16.7909 14.2091 15 12 15H6C3.79086 15 2 16.7909 2 19V21M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11ZM17 11L19 13L23 9"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>


                    <div>

                        <h2 class="panel-title">
                            عملکرد کارمندان
                        </h2>

                        <p class="panel-subtitle">
                            مقایسه شاخص‌های اصلی تیم فروش
                        </p>

                    </div>

                </div>


                <div class="panel-chip">
                    {{
                        employeePerformance.length
                    }}
                    کارمند
                </div>

            </div>


            <div class="table-container">

                <table class="premium-table">

                    <thead>

                        <tr>
                            <th>کارمند</th>
                            <th>مخاطبین</th>
                            <th>مشتریان</th>
                            <th>کل تماس‌ها</th>
                            <th>تماس امروز</th>
                            <th>پیامک موفق</th>
                            <th>سفارش</th>
                            <th>نرخ تبدیل</th>
                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                employee in
                                employeePerformance
                            "
                            :key="employee.id"
                        >

                            <td>

                                <div class="employee-cell">

                                    <div class="employee-avatar">
                                        {{
                                            firstLetter(
                                                employee.name
                                            )
                                        }}
                                    </div>


                                    <div>

                                        <div class="employee-name">
                                            {{ employee.name }}
                                        </div>

                                        <div class="employee-role">
                                            کارشناس فروش
                                        </div>

                                    </div>

                                </div>

                            </td>


                            <td>
                                {{ employee.contacts }}
                            </td>

                            <td>
                                {{ employee.customers }}
                            </td>

                            <td>
                                {{ employee.calls }}
                            </td>

                            <td>
                                <span class="metric-chip metric-blue">
                                    {{ employee.todayCalls }}
                                </span>
                            </td>

                            <td>
                                {{ employee.sms }}
                            </td>

                            <td>
                                {{ employee.orders }}
                            </td>

                            <td>

                                <div class="conversion-cell">

                                    <strong>
                                        {{
                                            employee
                                                .conversionRate
                                        }}%
                                    </strong>


                                    <div class="mini-progress">

                                        <div
                                            class="mini-progress-bar"
                                            :style="{
                                                width:
                                                    `${Math.min(
                                                        Number(
                                                            employee
                                                                .conversionRate
                                                            ?? 0
                                                        ),
                                                        100
                                                    )}%`
                                            }"
                                        ></div>

                                    </div>

                                </div>

                            </td>

                        </tr>


                        <tr
                            v-if="
                                !employeePerformance.length
                            "
                        >

                            <td
                                colspan="8"
                                class="empty-table"
                            >
                                کارمند فعالی وجود ندارد.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- =================================================
             LATEST CALLS
        ================================================== -->

        <section class="dashboard-panel">

            <div class="panel-header">

                <div class="panel-title-area">

                    <div class="panel-icon panel-icon-green">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M22 16.92V20C22 20.5523 21.5523 21 21 21C11.0589 21 3 12.9411 3 3C3 2.44772 3.44772 2 4 2H7.08C7.55685 2 7.96745 2.33652 8.06002 2.8043L8.76002 6.3043C8.82498 6.63118 8.72221 6.96929 8.484 7.203L6.68 8.98C8.0489 11.9367 10.4433 14.3311 13.4 15.7L15.177 13.896C15.4107 13.6578 15.7488 13.555 16.0757 13.62L19.5757 14.32C20.0435 14.4126 20.38 14.8232 20.38 15.3V16.92H22Z"
                                stroke="currentColor"
                                stroke-width="1.6"
                            />
                        </svg>

                    </div>


                    <div>

                        <h2 class="panel-title">
                            آخرین تماس‌ها
                        </h2>

                        <p class="panel-subtitle">
                            آخرین فعالیت‌های ثبت‌شده در سیستم
                        </p>

                    </div>

                </div>

            </div>


            <div class="table-container">

                <table class="premium-table">

                    <thead>

                        <tr>
                            <th>مخاطب</th>
                            <th>نتیجه تماس</th>
                            <th>وضعیت بعد از تماس</th>

                            <th
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                            >
                                کارمند
                            </th>

                            <th>تاریخ</th>
                            <th>عملیات</th>
                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                item in latestCalls
                            "
                            :key="item.id"
                        >

                            <td>

                                <div class="contact-cell">

                                    <div class="mini-avatar">
                                        {{
                                            firstLetter(
                                                item.contact?.name
                                            )
                                        }}
                                    </div>


                                    <span class="contact-name">
                                        {{
                                            item.contact?.name
                                            ?? 'بدون نام'
                                        }}
                                    </span>

                                </div>

                            </td>


                            <td>

                                <span
                                    class="status-badge"
                                    :class="
                                        callResultBadgeClass(
                                            item.result
                                        )
                                    "
                                >
                                    {{
                                        callResultLabels[
                                            item.result
                                        ]
                                        ?? item.result
                                        ?? '—'
                                    }}
                                </span>

                            </td>


                            <td>

                                <span
                                    class="status-badge"
                                    :class="
                                        statusBadgeClass(
                                            item
                                                .status_after_call
                                        )
                                    "
                                >
                                    {{
                                        statusLabels[
                                            item
                                                .status_after_call
                                        ]
                                        ??
                                        item
                                            .status_after_call
                                        ??
                                        '—'
                                    }}
                                </span>

                            </td>


                            <td
                                v-if="
                                    dashboardType ===
                                    'admin'
                                "
                            >
                                {{
                                    item.user?.name
                                    ?? '—'
                                }}
                            </td>


                            <td class="date-cell">
                                {{
                                    formatPersianDateTime(
                                        item.created_at
                                    )
                                }}
                            </td>


                            <td>

                                <Link
                                    v-if="item.contact"
                                    :href="
                                        `/contacts/${item.contact.id}`
                                    "
                                    class="table-action"
                                >
                                    مشاهده
                                </Link>

                            </td>

                        </tr>


                        <tr
                            v-if="!latestCalls.length"
                        >

                            <td
                                :colspan="
                                    dashboardType === 'admin'
                                        ? 6
                                        : 5
                                "
                                class="empty-table"
                            >
                                هنوز تماسی ثبت نشده است.
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>

    </div>
</template>


<style scoped>

/* =========================================================
   PAGE
   ========================================================= */

.dashboard-page,
.dashboard-page * {
    font-family:
        'IRANYekanXFaNum',
        Tahoma,
        Arial,
        sans-serif !important;
}

.dashboard-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding-bottom: 32px;
}


/* =========================================================
   HERO
   ========================================================= */

.dashboard-hero {
    position: relative;
    overflow: hidden;

    min-height: 220px;

    margin-bottom: 24px;

    border-radius: 24px;

    background:
        linear-gradient(
            125deg,
            #0f2d5c 0%,
            #17478a 48%,
            #2563eb 100%
        );

    box-shadow:
        0 18px 50px
        rgba(15, 45, 92, 0.20);
}


.dashboard-hero::after {
    content: '';

    position: absolute;
    inset: 0;

    pointer-events: none;

    background:
        linear-gradient(
            90deg,
            rgba(255, 255, 255, 0.035) 1px,
            transparent 1px
        ),
        linear-gradient(
            rgba(255, 255, 255, 0.035) 1px,
            transparent 1px
        );

    background-size: 36px 36px;

    mask-image:
        linear-gradient(
            to left,
            black,
            transparent 85%
        );
}


.hero-decoration {
    position: absolute;
    border-radius: 999px;
    pointer-events: none;
}


.hero-decoration-one {
    width: 270px;
    height: 270px;

    top: -130px;
    left: 3%;

    background:
        rgba(255, 255, 255, 0.07);

    border:
        1px solid
        rgba(255, 255, 255, 0.10);
}


.hero-decoration-two {
    width: 190px;
    height: 190px;

    bottom: -120px;
    left: 19%;

    background:
        rgba(56, 189, 248, 0.12);

    filter: blur(2px);
}


.hero-content {
    position: relative;
    z-index: 2;

    min-height: 220px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 32px;

    padding: 34px 38px;
}


.hero-copy {
    color: white;
}


.hero-eyebrow {
    display: inline-flex;
    align-items: center;

    gap: 8px;

    margin-bottom: 13px;

    padding: 7px 11px;

    border:
        1px solid
        rgba(255, 255, 255, 0.12);

    border-radius: 999px;

    background:
        rgba(255, 255, 255, 0.08);

    color:
        rgba(255, 255, 255, 0.84);

    font-size: 12px;
    font-weight: 600;
}


.hero-eyebrow-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;

    background: #4ade80;

    box-shadow:
        0 0 0 5px
        rgba(74, 222, 128, 0.12);
}


.hero-title {
    margin: 0;

    color: white !important;

    font-size: 30px !important;
    font-weight: 800 !important;

    line-height: 1.35;
}


.hero-description {
    max-width: 620px;

    margin:
        9px 0 0;

    color:
        rgba(255, 255, 255, 0.70);

    font-size: 14px;

    line-height: 1.9;
}


.hero-date {
    display: flex;
    align-items: center;

    gap: 8px;

    margin-top: 17px;

    color:
        rgba(255, 255, 255, 0.73);

    font-size: 12px;
}


.hero-date svg {
    width: 17px;
    height: 17px;
}


.hero-actions {
    flex-shrink: 0;

    display: flex;
    flex-wrap: wrap;

    justify-content: flex-end;

    gap: 10px;

    max-width: 420px;
}


.hero-button {
    min-height: 46px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 9px;

    padding: 0 17px;

    border-radius: 12px;

    font-size: 13px;
    font-weight: 700;

    transition:
        transform 0.18s ease,
        background 0.18s ease,
        box-shadow 0.18s ease;
}


.hero-button:hover {
    transform:
        translateY(-2px);
}


.hero-button-primary {
    background: white;

    color: #17478a;

    box-shadow:
        0 8px 24px
        rgba(0, 0, 0, 0.14);
}


.hero-button-primary:hover {
    background: #f8fafc;
}


.hero-button-secondary {
    color: white;

    border:
        1px solid
        rgba(255, 255, 255, 0.16);

    background:
        rgba(255, 255, 255, 0.08);

    backdrop-filter:
        blur(8px);
}


.hero-button-secondary:hover {
    background:
        rgba(255, 255, 255, 0.14);
}


.hero-button-icon {
    width: 19px;
    height: 19px;
}


.hero-button-icon svg {
    width: 100%;
    height: 100%;
}


/* =========================================================
   STATS
   ========================================================= */

.stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 16px;

    margin-bottom: 24px;
}


.stats-grid-employee {
    grid-template-columns:
        repeat(3, minmax(0, 1fr));
}


.stat-card {
    position: relative;

    min-height: 168px;

    overflow: hidden;

    padding: 20px;

    border:
        1px solid
        #e8edf5;

    border-radius: 18px;

    background: #ffffff;

    box-shadow:
        0 8px 26px
        rgba(15, 23, 42, 0.055);

    transition:
        transform 0.22s ease,
        box-shadow 0.22s ease,
        border-color 0.22s ease;
}


.stat-card::after {
    content: '';

    position: absolute;

    width: 115px;
    height: 115px;

    left: -45px;
    bottom: -55px;

    border-radius: 50%;

    background:
        currentColor;

    opacity: 0.035;

    pointer-events: none;
}


.stat-card:hover {
    transform:
        translateY(-4px);

    border-color:
        rgba(37, 99, 235, 0.16);

    box-shadow:
        0 16px 36px
        rgba(15, 23, 42, 0.09);
}


.stat-card-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}


.stat-icon {
    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background:
        currentColor;

    color: inherit;
}


.stat-icon svg {
    width: 21px;
    height: 21px;

    color: white;
}


.stat-tag {
    padding: 5px 9px;

    border-radius: 999px;

    background:
        currentColor;

    color: inherit;

    font-size: 10px;
    font-weight: 700;

    opacity: 0.14;
}


.stat-value {
    margin-top: 15px;

    color: #0f172a;

    font-size: 27px;
    font-weight: 800;

    line-height: 1;
}


.stat-label {
    margin-top: 8px;

    color: #334155;

    font-size: 13px;
    font-weight: 700;
}


.stat-footer {
    margin-top: 6px;

    color: #94a3b8;

    font-size: 11px;
}


.stat-blue {
    color: #2563eb;
}


.stat-cyan {
    color: #0891b2;
}


.stat-violet {
    color: #7c3aed;
}


.stat-amber {
    color: #d97706;
}


.stat-indigo {
    color: #4f46e5;
}


.stat-green {
    color: #16a34a;
}


.stat-red {
    color: #dc2626;
}


.stat-emerald {
    color: #059669;
}


.stat-card .stat-icon {
    background:
        currentColor;
}


.stat-card .stat-tag {
    background:
        color-mix(
            in srgb,
            currentColor 11%,
            white
        );

    color:
        currentColor;

    opacity: 1;
}


.conversion-track {
    width: 100%;
    height: 5px;

    margin-top: 12px;

    overflow: hidden;

    border-radius: 999px;

    background: #ecfdf5;
}


.conversion-bar {
    height: 100%;

    border-radius: 999px;

    background:
        linear-gradient(
            90deg,
            #34d399,
            #059669
        );

    transition:
        width 0.5s ease;
}


/* =========================================================
   PANELS
   ========================================================= */

.dashboard-panel {
    margin-bottom: 24px;

    overflow: hidden;

    border:
        1px solid #e7edf5;

    border-radius: 20px;

    background: white;

    box-shadow:
        0 9px 30px
        rgba(15, 23, 42, 0.045);
}


.danger-panel {
    border-color: #fee2e2;

    box-shadow:
        0 10px 32px
        rgba(220, 38, 38, 0.05);
}


.panel-header {
    min-height: 78px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    padding: 17px 20px;

    border-bottom:
        1px solid #eef2f7;
}


.panel-title-area {
    display: flex;
    align-items: center;

    gap: 12px;
}


.panel-icon {
    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;
}


.panel-icon svg {
    width: 20px;
    height: 20px;
}


.panel-icon-red {
    background: #fef2f2;
    color: #dc2626;
}


.panel-icon-blue {
    background: #eff6ff;
    color: #2563eb;
}


.panel-icon-violet {
    background: #f5f3ff;
    color: #7c3aed;
}


.panel-icon-green {
    background: #ecfdf5;
    color: #059669;
}


.panel-title {
    margin: 0;

    color: #172033;

    font-size: 15px;
    font-weight: 800;
}


.panel-subtitle {
    margin: 3px 0 0;

    color: #94a3b8;

    font-size: 11px;

    line-height: 1.7;
}


.panel-link {
    display: inline-flex;
    align-items: center;

    gap: 6px;

    flex-shrink: 0;

    padding: 8px 11px;

    border-radius: 9px;

    color: #2563eb;

    background: #eff6ff;

    font-size: 11px;
    font-weight: 700;

    transition:
        transform 0.18s ease,
        background 0.18s ease;
}


.panel-link:hover {
    transform:
        translateX(-2px);

    background: #dbeafe;
}


.danger-link {
    color: #dc2626;

    background: #fef2f2;
}


.danger-link:hover {
    background: #fee2e2;
}


.panel-chip {
    padding: 7px 10px;

    border:
        1px solid #e2e8f0;

    border-radius: 999px;

    color: #64748b;

    background: #f8fafc;

    font-size: 11px;
    font-weight: 700;
}


/* =========================================================
   TABLE
   ========================================================= */

.table-container {
    width: 100%;

    overflow-x: auto;
}


.premium-table {
    width: 100%;

    min-width: 820px;

    border: 0 !important;
    border-radius: 0 !important;

    box-shadow: none !important;
}


.premium-table thead {
    background: #f8fafc;
}


.premium-table th {
    padding:
        13px 18px !important;

    color: #64748b !important;

    border-bottom:
        1px solid #edf2f7 !important;

    font-size: 11px !important;
    font-weight: 700 !important;

    text-align: right;
}


.premium-table td {
    padding:
        15px 18px !important;

    color: #475569 !important;

    border-bottom:
        1px solid #f1f5f9 !important;

    font-size: 12px !important;

    vertical-align: middle;
}


.premium-table tbody tr {
    transition:
        background 0.15s ease;
}


.premium-table tbody tr:hover {
    background:
        #fbfdff !important;
}


.premium-table tbody tr:last-child td {
    border-bottom:
        0 !important;
}


.contact-cell,
.employee-cell {
    display: flex;
    align-items: center;

    gap: 10px;
}


.mini-avatar,
.employee-avatar {
    width: 34px;
    height: 34px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: #2563eb;

    background:
        linear-gradient(
            135deg,
            #eff6ff,
            #dbeafe
        );

    font-size: 12px;
    font-weight: 800;
}


.employee-avatar {
    width: 38px;
    height: 38px;

    color: #7c3aed;

    background:
        linear-gradient(
            135deg,
            #f5f3ff,
            #ede9fe
        );
}


.danger-avatar {
    color: #dc2626;

    background:
        linear-gradient(
            135deg,
            #fef2f2,
            #fee2e2
        );
}


.contact-name,
.employee-name {
    color: #1e293b;

    font-weight: 700;
}


.employee-role {
    margin-top: 2px;

    color: #94a3b8;

    font-size: 10px;
}


.ltr-number {
    direction: ltr;

    display: inline-block;
}


.overdue-time {
    color: #dc2626;

    font-weight: 700;
}


.date-cell {
    color: #64748b !important;

    white-space: nowrap;
}


.table-action {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    min-width: 64px;

    padding:
        7px 10px;

    border-radius: 8px;

    color: #2563eb !important;

    background: #eff6ff;

    font-size: 10px;
    font-weight: 700;

    transition:
        background 0.18s ease,
        transform 0.18s ease;
}


.table-action:hover {
    transform:
        translateY(-1px);

    background: #dbeafe;
}


/* =========================================================
   BADGES
   ========================================================= */

.status-badge {
    display: inline-flex;

    align-items: center;
    justify-content: center;

    padding:
        6px 9px;

    border-radius: 999px;

    font-size: 10px;
    font-weight: 700;

    white-space: nowrap;
}


.badge-blue {
    color: #1d4ed8;
    background: #eff6ff;
}


.badge-slate {
    color: #475569;
    background: #f1f5f9;
}


.badge-violet {
    color: #7c3aed;
    background: #f5f3ff;
}


.badge-amber {
    color: #b45309;
    background: #fffbeb;
}


.badge-indigo {
    color: #4338ca;
    background: #eef2ff;
}


.badge-green {
    color: #15803d;
    background: #f0fdf4;
}


.badge-red {
    color: #b91c1c;
    background: #fef2f2;
}


.badge-orange {
    color: #c2410c;
    background: #fff7ed;
}


/* =========================================================
   FOLLOWUPS LIST
   ========================================================= */

.followup-list {
    padding: 4px 18px 12px;
}


.followup-item {
    min-height: 70px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 16px;

    padding:
        11px 4px;

    border-bottom:
        1px solid #f1f5f9;
}


.followup-item:last-child {
    border-bottom: 0;
}


.followup-person {
    display: flex;
    align-items: center;

    min-width: 0;

    gap: 10px;
}


.followup-copy {
    min-width: 0;
}


.followup-name {
    overflow: hidden;

    color: #1e293b;

    font-size: 12px;
    font-weight: 700;

    white-space: nowrap;
    text-overflow: ellipsis;
}


.followup-business {
    max-width: 260px;

    margin-top: 3px;

    overflow: hidden;

    color: #94a3b8;

    font-size: 10px;

    white-space: nowrap;
    text-overflow: ellipsis;
}


.followup-meta {
    display: flex;
    align-items: center;

    gap: 10px;

    flex-shrink: 0;
}


.followup-time {
    display: flex;
    align-items: center;

    gap: 6px;

    color: #64748b;

    font-size: 10px;

    white-space: nowrap;
}


.followup-time svg {
    width: 15px;
    height: 15px;

    color: #2563eb;
}


.round-action {
    width: 30px;
    height: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    color: #2563eb;

    background: #eff6ff;

    font-size: 15px;

    transition:
        transform 0.18s ease,
        background 0.18s ease;
}


.round-action:hover {
    transform:
        translateX(-2px);

    background: #dbeafe;
}


/* =========================================================
   TWO COLUMN
   ========================================================= */

.dashboard-layout {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr);

    gap: 20px;

    align-items: stretch;
}


.dashboard-layout-admin {
    grid-template-columns:
        minmax(0, 2fr)
        minmax(270px, 0.75fr);
}


.dashboard-layout > .dashboard-panel {
    margin-bottom: 24px;
}


/* =========================================================
   CONVERSION PANEL
   ========================================================= */

.conversion-panel {
    position: relative;

    min-height: 320px;

    margin-bottom: 24px;

    overflow: hidden;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    padding: 25px;

    border:
        1px solid #e7edf5;

    border-radius: 20px;

    background:
        linear-gradient(
            180deg,
            #ffffff 0%,
            #f8fbff 100%
        );

    box-shadow:
        0 9px 30px
        rgba(15, 23, 42, 0.045);

    text-align: center;
}


.conversion-panel::before {
    content: '';

    position: absolute;

    width: 180px;
    height: 180px;

    top: -90px;
    right: -90px;

    border-radius: 50%;

    background:
        rgba(37, 99, 235, 0.06);
}


.conversion-orbit {
    padding: 9px;

    border:
        1px solid #e2e8f0;

    border-radius: 50%;

    background: white;
}


.conversion-circle {
    --value: 0deg;

    width: 128px;
    height: 128px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background:
        conic-gradient(
            #2563eb var(--value),
            #eaf0f8 var(--value)
        );
}


.conversion-circle-inner {
    width: 102px;
    height: 102px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    border-radius: 50%;

    background: white;

    box-shadow:
        inset 0 0 0 1px #edf2f7;
}


.conversion-circle-inner strong {
    color: #172033;

    font-size: 23px;
    font-weight: 800;
}


.conversion-circle-inner span {
    margin-top: 3px;

    color: #94a3b8;

    font-size: 9px;
}


.conversion-panel h3 {
    margin:
        16px 0 0;

    color: #1e293b;

    font-size: 14px;
    font-weight: 800;
}


.conversion-panel p {
    max-width: 230px;

    margin:
        7px 0 0;

    color: #94a3b8;

    font-size: 10px;

    line-height: 1.8;
}


.conversion-panel-stats {
    width: 100%;

    display: flex;
    align-items: center;
    justify-content: center;

    gap: 20px;

    margin-top: 18px;

    padding-top: 16px;

    border-top:
        1px solid #eef2f7;
}


.conversion-panel-stats > div:not(.conversion-divider) {
    display: flex;
    flex-direction: column;

    gap: 2px;
}


.conversion-panel-stats strong {
    color: #1e293b;

    font-size: 15px;
    font-weight: 800;
}


.conversion-panel-stats span {
    color: #94a3b8;

    font-size: 9px;
}


.conversion-divider {
    width: 1px;
    height: 30px;

    background: #e2e8f0;
}


/* =========================================================
   EMPLOYEE METRICS
   ========================================================= */

.metric-chip {
    display: inline-flex;

    min-width: 32px;

    align-items: center;
    justify-content: center;

    padding:
        4px 7px;

    border-radius: 7px;

    font-size: 10px;
    font-weight: 700;
}


.metric-blue {
    color: #2563eb;

    background: #eff6ff;
}


.conversion-cell {
    min-width: 100px;
}


.conversion-cell strong {
    color: #059669;

    font-size: 11px;
}


.mini-progress {
    width: 72px;
    height: 4px;

    margin-top: 5px;

    overflow: hidden;

    border-radius: 999px;

    background: #ecfdf5;
}


.mini-progress-bar {
    height: 100%;

    border-radius: inherit;

    background:
        linear-gradient(
            90deg,
            #34d399,
            #059669
        );
}


/* =========================================================
   EMPTY
   ========================================================= */

.empty-state {
    min-height: 235px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    padding: 24px;

    text-align: center;
}


.empty-icon {
    width: 52px;
    height: 52px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 12px;

    border-radius: 15px;

    color: #16a34a;

    background: #f0fdf4;
}


.empty-icon svg {
    width: 25px;
    height: 25px;
}


.empty-title {
    color: #334155;

    font-size: 12px;
    font-weight: 700;
}


.empty-description {
    margin-top: 5px;

    color: #94a3b8;

    font-size: 10px;
}


.empty-table {
    padding:
        30px 18px !important;

    color:
        #94a3b8 !important;

    text-align:
        center !important;
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1280px) {

    .stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }


    .stats-grid-employee {
        grid-template-columns:
            repeat(3, minmax(0, 1fr));
    }


    .dashboard-layout-admin {
        grid-template-columns:
            minmax(0, 1.55fr)
            minmax(250px, 0.8fr);
    }

}


@media (max-width: 1024px) {

    .hero-content {
        align-items: flex-start;
        flex-direction: column;
    }


    .hero-actions {
        max-width: none;

        justify-content: flex-start;
    }


    .dashboard-layout-admin {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .conversion-panel {
        min-height: auto;
    }

}


@media (max-width: 820px) {

    .stats-grid,
    .stats-grid-employee {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }


    .dashboard-hero {
        border-radius: 18px;
    }


    .hero-content {
        padding:
            28px 24px;
    }


    .hero-title {
        font-size:
            25px !important;
    }


    .panel-header {
        align-items: flex-start;
    }

}


@media (max-width: 560px) {

    .stats-grid,
    .stats-grid-employee {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .hero-actions {
        width: 100%;
    }


    .hero-button {
        flex: 1;
    }


    .hero-description {
        font-size: 12px;
    }


    .hero-title {
        font-size:
            22px !important;
    }


    .panel-header {
        flex-direction: column;
    }


    .panel-link,
    .panel-chip {
        align-self: flex-start;
    }


    .followup-item {
        align-items: flex-start;
        flex-direction: column;
    }


    .followup-meta {
        width: 100%;

        justify-content: space-between;
    }

}

</style>
