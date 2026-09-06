<script setup>
import {
    Link,
    router,
} from '@inertiajs/vue3'

import {
    computed,
    ref,
} from 'vue'


const props = defineProps({
    contacts: Object,
    filters: Object,
    isAdmin: Boolean,
})


const search = ref(
    props.filters?.search ?? ''
)

const deleteTarget = ref(null)

const deleting = ref(false)


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


const totalContacts = computed(() =>
    props.contacts?.total
    ?? props.contacts?.data?.length
    ?? 0
)


const exportUrl = computed(() => {

    const params =
        new URLSearchParams()

    const currentSearch =
        props.filters?.search ?? ''

    if (currentSearch) {
        params.set(
            'search',
            currentSearch
        )
    }

    const query =
        params.toString()

    return query
        ? `/contacts/export?${query}`
        : '/contacts/export'
})


const doSearch = () => {

    router.get(
        '/contacts',
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
        '/contacts',
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    )
}


const requestDelete = (contact) => {

    deleteTarget.value =
        contact
}


const cancelDelete = () => {

    if (deleting.value) {
        return
    }

    deleteTarget.value =
        null
}


const confirmDelete = () => {

    if (
        !deleteTarget.value
        ||
        deleting.value
    ) {
        return
    }

    deleting.value = true

    router.delete(
        `/contacts/${deleteTarget.value.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {
                deleteTarget.value =
                    null
            },

            onFinish: () => {
                deleting.value =
                    false
            },
        }
    )
}


const firstLetter = (name) => {

    if (!name) {
        return '؟'
    }

    return String(name)
        .trim()
        .substring(0, 1)
}


const statusClass = (status) => {

    const classes = {

        new:
            'status-new',

        contacted:
            'status-contacted',

        interested:
            'status-interested',

        follow_up:
            'status-followup',

        demo_sent:
            'status-demo',

        customer:
            'status-customer',

        active:
            'status-customer',

        rejected:
            'status-rejected',

        inactive:
            'status-rejected',

        no_answer:
            'status-noanswer',
    }

    return classes[status]
        ?? 'status-default'
}
</script>


<template>

    <div
        class="contacts-page"
        dir="rtl"
    >

        <!-- =================================================
             HEADER
        ================================================== -->

        <section class="contacts-header">

            <div class="header-copy">

                <div class="header-icon">

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

                    <div class="page-eyebrow">
                        مدیریت ارتباط با مشتری
                    </div>

                    <h1 class="page-title">
                        مخاطبین
                    </h1>

                    <p class="page-description">
                        مدیریت مخاطبین، کسب‌وکارها و اطلاعات تماس
                    </p>

                </div>

            </div>


            <div class="header-actions">

                <a
                    :href="exportUrl"
                    class="action-button action-secondary"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M12 3V15M12 15L8 11M12 15L16 11M5 21H19C20.1046 21 21 20.1046 21 19V17M3 17V19C3 20.1046 3.89543 21 5 21"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    خروجی Excel

                </a>


                <Link
                    href="/contacts/import"
                    class="action-button action-secondary"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M12 21V9M12 9L8 13M12 9L16 13M5 3H19C20.1046 3 21 3.89543 21 5V7M3 7V5C3 3.89543 3.89543 3 5 3"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    ورود از Excel

                </Link>


                <Link
                    href="/contacts/create"
                    class="action-button action-primary"
                >

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

                    مخاطب جدید

                </Link>

            </div>

        </section>


        <!-- =================================================
             SEARCH + META
        ================================================== -->

        <section class="toolbar-card">

            <form
                class="search-form"
                @submit.prevent="doSearch"
            >

                <div class="search-box">

                    <span class="search-icon">

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

                    </span>


                    <input
                        v-model="search"
                        type="text"
                        placeholder="جستجو بر اساس نام، موبایل، تلفن یا کسب‌وکار..."
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


            <div class="result-count">

                <div class="result-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M8 6H21M8 12H21M8 18H21M3 6H3.01M3 12H3.01M3 18H3.01"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        />
                    </svg>

                </div>

                <div>

                    <span>
                        تعداد مخاطبین
                    </span>

                    <strong>
                        {{ totalContacts }}
                    </strong>

                </div>

            </div>

        </section>


        <!-- =================================================
             TABLE
        ================================================== -->

        <section class="contacts-panel">

            <div class="panel-heading">

                <div>

                    <h2>
                        لیست مخاطبین
                    </h2>

                    <p>
                        اطلاعات و وضعیت مخاطبین ثبت‌شده در CRM
                    </p>

                </div>


                <div class="panel-counter">

                    {{ totalContacts }}

                    <span>
                        رکورد
                    </span>

                </div>

            </div>


            <div class="table-scroll">

                <table class="contacts-table">

                    <thead>

                        <tr>
                            <th>مخاطب</th>
                            <th>کسب‌وکار</th>
                            <th>موبایل</th>
                            <th>تلفن</th>
                            <th>شهر</th>
                            <th>وضعیت</th>
                            <th>مسئول</th>
                            <th>عملیات</th>
                        </tr>

                    </thead>


                    <tbody>

                        <tr
                            v-for="
                                contact in
                                contacts.data
                            "
                            :key="contact.id"
                        >

                            <!-- Contact -->
                            <td>

                                <div class="contact-main">

                                    <div class="contact-avatar">
                                        {{
                                            firstLetter(
                                                contact.name
                                            )
                                        }}
                                    </div>


                                    <div class="contact-text">

                                        <Link
                                            :href="
                                                `/contacts/${contact.id}`
                                            "
                                            class="contact-name"
                                        >
                                            {{
                                                contact.name
                                                || 'بدون نام'
                                            }}
                                        </Link>


                                        <span class="contact-id">
                                            شناسه #{{ contact.id }}
                                        </span>

                                    </div>

                                </div>

                            </td>


                            <!-- Business -->
                            <td>

                                <span
                                    v-if="
                                        contact.business_name
                                    "
                                    class="business-name"
                                >
                                    {{
                                        contact.business_name
                                    }}
                                </span>

                                <span
                                    v-else
                                    class="muted-value"
                                >
                                    —
                                </span>

                            </td>


                            <!-- Mobile -->
                            <td>

                                <a
                                    v-if="contact.mobile"
                                    :href="
                                        `tel:${contact.mobile}`
                                    "
                                    class="phone-link"
                                    dir="ltr"
                                >
                                    {{ contact.mobile }}
                                </a>

                                <span
                                    v-else
                                    class="muted-value"
                                >
                                    —
                                </span>

                            </td>


                            <!-- Phone -->
                            <td>

                                <a
                                    v-if="contact.phone"
                                    :href="
                                        `tel:${contact.phone}`
                                    "
                                    class="secondary-phone"
                                    dir="ltr"
                                >
                                    {{ contact.phone }}
                                </a>

                                <span
                                    v-else
                                    class="muted-value"
                                >
                                    —
                                </span>

                            </td>


                            <!-- City -->
                            <td>

                                <div
                                    v-if="contact.city"
                                    class="city-cell"
                                >

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M20 10C20 15 12 21 12 21C12 21 4 15 4 10C4 5.58172 7.58172 2 12 2C16.4183 2 20 5.58172 20 10ZM12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />
                                    </svg>

                                    {{ contact.city }}

                                </div>

                                <span
                                    v-else
                                    class="muted-value"
                                >
                                    —
                                </span>

                            </td>


                            <!-- Status -->
                            <td>

                                <span
                                    class="status-badge"
                                    :class="
                                        statusClass(
                                            contact.status
                                        )
                                    "
                                >

                                    <span class="status-dot"></span>

                                    {{
                                        statusLabels[
                                            contact.status
                                        ]
                                        ?? contact.status
                                        ?? 'نامشخص'
                                    }}

                                </span>

                            </td>


                            <!-- Assigned -->
                            <td>

                                <div
                                    v-if="
                                        contact
                                            .assigned_user
                                            ?.name
                                    "
                                    class="assigned-user"
                                >

                                    <div class="assigned-avatar">
                                        {{
                                            firstLetter(
                                                contact
                                                    .assigned_user
                                                    .name
                                            )
                                        }}
                                    </div>

                                    <span>
                                        {{
                                            contact
                                                .assigned_user
                                                .name
                                        }}
                                    </span>

                                </div>


                                <span
                                    v-else
                                    class="unassigned"
                                >
                                    تخصیص‌نیافته
                                </span>

                            </td>


                            <!-- Actions -->
                            <td>

                                <div class="row-actions">

                                    <Link
                                        :href="
                                            `/contacts/${contact.id}`
                                        "
                                        class="icon-action view-action"
                                        title="مشاهده"
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M2 12C2 12 5.5 5 12 5C18.5 5 22 12 22 12C22 12 18.5 19 12 19C5.5 19 2 12 2 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                            />
                                        </svg>

                                    </Link>


                                    <Link
                                        :href="
                                            `/contacts/${contact.id}/edit`
                                        "
                                        class="icon-action edit-action"
                                        title="ویرایش"
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M13.5 6.5L17.5 10.5M4 20H8L19 9C20.1046 7.89543 20.1046 6.10457 19 5C17.8954 3.89543 16.1046 3.89543 15 5L4 16V20Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>

                                    </Link>


                                    <button
                                        v-if="isAdmin"
                                        type="button"
                                        class="icon-action delete-action"
                                        title="حذف"
                                        @click="
                                            requestDelete(
                                                contact
                                            )
                                        "
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M4 7H20M9 11V17M15 11V17M6 7L7 21H17L18 7M9 7V4H15V7"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>

                                    </button>

                                </div>

                            </td>

                        </tr>


                        <!-- Empty -->
                        <tr
                            v-if="
                                !contacts.data
                                ||
                                !contacts.data.length
                            "
                        >

                            <td
                                colspan="8"
                                class="empty-cell"
                            >

                                <div class="empty-state">

                                    <div class="empty-icon">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M16 21V19C16 16.7909 14.2091 15 12 15H6C3.79086 15 2 16.7909 2 19V21M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11ZM17 11L22 16M22 11L17 16"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                            />
                                        </svg>

                                    </div>

                                    <strong>
                                        مخاطبی یافت نشد
                                    </strong>

                                    <span>
                                        عبارت جستجو را تغییر دهید یا مخاطب جدید ثبت کنید.
                                    </span>

                                </div>

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </section>


        <!-- =================================================
             PAGINATION
        ================================================== -->

        <div
            v-if="
                contacts.links
                &&
                contacts.links.length > 3
            "
            class="pagination-wrapper"
        >

            <div class="pagination-info">

                صفحه

                <strong>
                    {{ contacts.current_page ?? 1 }}
                </strong>

                از

                <strong>
                    {{ contacts.last_page ?? 1 }}
                </strong>

            </div>


            <div class="pagination">

                <template
                    v-for="
                        link in
                        contacts.links
                    "
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
                            'page-active':
                                link.active,
                        }"
                        v-html="link.label"
                    />


                    <span
                        v-else
                        class="
                            page-button
                            page-disabled
                        "
                        v-html="link.label"
                    ></span>

                </template>

            </div>

        </div>


        <!-- =================================================
             DELETE MODAL
        ================================================== -->

        <Teleport to="body">

            <div
                v-if="deleteTarget"
                class="delete-overlay"
                dir="rtl"
                @click.self="cancelDelete"
            >

                <div class="delete-modal">

                    <div class="delete-modal-icon">

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


                    <h3>
                        حذف مخاطب؟
                    </h3>


                    <p>
                        آیا از حذف

                        <strong>
                            {{ deleteTarget.name }}
                        </strong>

                        مطمئن هستید؟
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
                                    : 'بله، حذف شود'
                            }}

                        </button>

                    </div>

                </div>

            </div>

        </Teleport>

    </div>

</template>


<style scoped>

/* =========================================================
   PAGE
   ========================================================= */

.contacts-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding-bottom: 32px;
}


/* =========================================================
   HEADER
   ========================================================= */

.contacts-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 24px;

    margin-bottom: 20px;

    padding: 24px 26px;

    border: 1px solid #e5ebf3;
    border-radius: 20px;

    background:
        linear-gradient(
            135deg,
            #ffffff 0%,
            #f8fbff 100%
        );

    box-shadow:
        0 9px 30px
        rgba(15, 23, 42, 0.045);
}


.header-copy {
    display: flex;
    align-items: center;

    gap: 15px;
}


.header-icon {
    width: 54px;
    height: 54px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 16px;

    color: #2563eb;

    background:
        linear-gradient(
            135deg,
            #eff6ff,
            #dbeafe
        );
}


.header-icon svg {
    width: 25px;
    height: 25px;
}


.page-eyebrow {
    margin-bottom: 3px;

    color: #94a3b8;

    font-size: 10px;
    font-weight: 600;
}


.page-title {
    margin: 0;

    color: #172033;

    font-size: 24px !important;
    font-weight: 800 !important;
}


.page-description {
    margin: 4px 0 0;

    color: #64748b;

    font-size: 11px;

    line-height: 1.8;
}


.header-actions {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 9px;
}


.action-button {
    min-height: 43px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 8px;

    padding: 0 14px;

    border-radius: 11px;

    font-size: 11px;
    font-weight: 700;

    transition:
        transform 0.18s ease,
        box-shadow 0.18s ease,
        background 0.18s ease;
}


.action-button:hover {
    transform:
        translateY(-2px);
}


.action-button svg {
    width: 17px;
    height: 17px;
}


.action-primary {
    color: #ffffff;

    border: 1px solid #2563eb;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );

    box-shadow:
        0 7px 18px
        rgba(37, 99, 235, 0.20);
}


.action-primary:hover {
    box-shadow:
        0 10px 23px
        rgba(37, 99, 235, 0.26);
}


.action-secondary {
    color: #475569;

    border: 1px solid #e2e8f0;

    background: #ffffff;
}


.action-secondary:hover {
    color: #2563eb;

    border-color: #bfdbfe;

    background: #f8fbff;

    box-shadow:
        0 7px 18px
        rgba(15, 23, 42, 0.06);
}


/* =========================================================
   TOOLBAR
   ========================================================= */

.toolbar-card {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 20px;

    margin-bottom: 20px;

    padding: 15px 17px;

    border: 1px solid #e5ebf3;
    border-radius: 16px;

    background: #ffffff;

    box-shadow:
        0 6px 22px
        rgba(15, 23, 42, 0.035);
}


.search-form {
    flex: 1;

    display: flex;
    align-items: center;

    gap: 9px;
}


.search-box {
    position: relative;

    width: 100%;
    max-width: 520px;
}


.search-box input {
    width: 100% !important;
    min-height: 43px !important;

    padding:
        9px 42px 9px 12px !important;

    border:
        1px solid #dce3ed !important;

    border-radius:
        11px !important;

    background:
        #f9fbfd !important;

    color:
        #172033 !important;

    font-size:
        11px !important;

    transition:
        border-color 0.18s ease,
        box-shadow 0.18s ease,
        background 0.18s ease;
}


.search-box input:focus {
    border-color:
        #93c5fd !important;

    background:
        #ffffff !important;

    box-shadow:
        0 0 0 3px
        rgba(37, 99, 235, 0.08) !important;
}


.search-icon {
    position: absolute;

    z-index: 2;

    top: 50%;
    right: 14px;

    width: 17px;
    height: 17px;

    color: #94a3b8;

    transform:
        translateY(-50%);
}


.search-icon svg {
    width: 100%;
    height: 100%;
}


.search-button {
    min-height: 43px;

    padding: 0 18px;

    border: 0;
    border-radius: 10px;

    color: #ffffff;

    background: #172033;

    font-size: 11px;
    font-weight: 700;

    box-shadow:
        0 5px 13px
        rgba(15, 23, 42, 0.13);
}


.search-button:hover {
    background: #0f172a;
}


.clear-button {
    min-height: 43px;

    padding: 0 14px;

    border: 1px solid #e2e8f0;
    border-radius: 10px;

    color: #64748b;

    background: #ffffff;

    font-size: 10px;
    font-weight: 700;
}


.clear-button:hover {
    color: #dc2626;

    border-color: #fecaca;

    background: #fef2f2;
}


.result-count {
    flex-shrink: 0;

    display: flex;
    align-items: center;

    gap: 9px;

    padding-right: 18px;

    border-right:
        1px solid #edf2f7;
}


.result-icon {
    width: 35px;
    height: 35px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: #2563eb;

    background: #eff6ff;
}


.result-icon svg {
    width: 17px;
    height: 17px;
}


.result-count > div:last-child {
    display: flex;
    flex-direction: column;
}


.result-count span {
    color: #94a3b8;

    font-size: 9px;
}


.result-count strong {
    margin-top: 1px;

    color: #172033;

    font-size: 15px;
    font-weight: 800;
}


/* =========================================================
   PANEL
   ========================================================= */

.contacts-panel {
    overflow: hidden;

    border: 1px solid #e5ebf3;
    border-radius: 18px;

    background: #ffffff;

    box-shadow:
        0 9px 30px
        rgba(15, 23, 42, 0.045);
}


.panel-heading {
    min-height: 72px;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 16px;

    padding: 15px 19px;

    border-bottom:
        1px solid #edf2f7;
}


.panel-heading h2 {
    margin: 0;

    color: #1e293b;

    font-size: 14px;
    font-weight: 800;
}


.panel-heading p {
    margin: 4px 0 0;

    color: #94a3b8;

    font-size: 10px;
}


.panel-counter {
    min-width: 62px;

    padding: 7px 11px;

    border: 1px solid #dbeafe;
    border-radius: 999px;

    color: #2563eb;

    background: #eff6ff;

    text-align: center;

    font-size: 11px;
    font-weight: 800;
}


.panel-counter span {
    margin-right: 3px;

    font-size: 9px;
    font-weight: 600;
}


/* =========================================================
   TABLE
   ========================================================= */

.table-scroll {
    width: 100%;
    overflow-x: auto;
}


.contacts-table {
    width: 100%;
    min-width: 1080px;

    border: 0 !important;
    border-radius: 0 !important;

    box-shadow: none !important;
}


.contacts-table thead {
    background: #f8fafc;
}


.contacts-table th {
    padding:
        12px 16px !important;

    border-bottom:
        1px solid #edf2f7 !important;

    color:
        #64748b !important;

    background: #f8fafc;

    font-size:
        10px !important;

    font-weight:
        700 !important;

    white-space: nowrap;

    text-align: right;
}


.contacts-table td {
    padding:
        13px 16px !important;

    border-bottom:
        1px solid #f1f5f9 !important;

    color:
        #475569 !important;

    font-size:
        11px !important;

    vertical-align: middle;
}


.contacts-table tbody tr {
    transition:
        background 0.16s ease,
        box-shadow 0.16s ease;
}


.contacts-table tbody tr:hover {
    background:
        #fbfdff !important;
}


.contacts-table tbody tr:last-child td {
    border-bottom:
        0 !important;
}


/* =========================================================
   CONTACT CELL
   ========================================================= */

.contact-main {
    display: flex;
    align-items: center;

    gap: 10px;
}


.contact-avatar {
    width: 37px;
    height: 37px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

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


.contact-text {
    min-width: 0;

    display: flex;
    flex-direction: column;
}


.contact-name {
    max-width: 160px;

    overflow: hidden;

    color:
        #172033 !important;

    font-size: 11px;

    font-weight:
        700 !important;

    white-space: nowrap;

    text-overflow: ellipsis;

    transition:
        color 0.16s ease;
}


.contact-name:hover {
    color:
        #2563eb !important;
}


.contact-id {
    margin-top: 2px;

    color: #a0aec0;

    font-size: 8px;
}


.business-name {
    color: #334155;

    font-weight: 600;
}


.phone-link {
    display: inline-block;

    color:
        #2563eb !important;

    font-weight:
        600 !important;
}


.phone-link:hover {
    text-decoration: underline;
}


.secondary-phone {
    display: inline-block;

    color:
        #64748b !important;
}


.muted-value {
    color: #cbd5e1;
}


.city-cell {
    display: flex;
    align-items: center;

    gap: 5px;

    color: #64748b;
}


.city-cell svg {
    width: 14px;
    height: 14px;

    color: #94a3b8;
}


/* =========================================================
   STATUS
   ========================================================= */

.status-badge {
    display: inline-flex;
    align-items: center;

    gap: 6px;

    padding: 6px 9px;

    border-radius: 999px;

    font-size: 9px;
    font-weight: 700;

    white-space: nowrap;
}


.status-dot {
    width: 5px;
    height: 5px;

    border-radius: 50%;

    background: currentColor;
}


.status-new {
    color: #475569;
    background: #f1f5f9;
}


.status-contacted {
    color: #2563eb;
    background: #eff6ff;
}


.status-interested {
    color: #7c3aed;
    background: #f5f3ff;
}


.status-followup {
    color: #d97706;
    background: #fffbeb;
}


.status-demo {
    color: #4f46e5;
    background: #eef2ff;
}


.status-customer {
    color: #15803d;
    background: #f0fdf4;
}


.status-rejected {
    color: #dc2626;
    background: #fef2f2;
}


.status-noanswer {
    color: #c2410c;
    background: #fff7ed;
}


.status-default {
    color: #64748b;
    background: #f8fafc;
}


/* =========================================================
   ASSIGNMENT
   ========================================================= */

.assigned-user {
    display: flex;
    align-items: center;

    gap: 7px;

    color: #475569;

    font-size: 10px;
    font-weight: 600;
}


.assigned-avatar {
    width: 26px;
    height: 26px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    color: #7c3aed;

    background: #f5f3ff;

    font-size: 9px;
    font-weight: 800;
}


.unassigned {
    display: inline-flex;

    padding:
        5px 8px;

    border-radius: 7px;

    color: #94a3b8;

    background: #f8fafc;

    font-size: 8px;
}


/* =========================================================
   ROW ACTIONS
   ========================================================= */

.row-actions {
    display: flex;
    align-items: center;

    gap: 6px;
}


.icon-action {
    width: 31px;
    height: 31px;

    flex-shrink: 0;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 !important;

    border: 1px solid transparent;
    border-radius: 9px !important;

    transition:
        transform 0.16s ease,
        background 0.16s ease,
        border-color 0.16s ease;
}


.icon-action:hover {
    transform:
        translateY(-2px);
}


.icon-action svg {
    width: 15px;
    height: 15px;
}


.view-action {
    color:
        #059669 !important;

    background:
        #ecfdf5;
}


.view-action:hover {
    border-color: #a7f3d0;

    background: #d1fae5;
}


.edit-action {
    color:
        #2563eb !important;

    background:
        #eff6ff;
}


.edit-action:hover {
    border-color: #bfdbfe;

    background: #dbeafe;
}


.delete-action {
    color:
        #dc2626 !important;

    background:
        #fef2f2;
}


.delete-action:hover {
    border-color: #fecaca;

    background: #fee2e2;
}


/* =========================================================
   EMPTY
   ========================================================= */

.empty-cell {
    padding:
        45px 20px !important;

    text-align:
        center !important;
}


.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;

    color: #94a3b8;
}


.empty-icon {
    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 11px;

    border-radius: 14px;

    color: #64748b;

    background: #f1f5f9;
}


.empty-icon svg {
    width: 23px;
    height: 23px;
}


.empty-state strong {
    color: #475569;

    font-size: 12px;
}


.empty-state span {
    margin-top: 5px;

    font-size: 9px;
}


/* =========================================================
   PAGINATION
   ========================================================= */

.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 18px;

    margin-top: 18px;

    padding: 3px 2px;
}


.pagination-info {
    color: #94a3b8;

    font-size: 10px;
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
    min-width: 33px;
    height: 33px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 9px;

    border: 1px solid #e2e8f0;
    border-radius: 9px;

    color: #64748b !important;

    background: #ffffff;

    font-size: 9px;

    transition:
        transform 0.16s ease,
        border-color 0.16s ease,
        background 0.16s ease;
}


.page-button:hover {
    transform:
        translateY(-1px);

    border-color: #bfdbfe;

    color:
        #2563eb !important;

    background: #eff6ff;
}


.page-active {
    color:
        #ffffff !important;

    border-color: #2563eb;

    background: #2563eb;

    box-shadow:
        0 5px 12px
        rgba(37, 99, 235, 0.18);
}


.page-active:hover {
    color:
        #ffffff !important;

    background: #1d4ed8;
}


.page-disabled {
    opacity: 0.42;

    pointer-events: none;
}


/* =========================================================
   DELETE MODAL
   ========================================================= */

.delete-overlay {
    position: fixed;

    z-index: 9999;

    inset: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background:
        rgba(15, 23, 42, 0.48);

    backdrop-filter:
        blur(5px);
}


.delete-modal {
    width: 100%;
    max-width: 390px;

    padding: 27px;

    border:
        1px solid
        rgba(255, 255, 255, 0.8);

    border-radius: 20px;

    background: #ffffff;

    box-shadow:
        0 25px 70px
        rgba(15, 23, 42, 0.25);

    text-align: center;

    animation:
        modalIn 0.2s ease;
}


@keyframes modalIn {

    from {
        opacity: 0;

        transform:
            translateY(8px)
            scale(0.97);
    }

    to {
        opacity: 1;

        transform:
            translateY(0)
            scale(1);
    }
}


.delete-modal-icon {
    width: 54px;
    height: 54px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin:
        0 auto 14px;

    border-radius: 16px;

    color: #dc2626;

    background: #fef2f2;
}


.delete-modal-icon svg {
    width: 26px;
    height: 26px;
}


.delete-modal h3 {
    margin: 0;

    color: #172033;

    font-size: 16px;
    font-weight: 800;
}


.delete-modal p {
    margin:
        9px 0 0;

    color: #64748b;

    font-size: 11px;

    line-height: 1.9;
}


.delete-modal p strong {
    color: #172033;
}


.modal-warning {
    margin-top: 11px;

    padding:
        8px 10px;

    border-radius: 9px;

    color: #dc2626;

    background: #fef2f2;

    font-size: 9px;
    font-weight: 600;
}


.modal-actions {
    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 9px;

    margin-top: 20px;
}


.modal-actions button {
    min-height: 42px;

    border-radius: 10px;

    font-size: 10px;
    font-weight: 700;

    transition:
        transform 0.16s ease,
        opacity 0.16s ease;
}


.modal-actions button:hover:not(:disabled) {
    transform:
        translateY(-1px);
}


.modal-actions button:disabled {
    opacity: 0.55;
    cursor: not-allowed;
}


.modal-cancel {
    border: 1px solid #e2e8f0;

    color: #64748b;

    background: #ffffff;
}


.modal-delete {
    border: 1px solid #dc2626;

    color: #ffffff;

    background:
        linear-gradient(
            135deg,
            #ef4444,
            #dc2626
        );

    box-shadow:
        0 6px 15px
        rgba(220, 38, 38, 0.18);
}


/* =========================================================
   RESPONSIVE
   ========================================================= */

@media (max-width: 1050px) {

    .contacts-header {
        align-items: flex-start;
        flex-direction: column;
    }


    .header-actions {
        width: 100%;
    }


    .toolbar-card {
        align-items: stretch;
        flex-direction: column;
    }


    .result-count {
        padding-top: 12px;
        padding-right: 0;

        border-top:
            1px solid #edf2f7;

        border-right: 0;
    }

}


@media (max-width: 700px) {

    .contacts-header {
        padding:
            20px;
    }


    .header-actions {
        display: grid;

        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }


    .action-button:last-child {
        grid-column:
            1 / -1;
    }


    .search-form {
        align-items: stretch;
        flex-direction: column;
    }


    .search-box {
        max-width: none;
    }


    .search-button,
    .clear-button {
        width: 100%;
    }


    .pagination-wrapper {
        align-items: flex-start;
        flex-direction: column;
    }


    .panel-heading {
        align-items: flex-start;
    }

}


@media (max-width: 480px) {

    .header-copy {
        align-items: flex-start;
    }


    .header-icon {
        width: 46px;
        height: 46px;
    }


    .page-title {
        font-size:
            21px !important;
    }


    .header-actions {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .action-button:last-child {
        grid-column: auto;
    }


    .modal-actions {
        grid-template-columns:
            minmax(0, 1fr);
    }

}

</style>
