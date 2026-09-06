<script setup>
import { Link, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import { formatPersianDateTime } from '../../utils/date'

const props = defineProps({
    followUps: Object,
    filters: Object,
    isAdmin: Boolean,
})

const search = ref(props.filters?.search ?? '')
const deleteTarget = ref(null)
const deleting = ref(false)

const statusLabels = {
    pending: 'در انتظار',
    done: 'انجام شده',
    cancelled: 'لغو شده',
}

const totalItems = computed(() =>
    props.followUps?.total
    ?? props.followUps?.data?.length
    ?? 0
)

const overdueOnPage = computed(() =>
    (props.followUps?.data ?? []).filter(isOverdue).length
)

function isOverdue(item) {
    return (
        item.status === 'pending'
        &&
        new Date(item.follow_up_at) < new Date()
    )
}

function doSearch() {
    router.get(
        '/followups',
        { search: search.value },
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
        '/followups',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}

function updateStatus(id, status) {
    router.patch(
        `/followups/${id}/status`,
        { status },
        {
            preserveScroll: true,
        }
    )
}

function requestDelete(item) {
    deleteTarget.value = item
}

function cancelDelete() {
    if (!deleting.value) {
        deleteTarget.value = null
    }
}

function confirmDelete() {
    if (!deleteTarget.value || deleting.value) {
        return
    }

    deleting.value = true

    router.delete(
        `/followups/${deleteTarget.value.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {
                deleteTarget.value = null
            },

            onFinish: () => {
                deleting.value = false
            },
        }
    )
}

function firstLetter(name) {
    return name
        ? String(name).trim().substring(0, 1)
        : '؟'
}

function statusClass(status) {
    return {
        pending: 'status-pending',
        done: 'status-done',
        cancelled: 'status-cancelled',
    }[status] ?? 'status-default'
}
</script>

<template>
    <div class="followups-page" dir="rtl">

        <section class="page-header">
            <div class="header-copy">

                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <div class="eyebrow">
                        مدیریت ارتباط با مشتری
                    </div>

                    <h1>پیگیری‌ها</h1>

                    <p>
                        برنامه‌ریزی، کنترل و مدیریت پیگیری‌های مشتریان
                    </p>
                </div>

            </div>

            <Link
                href="/followups/create"
                class="primary-button"
            >
                <svg viewBox="0 0 24 24" fill="none">
                    <path
                        d="M12 5V19M5 12H19"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                    />
                </svg>

                پیگیری جدید
            </Link>
        </section>


        <section class="summary-grid">

            <div class="summary-card">
                <div class="summary-icon blue">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M4 6H20M4 12H20M4 18H14"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <span>کل پیگیری‌ها</span>
                    <strong>{{ totalItems }}</strong>
                </div>
            </div>


            <div class="summary-card">
                <div class="summary-icon red">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M12 8V13M12 17H12.01M10.3 3.9L2.8 17C2.4 17.8 3 19 4 19H20C21 19 21.6 17.8 21.2 17L13.7 3.9C13.3 3.2 12.7 3 12 3C11.3 3 10.7 3.2 10.3 3.9Z"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <span>عقب‌افتاده در این صفحه</span>
                    <strong>{{ overdueOnPage }}</strong>
                </div>
            </div>

        </section>


        <section class="toolbar">

            <form
                class="search-form"
                @submit.prevent="doSearch"
            >
                <div class="search-box">

                    <svg viewBox="0 0 24 24" fill="none">
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
                        placeholder="جستجو نام، موبایل یا کسب‌وکار..."
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


        <section class="table-card">

            <div class="table-heading">
                <div>
                    <h2>لیست پیگیری‌ها</h2>
                    <p>
                        زمان‌بندی و وضعیت پیگیری مخاطبین
                    </p>
                </div>

                <span class="record-count">
                    {{ totalItems }} رکورد
                </span>
            </div>


            <div class="table-scroll">

                <table class="premium-table">

                    <thead>
                        <tr>
                            <th>مخاطب</th>
                            <th>کسب‌وکار</th>
                            <th>موبایل</th>
                            <th>عنوان</th>
                            <th>زمان پیگیری</th>
                            <th>وضعیت</th>

                            <th v-if="isAdmin">
                                کارمند
                            </th>

                            <th>عملیات</th>
                        </tr>
                    </thead>


                    <tbody>

                        <tr
                            v-for="item in followUps.data"
                            :key="item.id"
                            :class="{
                                'overdue-row': isOverdue(item),
                            }"
                        >

                            <td>
                                <div
                                    v-if="item.contact"
                                    class="contact-cell"
                                >

                                    <div
                                        class="avatar"
                                        :class="{
                                            'overdue-avatar':
                                                isOverdue(item),
                                        }"
                                    >
                                        {{
                                            firstLetter(
                                                item.contact.name
                                            )
                                        }}
                                    </div>

                                    <Link
                                        :href="`/contacts/${item.contact.id}`"
                                        class="contact-name"
                                    >
                                        {{ item.contact.name }}
                                    </Link>

                                </div>

                                <span v-else class="muted">
                                    —
                                </span>
                            </td>


                            <td>
                                {{
                                    item.contact?.business_name
                                    ?? '—'
                                }}
                            </td>


                            <td>
                                <a
                                    v-if="item.contact?.mobile"
                                    :href="`tel:${item.contact.mobile}`"
                                    class="mobile"
                                    dir="ltr"
                                >
                                    {{ item.contact.mobile }}
                                </a>

                                <span v-else class="muted">
                                    —
                                </span>
                            </td>


                            <td>
                                <strong class="followup-title">
                                    {{ item.title }}
                                </strong>
                            </td>


                            <td>
                                <div
                                    class="date-box"
                                    :class="{
                                        overdue:
                                            isOverdue(item),
                                    }"
                                >
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

                                    <span>
                                        {{
                                            formatPersianDateTime(
                                                item.follow_up_at
                                            )
                                        }}
                                    </span>

                                    <span
                                        v-if="isOverdue(item)"
                                        class="overdue-label"
                                    >
                                        عقب‌افتاده
                                    </span>
                                </div>
                            </td>


                            <td>
                                <span
                                    class="status-badge"
                                    :class="
                                        statusClass(item.status)
                                    "
                                >
                                    <i></i>

                                    {{
                                        statusLabels[
                                            item.status
                                        ]
                                        ?? item.status
                                    }}
                                </span>
                            </td>


                            <td v-if="isAdmin">
                                <div
                                    v-if="item.user?.name"
                                    class="employee"
                                >
                                    <span>
                                        {{
                                            firstLetter(
                                                item.user.name
                                            )
                                        }}
                                    </span>

                                    {{ item.user.name }}
                                </div>

                                <span v-else class="muted">
                                    —
                                </span>
                            </td>


                            <td>
                                <div class="actions">

                                    <button
                                        v-if="item.status !== 'done'"
                                        type="button"
                                        class="action done-action"
                                        title="انجام شد"
                                        @click="
                                            updateStatus(
                                                item.id,
                                                'done'
                                            )
                                        "
                                    >
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M5 12L10 17L19 8"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>


                                    <button
                                        v-if="item.status !== 'cancelled'"
                                        type="button"
                                        class="action cancel-action"
                                        title="لغو"
                                        @click="
                                            updateStatus(
                                                item.id,
                                                'cancelled'
                                            )
                                        "
                                    >
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M6 6L18 18M18 6L6 18"
                                                stroke="currentColor"
                                                stroke-width="1.9"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                    </button>


                                    <button
                                        v-if="item.status !== 'pending'"
                                        type="button"
                                        class="action restore-action"
                                        title="بازگردانی"
                                        @click="
                                            updateStatus(
                                                item.id,
                                                'pending'
                                            )
                                        "
                                    >
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M4 4V10H10M5 9C6.4 5.5 9 4 12 4C16.4 4 20 7.6 20 12C20 16.4 16.4 20 12 20C8.5 20 5.5 17.8 4.4 14.7"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>


                                    <button
                                        type="button"
                                        class="action delete-action"
                                        title="حذف"
                                        @click="requestDelete(item)"
                                    >
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M4 7H20M9 11V17M15 11V17M6 7L7 21H17L18 7M9 7V4H15V7"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                            />
                                        </svg>
                                    </button>

                                </div>
                            </td>

                        </tr>


                        <tr v-if="!followUps.data.length">
                            <td
                                :colspan="isAdmin ? 8 : 7"
                                class="empty-cell"
                            >
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <svg viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M12 8V12L15 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            />
                                        </svg>
                                    </div>

                                    <strong>
                                        پیگیری‌ای یافت نشد
                                    </strong>

                                    <span>
                                        جستجو را تغییر دهید یا یک پیگیری جدید ایجاد کنید.
                                    </span>
                                </div>
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <div
            v-if="
                followUps.links
                && followUps.links.length > 3
            "
            class="pagination-wrapper"
        >

            <div class="pagination-info">
                صفحه
                <strong>{{ followUps.current_page }}</strong>
                از
                <strong>{{ followUps.last_page }}</strong>
            </div>


            <div class="pagination">
                <template
                    v-for="link in followUps.links"
                    :key="`${link.label}-${link.url}`"
                >

                    <Link
                        v-if="link.url"
                        :href="link.url"
                        preserve-scroll
                        class="page-button"
                        :class="{
                            active: link.active,
                        }"
                        v-html="link.label"
                    />

                    <span
                        v-else
                        class="page-button disabled"
                        v-html="link.label"
                    ></span>

                </template>
            </div>

        </div>


        <Teleport to="body">

            <div
                v-if="deleteTarget"
                class="modal-overlay"
                dir="rtl"
                @click.self="cancelDelete"
            >

                <div class="delete-modal">

                    <div class="modal-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 9V13M12 17H12.01M10.3 3.9L2.8 17C2.4 17.8 3 19 4 19H20C21 19 21.6 17.8 21.2 17L13.7 3.9C13.3 3.2 12.7 3 12 3C11.3 3 10.7 3.2 10.3 3.9Z"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <h3>حذف پیگیری؟</h3>

                    <p>
                        پیگیری
                        <strong>
                            «{{ deleteTarget.title }}»
                        </strong>
                        حذف شود؟
                    </p>

                    <div class="modal-warning">
                        این عملیات قابل بازگشت نیست.
                    </div>

                    <div class="modal-actions">

                        <button
                            type="button"
                            class="modal-cancel"
                            :disabled="deleting"
                            @click="cancelDelete"
                        >
                            انصراف
                        </button>

                        <button
                            type="button"
                            class="modal-delete"
                            :disabled="deleting"
                            @click="confirmDelete"
                        >
                            {{
                                deleting
                                    ? 'در حال حذف...'
                                    : 'حذف پیگیری'
                            }}
                        </button>

                    </div>

                </div>
            </div>

        </Teleport>

    </div>
</template>


<style scoped>
.followups-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding-bottom: 32px;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 18px;
    padding: 23px 25px;
    border: 1px solid #e5ebf3;
    border-radius: 20px;
    background: linear-gradient(135deg, #fff, #f7faff);
    box-shadow: 0 8px 28px rgba(15,23,42,.045);
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
    background: linear-gradient(135deg,#eff6ff,#dbeafe);
}

.header-icon svg {
    width: 24px;
    height: 24px;
}

.eyebrow {
    margin-bottom: 3px;
    color: #94a3b8;
    font-size: 9px;
    font-weight: 700;
}

.page-header h1 {
    margin: 0;
    color: #172033;
    font-size: 23px !important;
    font-weight: 800 !important;
}

.page-header p {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 10px;
}

.primary-button {
    min-height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 0 15px;
    border-radius: 11px;
    color: #fff !important;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    box-shadow: 0 7px 18px rgba(37,99,235,.21);
    font-size: 9px;
    font-weight: 700;
    transition: .18s ease;
}

.primary-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(37,99,235,.27);
}

.primary-button svg {
    width: 16px;
    height: 16px;
}

.summary-grid {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    gap: 12px;
    margin-bottom: 18px;
}

.summary-card {
    display: flex;
    align-items: center;
    gap: 11px;
    min-height: 78px;
    padding: 13px 15px;
    border: 1px solid #e5ebf3;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 5px 18px rgba(15,23,42,.035);
}

.summary-icon {
    width: 39px;
    height: 39px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 11px;
}

.summary-icon svg {
    width: 18px;
    height: 18px;
}

.summary-icon.blue {
    color: #2563eb;
    background: #eff6ff;
}

.summary-icon.red {
    color: #dc2626;
    background: #fef2f2;
}

.summary-card > div:last-child {
    display: flex;
    flex-direction: column;
}

.summary-card span {
    color: #94a3b8;
    font-size: 8px;
}

.summary-card strong {
    margin-top: 2px;
    color: #172033;
    font-size: 17px;
    font-weight: 800;
}

.toolbar {
    margin-bottom: 18px;
    padding: 14px 16px;
    border: 1px solid #e5ebf3;
    border-radius: 15px;
    background: #fff;
    box-shadow: 0 5px 20px rgba(15,23,42,.035);
}

.search-form {
    display: flex;
    align-items: center;
    gap: 8px;
}

.search-box {
    position: relative;
    flex: 1;
    max-width: 530px;
}

.search-box svg {
    position: absolute;
    top: 50%;
    right: 13px;
    width: 16px;
    height: 16px;
    color: #94a3b8;
    transform: translateY(-50%);
}

.search-box input {
    width: 100% !important;
    min-height: 41px !important;
    padding-right: 39px !important;
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
    color: #fff;
    background: #172033;
}

.clear-button {
    border: 1px solid #e2e8f0;
    color: #64748b;
    background: #fff;
}

.table-card {
    overflow: hidden;
    border: 1px solid #e5ebf3;
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 8px 28px rgba(15,23,42,.045);
}

.table-heading {
    min-height: 69px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 14px 18px;
    border-bottom: 1px solid #edf2f7;
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
    padding: 6px 10px;
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
    min-width: 1050px;
    border: 0 !important;
    border-radius: 0 !important;
    box-shadow: none !important;
}

.premium-table thead {
    background: #f8fafc;
}

.premium-table th {
    padding: 11px 14px !important;
    border-bottom: 1px solid #edf2f7 !important;
    color: #64748b !important;
    font-size: 8px !important;
    font-weight: 700 !important;
    text-align: right;
    white-space: nowrap;
}

.premium-table td {
    padding: 13px 14px !important;
    border-bottom: 1px solid #f1f5f9 !important;
    color: #475569 !important;
    font-size: 9px !important;
    vertical-align: middle;
}

.premium-table tbody tr {
    transition: background .16s ease;
}

.premium-table tbody tr:hover {
    background: #fbfdff !important;
}

.premium-table tbody tr.overdue-row {
    background: linear-gradient(90deg,#fff,#fffafa) !important;
}

.contact-cell,
.employee {
    display: flex;
    align-items: center;
    gap: 8px;
}

.avatar {
    width: 33px;
    height: 33px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    color: #2563eb;
    background: #eff6ff;
    font-size: 10px;
    font-weight: 800;
}

.overdue-avatar {
    color: #dc2626;
    background: #fef2f2;
}

.contact-name {
    color: #172033 !important;
    font-weight: 700;
}

.contact-name:hover {
    color: #2563eb !important;
}

.mobile {
    color: #2563eb !important;
    font-weight: 600;
}

.followup-title {
    color: #334155;
    font-size: 9px;
}

.date-box {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
    white-space: nowrap;
}

.date-box svg {
    width: 14px;
    height: 14px;
    color: #94a3b8;
}

.date-box.overdue {
    color: #dc2626;
    font-weight: 700;
}

.date-box.overdue svg {
    color: #dc2626;
}

.overdue-label {
    padding: 3px 6px;
    border-radius: 999px;
    color: #dc2626;
    background: #fef2f2;
    font-size: 7px;
}

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

.status-pending {
    color: #b45309;
    background: #fffbeb;
}

.status-done {
    color: #15803d;
    background: #f0fdf4;
}

.status-cancelled {
    color: #dc2626;
    background: #fef2f2;
}

.status-default {
    color: #64748b;
    background: #f1f5f9;
}

.employee span {
    width: 25px;
    height: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: #7c3aed;
    background: #f5f3ff;
    font-size: 8px;
    font-weight: 800;
}

.muted {
    color: #cbd5e1;
}

.actions {
    display: flex;
    align-items: center;
    gap: 5px;
}

.action {
    width: 30px;
    height: 30px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0 !important;
    border: 1px solid transparent;
    border-radius: 9px !important;
    transition: .16s ease;
}

.action:hover {
    transform: translateY(-2px);
}

.action svg {
    width: 14px;
    height: 14px;
}

.done-action {
    color: #059669;
    background: #ecfdf5;
}

.cancel-action {
    color: #d97706;
    background: #fffbeb;
}

.restore-action {
    color: #2563eb;
    background: #eff6ff;
}

.delete-action {
    color: #dc2626;
    background: #fef2f2;
}

.empty-cell {
    padding: 40px 20px !important;
    text-align: center !important;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.empty-icon {
    width: 45px;
    height: 45px;
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
    align-items: center;
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
    color: #64748b !important;
    background: #fff;
    font-size: 8px;
}

.page-button.active {
    color: #fff !important;
    border-color: #2563eb;
    background: #2563eb;
}

.page-button.disabled {
    opacity: .4;
    pointer-events: none;
}

.modal-overlay {
    position: fixed;
    z-index: 9999;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: rgba(15,23,42,.48);
    backdrop-filter: blur(5px);
}

.delete-modal {
    width: 100%;
    max-width: 380px;
    padding: 25px;
    border-radius: 19px;
    background: #fff;
    box-shadow: 0 25px 70px rgba(15,23,42,.25);
    text-align: center;
}

.modal-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 13px;
    border-radius: 15px;
    color: #dc2626;
    background: #fef2f2;
}

.modal-icon svg {
    width: 25px;
    height: 25px;
}

.delete-modal h3 {
    margin: 0;
    color: #172033;
    font-size: 14px;
    font-weight: 800;
}

.delete-modal p {
    margin: 8px 0 0;
    color: #64748b;
    font-size: 9px;
    line-height: 1.9;
}

.delete-modal p strong {
    color: #172033;
}

.modal-warning {
    margin-top: 10px;
    padding: 7px;
    border-radius: 8px;
    color: #dc2626;
    background: #fef2f2;
    font-size: 8px;
}

.modal-actions {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-top: 18px;
}

.modal-actions button {
    min-height: 39px;
    border-radius: 9px;
    font-size: 9px;
    font-weight: 700;
}

.modal-cancel {
    border: 1px solid #e2e8f0;
    color: #64748b;
    background: #fff;
}

.modal-delete {
    border: 0;
    color: #fff;
    background: linear-gradient(135deg,#ef4444,#dc2626);
}

@media (max-width: 750px) {
    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .primary-button {
        width: 100%;
    }

    .summary-grid {
        grid-template-columns: 1fr;
    }

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
</style>
