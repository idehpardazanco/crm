<script setup>
import {
    Link,
    router,
} from '@inertiajs/vue3'

import {
    computed,
    ref,
} from 'vue'

import {
    formatPersianDateTime,
} from '../../utils/date'


const props = defineProps({
    logs: {
        type: Object,
        required: true,
    },

    filters: {
        type: Object,
        default: () => ({}),
    },
})


const search = ref(
    props.filters?.search ?? ''
)


const statusLabels = {
    queued: 'در صف ارسال',
    sent: 'ارسال موفق',
    failed: 'ارسال ناموفق',
}


const totalLogs = computed(() =>
    props.logs?.total
    ?? props.logs?.data?.length
    ?? 0
)


const sentOnPage = computed(() =>
    (props.logs?.data ?? [])
        .filter(
            item =>
                item.status === 'sent'
        )
        .length
)


const failedOnPage = computed(() =>
    (props.logs?.data ?? [])
        .filter(
            item =>
                item.status === 'failed'
        )
        .length
)


const queuedOnPage = computed(() =>
    (props.logs?.data ?? [])
        .filter(
            item =>
                item.status === 'queued'
        )
        .length
)


const doSearch = () => {

    router.get(
        '/sms/logs',
        {
            search:
                search.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}


const clearSearch = () => {

    search.value = ''

    router.get(
        '/sms/logs',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}


const statusClass = (status) => {

    return {
        queued:
            'status-queued',

        sent:
            'status-sent',

        failed:
            'status-failed',
    }[status]
        ?? 'status-default'
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
        class="sms-page"
        dir="rtl"
    >

        <!-- HEADER -->
        <section class="page-header">

            <div class="header-copy">

                <div class="header-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M4 4H20C21.1046 4 22 4.89543 22 6V16C22 17.1046 21.1046 18 20 18H7L2 22V6C2 4.89543 2.89543 4 4 4ZM6 8H18M6 12H14"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </div>


                <div>

                    <div class="eyebrow">
                        مرکز پیامک CRM
                    </div>

                    <h1>
                        تاریخچه پیامک‌ها
                    </h1>

                    <p>
                        مشاهده وضعیت، متن و جزئیات پیامک‌های ارسال‌شده
                    </p>

                </div>

            </div>

        </section>


        <!-- STATS -->
        <section class="stats-grid">

            <div class="stat-card">

                <div class="stat-icon blue">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M4 6H20M4 12H20M4 18H14"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>

                </div>


                <div>

                    <span>
                        کل پیامک‌ها
                    </span>

                    <strong>
                        {{ totalLogs }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon green">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M5 12L10 17L19 8"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </div>


                <div>

                    <span>
                        موفق در این صفحه
                    </span>

                    <strong>
                        {{ sentOnPage }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon amber">

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

                    <span>
                        در صف این صفحه
                    </span>

                    <strong>
                        {{ queuedOnPage }}
                    </strong>

                </div>

            </div>


            <div class="stat-card">

                <div class="stat-icon red">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M6 6L18 18M18 6L6 18"
                            stroke="currentColor"
                            stroke-width="1.9"
                            stroke-linecap="round"
                        />
                    </svg>

                </div>


                <div>

                    <span>
                        ناموفق در این صفحه
                    </span>

                    <strong>
                        {{ failedOnPage }}
                    </strong>

                </div>

            </div>

        </section>


        <!-- SEARCH -->
        <section class="toolbar">

            <form
                class="search-form"
                @submit.prevent="doSearch"
            >

                <div class="search-box">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M21 21L16.65 16.65M19 11C19 15.4183 15.4183 19 11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>


                    <input
                        v-model="search"
                        type="text"
                        placeholder="جستجوی شماره یا متن پیامک..."
                    >

                </div>


                <button
                    type="submit"
                    class="search-button"
                >
                    جستجو
                </button>


                <button
                    v-if="search"
                    type="button"
                    class="clear-button"
                    @click="clearSearch"
                >
                    پاک کردن
                </button>

            </form>

        </section>


        <!-- TABLE -->
        <section class="table-card">

            <div class="table-heading">

                <div>

                    <h2>
                        گزارش ارسال پیامک
                    </h2>

                    <p>
                        جزئیات کامل پیامک‌های ثبت‌شده در سیستم
                    </p>

                </div>


                <span class="record-count">
                    {{ totalLogs }}
                    رکورد
                </span>

            </div>


            <div class="table-scroll">

                <table class="premium-table">

                    <thead>

                        <tr>

                            <th>
                                شماره موبایل
                            </th>

                            <th>
                                مخاطب
                            </th>

                            <th>
                                قالب
                            </th>

                            <th class="message-column">
                                متن نهایی
                            </th>

                            <th>
                                ارسال‌کننده
                            </th>

                            <th>
                                وضعیت
                            </th>

                            <th>
                                تاریخ
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="log in logs.data"
                            :key="log.id"
                        >

                            <!-- MOBILE -->
                            <td>

                                <a
                                    :href="`tel:${log.mobile}`"
                                    class="mobile-number"
                                    dir="ltr"
                                >

                                    <span class="mobile-icon">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M8 2H16C17.1046 2 18 2.89543 18 4V20C18 21.1046 17.1046 22 16 22H8C6.89543 22 6 21.1046 6 20V4C6 2.89543 6.89543 2 8 2ZM10 19H14"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                            />
                                        </svg>

                                    </span>

                                    {{ log.mobile }}

                                </a>

                            </td>


                            <!-- CONTACT -->
                            <td>

                                <div
                                    v-if="
                                        log.sendable?.name
                                    "
                                    class="contact-cell"
                                >

                                    <div class="avatar">
                                        {{
                                            firstLetter(
                                                log.sendable.name
                                            )
                                        }}
                                    </div>


                                    <div class="contact-copy">

                                        <strong>
                                            {{
                                                log.sendable.name
                                            }}
                                        </strong>

                                        <span>
                                            مخاطب CRM
                                        </span>

                                    </div>

                                </div>


                                <span
                                    v-else
                                    class="muted"
                                >
                                    —
                                </span>

                            </td>


                            <!-- TEMPLATE -->
                            <td>

                                <span
                                    v-if="log.template"
                                    class="template-badge"
                                >
                                    {{
                                        log.template.title
                                    }}
                                </span>


                                <span
                                    v-else
                                    class="manual-badge"
                                >
                                    پیامک دستی
                                </span>

                            </td>


                            <!-- MESSAGE -->
                            <td>

                                <div
                                    class="message-preview"
                                    :title="log.message"
                                >
                                    {{ log.message }}
                                </div>

                            </td>


                            <!-- USER -->
                            <td>

                                <div
                                    v-if="
                                        log.user?.name
                                    "
                                    class="employee-cell"
                                >

                                    <div class="employee-avatar">
                                        {{
                                            firstLetter(
                                                log.user.name
                                            )
                                        }}
                                    </div>

                                    <span>
                                        {{ log.user.name }}
                                    </span>

                                </div>


                                <span
                                    v-else
                                    class="muted"
                                >
                                    —
                                </span>

                            </td>


                            <!-- STATUS -->
                            <td>

                                <span
                                    class="status-badge"
                                    :class="
                                        statusClass(
                                            log.status
                                        )
                                    "
                                >

                                    <i></i>

                                    {{
                                        statusLabels[
                                            log.status
                                        ]
                                        ?? log.status
                                    }}

                                </span>

                            </td>


                            <!-- DATE -->
                            <td>

                                <div class="date-cell">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M7 3V6M17 3V6M4 9H20M5 5H19C20.1046 5 21 5.89543 21 7V19C21 20.1046 20.1046 21 19 21H5C3.89543 21 3 20.1046 3 19V7C3 5.89543 3.89543 5 5 5Z"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                        />
                                    </svg>

                                    {{
                                        formatPersianDateTime(
                                            log.created_at
                                        )
                                    }}

                                </div>

                            </td>

                        </tr>


                        <!-- EMPTY -->
                        <tr
                            v-if="
                                !logs.data
                                ||
                                !logs.data.length
                            "
                        >

                            <td
                                colspan="7"
                                class="empty-cell"
                            >

                                <div class="empty-state">

                                    <div class="empty-icon">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M4 4H20C21.1046 4 22 4.89543 22 6V16C22 17.1046 21.1046 18 20 18H7L2 22V6C2 4.89543 2.89543 4 4 4ZM8 9L16 15M16 9L8 15"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                            />
                                        </svg>

                                    </div>


                                    <strong>
                                        پیامکی وجود ندارد
                                    </strong>

                                    <span>
                                        هنوز گزارشی برای نمایش ثبت نشده است.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- PAGINATION -->
        <div
            v-if="
                logs.links
                &&
                logs.links.length > 3
            "
            class="pagination-wrapper"
        >

            <div class="pagination-info">

                صفحه

                <strong>
                    {{ logs.current_page ?? 1 }}
                </strong>

                از

                <strong>
                    {{ logs.last_page ?? 1 }}
                </strong>

            </div>


            <div class="pagination">

                <template
                    v-for="link in logs.links"
                    :key="
                        `${link.label}-${link.url}`
                    "
                >

                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-scroll
                        class="page-button"
                        :class="{
                            active:
                                link.active,
                        }"
                        v-html="link.label"
                    />


                    <span
                        v-else
                        class="
                            page-button
                            disabled
                        "
                        v-html="link.label"
                    ></span>

                </template>

            </div>

        </div>

    </div>

</template>


<style scoped>
.sms-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding-bottom: 32px;
}


/* HEADER */

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;

    margin-bottom: 18px;
    padding: 23px 25px;

    border: 1px solid #e5ebf3;
    border-radius: 20px;

    background:
        linear-gradient(
            135deg,
            #ffffff,
            #f7faff
        );

    box-shadow:
        0 8px 28px
        rgba(15, 23, 42, .045);
}


.header-copy {
    display: flex;
    align-items: center;
    gap: 14px;
}


.header-icon {
    width: 52px;
    height: 52px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    color: #2563eb;

    background:
        linear-gradient(
            135deg,
            #eff6ff,
            #dbeafe
        );
}


.header-icon svg {
    width: 24px;
    height: 24px;
}


.eyebrow {
    color: #94a3b8;
    font-size: 9px;
    font-weight: 700;
}


.page-header h1 {
    margin: 2px 0 0;

    color: #172033;

    font-size: 23px !important;
    font-weight: 800 !important;
}


.page-header p {
    margin: 4px 0 0;

    color: #64748b;

    font-size: 10px;
}


/* STATS */

.stats-grid {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 12px;

    margin-bottom: 18px;
}


.stat-card {
    min-height: 82px;

    display: flex;
    align-items: center;

    gap: 11px;

    padding: 14px;

    border: 1px solid #e5ebf3;
    border-radius: 14px;

    background: #ffffff;

    box-shadow:
        0 5px 18px
        rgba(15, 23, 42, .035);

    transition:
        transform .18s ease,
        box-shadow .18s ease;
}


.stat-card:hover {
    transform: translateY(-3px);

    box-shadow:
        0 10px 26px
        rgba(15, 23, 42, .07);
}


.stat-icon {
    width: 39px;
    height: 39px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;
}


.stat-icon svg {
    width: 18px;
    height: 18px;
}


.stat-icon.blue {
    color: #2563eb;
    background: #eff6ff;
}


.stat-icon.green {
    color: #059669;
    background: #ecfdf5;
}


.stat-icon.amber {
    color: #d97706;
    background: #fffbeb;
}


.stat-icon.red {
    color: #dc2626;
    background: #fef2f2;
}


.stat-card > div:last-child {
    display: flex;
    flex-direction: column;
}


.stat-card span {
    color: #94a3b8;
    font-size: 8px;
}


.stat-card strong {
    margin-top: 2px;

    color: #172033;

    font-size: 17px;
    font-weight: 800;
}


/* TOOLBAR */

.toolbar {
    margin-bottom: 18px;

    padding: 14px 16px;

    border: 1px solid #e5ebf3;
    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 5px 20px
        rgba(15, 23, 42, .035);
}


.search-form {
    display: flex;
    align-items: center;

    gap: 8px;
}


.search-box {
    position: relative;

    flex: 1;

    max-width: 550px;
}


.search-box svg {
    position: absolute;

    top: 50%;
    right: 13px;

    width: 16px;
    height: 16px;

    color: #94a3b8;

    transform:
        translateY(-50%);
}


.search-box input {
    width: 100% !important;
    min-height: 41px !important;

    padding-right:
        39px !important;

    font-size: 9px !important;
}


.search-button,
.clear-button {
    min-height: 41px;

    padding: 0 15px;

    border-radius: 10px;

    font-size: 9px;
    font-weight: 700;
}


.search-button {
    border: 0;

    color: #ffffff;

    background: #172033;
}


.clear-button {
    border: 1px solid #e2e8f0;

    color: #64748b;

    background: #ffffff;
}


/* TABLE */

.table-card {
    overflow: hidden;

    border: 1px solid #e5ebf3;
    border-radius: 18px;

    background: #ffffff;

    box-shadow:
        0 8px 28px
        rgba(15, 23, 42, .045);
}


.table-heading {
    min-height: 69px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 14px 18px;

    border-bottom:
        1px solid #edf2f7;
}


.table-heading h2 {
    margin: 0;

    color: #1e293b;

    font-size: 12px;
    font-weight: 800;
}


.table-heading p {
    margin: 3px 0 0;

    color: #94a3b8;

    font-size: 8px;
}


.record-count {
    padding: 6px 9px;

    border-radius: 999px;

    color: #2563eb;

    background: #eff6ff;

    font-size: 8px;
    font-weight: 700;
}


.table-scroll {
    overflow-x: auto;
}


.premium-table {
    width: 100%;

    min-width: 1150px;

    border: 0 !important;
    border-radius: 0 !important;

    box-shadow: none !important;
}


.premium-table thead {
    background: #f8fafc;
}


.premium-table th {
    padding:
        11px 14px !important;

    border-bottom:
        1px solid #edf2f7 !important;

    color:
        #64748b !important;

    font-size:
        8px !important;

    font-weight:
        700 !important;

    text-align: right;

    white-space: nowrap;
}


.premium-table td {
    padding:
        13px 14px !important;

    border-bottom:
        1px solid #f1f5f9 !important;

    color:
        #475569 !important;

    font-size:
        9px !important;

    vertical-align: middle;
}


.premium-table tbody tr {
    transition:
        background .16s ease;
}


.premium-table tbody tr:hover {
    background:
        #fbfdff !important;
}


/* MOBILE */

.mobile-number {
    display: inline-flex;
    align-items: center;

    gap: 7px;

    color:
        #2563eb !important;

    font-weight: 700;
}


.mobile-icon {
    width: 27px;
    height: 27px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    color: #2563eb;

    background: #eff6ff;
}


.mobile-icon svg {
    width: 14px;
    height: 14px;
}


/* CONTACT */

.contact-cell,
.employee-cell {
    display: flex;
    align-items: center;

    gap: 8px;
}


.avatar,
.employee-avatar {
    width: 33px;
    height: 33px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    font-size: 10px;
    font-weight: 800;
}


.avatar {
    color: #2563eb;

    background: #eff6ff;
}


.employee-avatar {
    width: 27px;
    height: 27px;

    color: #7c3aed;

    background: #f5f3ff;

    font-size: 8px;
}


.contact-copy {
    display: flex;
    flex-direction: column;
}


.contact-copy strong {
    color: #334155;

    font-size: 9px;
}


.contact-copy span {
    margin-top: 2px;

    color: #94a3b8;

    font-size: 7px;
}


.employee-cell > span {
    color: #475569;

    font-size: 8px;
    font-weight: 600;
}


/* TEMPLATE */

.template-badge,
.manual-badge {
    display: inline-flex;

    padding: 5px 8px;

    border-radius: 8px;

    font-size: 8px;
    font-weight: 700;

    white-space: nowrap;
}


.template-badge {
    color: #4f46e5;

    background: #eef2ff;
}


.manual-badge {
    color: #64748b;

    background: #f1f5f9;
}


/* MESSAGE */

.message-preview {
    max-width: 330px;

    overflow: hidden;

    color: #475569;

    line-height: 1.85;

    display: -webkit-box;

    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
}


/* STATUS */

.status-badge {
    display: inline-flex;
    align-items: center;

    gap: 5px;

    padding: 5px 8px;

    border-radius: 999px;

    font-size: 8px;
    font-weight: 700;

    white-space: nowrap;
}


.status-badge i {
    width: 5px;
    height: 5px;

    border-radius: 50%;

    background: currentColor;
}


.status-queued {
    color: #b45309;

    background: #fffbeb;
}


.status-sent {
    color: #15803d;

    background: #f0fdf4;
}


.status-failed {
    color: #dc2626;

    background: #fef2f2;
}


.status-default {
    color: #64748b;

    background: #f1f5f9;
}


/* DATE */

.date-cell {
    display: flex;
    align-items: center;

    gap: 6px;

    color: #64748b;

    white-space: nowrap;
}


.date-cell svg {
    width: 14px;
    height: 14px;

    color: #94a3b8;
}


.muted {
    color: #cbd5e1;
}


/* EMPTY */

.empty-cell {
    padding:
        40px 20px !important;

    text-align:
        center !important;
}


.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
}


.empty-icon {
    width: 46px;
    height: 46px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 10px;

    border-radius: 13px;

    color: #64748b;

    background: #f1f5f9;
}


.empty-icon svg {
    width: 21px;
    height: 21px;
}


.empty-state strong {
    color: #475569;

    font-size: 10px;
}


.empty-state span {
    margin-top: 4px;

    color: #94a3b8;

    font-size: 8px;
}


/* PAGINATION */

.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 16px;

    margin-top: 16px;
}


.pagination-info {
    color: #94a3b8;

    font-size: 8px;
}


.pagination-info strong {
    color: #475569;
}


.pagination {
    display: flex;
    flex-wrap: wrap;

    gap: 5px;
}


.page-button {
    min-width: 32px;
    height: 32px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 8px;

    border: 1px solid #e2e8f0;
    border-radius: 8px;

    color:
        #64748b !important;

    background: #ffffff;

    font-size: 8px;
}


.page-button.active {
    color:
        #ffffff !important;

    border-color: #2563eb;

    background: #2563eb;
}


.page-button.disabled {
    opacity: .4;

    pointer-events: none;
}


/* RESPONSIVE */

@media (max-width: 1000px) {

    .stats-grid {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 700px) {

    .search-form {
        align-items: stretch;
        flex-direction: column;
    }


    .search-box {
        max-width: none;
    }


    .pagination-wrapper {
        align-items: flex-start;
        flex-direction: column;
    }

}


@media (max-width: 500px) {

    .stats-grid {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .page-header {
        padding: 20px;
    }

}
</style>
