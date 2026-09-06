<script setup>
import { useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({}),
    },
})

const showPassword = ref(false)

const form = useForm({
    sms_from: props.settings?.sms_from ?? '',
    sms_username: props.settings?.sms_username ?? '',
    sms_password: '',
    demo_link: props.settings?.demo_link ?? '',
    product_name: props.settings?.product_name ?? '',
    order_link: props.settings?.order_link ?? '',
})

const hasBasicSettings = computed(() =>
    Boolean(
        form.sms_from
        &&
        form.sms_username
    )
)

const configuredVariables = computed(() => {
    let count = 0

    if (form.demo_link) {
        count++
    }

    if (form.product_name) {
        count++
    }

    if (form.order_link) {
        count++
    }

    return count
})

const submit = () => {
    form.post('/sms/settings', {
        preserveScroll: true,

        onSuccess: () => {
            form.sms_password = ''
        },
    })
}
</script>


<template>
    <div
        class="sms-settings-page"
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
                            d="M12 15.5C13.933 15.5 15.5 13.933 15.5 12C15.5 10.067 13.933 8.5 12 8.5C10.067 8.5 8.5 10.067 8.5 12C8.5 13.933 10.067 15.5 12 15.5ZM19.4 15C19.2 15.4 19.1 15.9 19.3 16.3L20.4 18.4L18.4 20.4L16.3 19.3C15.9 19.1 15.4 19.2 15 19.4C14.6 19.6 14.3 20 14.2 20.5L13.8 22.8H10.2L9.8 20.5C9.7 20 9.4 19.6 9 19.4C8.6 19.2 8.1 19.1 7.7 19.3L5.6 20.4L3.6 18.4L4.7 16.3C4.9 15.9 4.8 15.4 4.6 15C4.4 14.6 4 14.3 3.5 14.2L1.2 13.8V10.2L3.5 9.8C4 9.7 4.4 9.4 4.6 9C4.8 8.6 4.9 8.1 4.7 7.7L3.6 5.6L5.6 3.6L7.7 4.7C8.1 4.9 8.6 4.8 9 4.6C9.4 4.4 9.7 4 9.8 3.5L10.2 1.2H13.8L14.2 3.5C14.3 4 14.6 4.4 15 4.6C15.4 4.8 15.9 4.9 16.3 4.7L18.4 3.6L20.4 5.6L19.3 7.7C19.1 8.1 19.2 8.6 19.4 9C19.6 9.4 20 9.7 20.5 9.8L22.8 10.2V13.8L20.5 14.2C20 14.3 19.6 14.6 19.4 15Z"
                            stroke="currentColor"
                            stroke-width="1.5"
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
                        تنظیمات پیامک
                    </h1>

                    <p>
                        تنظیمات اتصال پنل پیامکی و متغیرهای قابل استفاده در قالب‌ها
                    </p>

                </div>

            </div>


            <div
                class="connection-badge"
                :class="{
                    connected:
                        hasBasicSettings,
                }"
            >

                <span></span>

                {{
                    hasBasicSettings
                        ? 'تنظیمات پایه ثبت شده'
                        : 'نیاز به تکمیل تنظیمات'
                }}

            </div>

        </section>


        <!-- SUMMARY -->
        <section class="summary-grid">

            <div class="summary-card">

                <div class="summary-icon blue">

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

                </div>


                <div>

                    <span>
                        شماره فرستنده
                    </span>

                    <strong
                        v-if="form.sms_from"
                        dir="ltr"
                    >
                        {{ form.sms_from }}
                    </strong>

                    <strong
                        v-else
                        class="empty-value"
                    >
                        ثبت نشده
                    </strong>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon violet">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                            stroke="currentColor"
                            stroke-width="1.7"
                        />
                    </svg>

                </div>


                <div>

                    <span>
                        حساب پنل پیامک
                    </span>

                    <strong
                        v-if="form.sms_username"
                        dir="ltr"
                    >
                        {{ form.sms_username }}
                    </strong>

                    <strong
                        v-else
                        class="empty-value"
                    >
                        ثبت نشده
                    </strong>

                </div>

            </div>


            <div class="summary-card">

                <div class="summary-icon green">
                    {{ configuredVariables }}
                </div>


                <div>

                    <span>
                        متغیرهای تکمیل‌شده
                    </span>

                    <strong>
                        {{ configuredVariables }}
                        از ۳
                    </strong>

                </div>

            </div>

        </section>


        <form
            class="settings-layout"
            @submit.prevent="submit"
        >

            <main>

                <!-- CONNECTION -->
                <section class="settings-card">

                    <div class="card-heading">

                        <div class="heading-icon blue">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M7 8C7 5.23858 9.23858 3 12 3C14.7614 3 17 5.23858 17 8V10M6 10H18C19.1046 10 20 10.8954 20 12V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V12C4 10.8954 4.89543 10 6 10Z"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>

                            <h2>
                                اطلاعات اتصال پنل پیامک
                            </h2>

                            <p>
                                اطلاعات حساب و شماره ارسال‌کننده
                            </p>

                        </div>

                    </div>


                    <div class="card-content">

                        <div class="form-grid">

                            <div class="field">

                                <label>
                                    شماره فرستنده
                                </label>


                                <div class="input-with-icon">

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


                                    <input
                                        v-model="form.sms_from"
                                        type="text"
                                        dir="ltr"
                                        placeholder="شماره خط ارسال پیامک"
                                    >

                                </div>


                                <div
                                    v-if="
                                        form.errors
                                            .sms_from
                                    "
                                    class="field-error"
                                >
                                    {{
                                        form.errors
                                            .sms_from
                                    }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    نام کاربری پنل
                                </label>


                                <div class="input-with-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                        />
                                    </svg>


                                    <input
                                        v-model="form.sms_username"
                                        type="text"
                                        dir="ltr"
                                        autocomplete="username"
                                        placeholder="نام کاربری پنل پیامکی"
                                    >

                                </div>


                                <div
                                    v-if="
                                        form.errors
                                            .sms_username
                                    "
                                    class="field-error"
                                >
                                    {{
                                        form.errors
                                            .sms_username
                                    }}
                                </div>

                            </div>


                            <div class="field span-2">

                                <label>
                                    رمز عبور پنل پیامک
                                </label>


                                <div class="password-input">

                                    <input
                                        v-model="form.sms_password"
                                        :type="
                                            showPassword
                                                ? 'text'
                                                : 'password'
                                        "
                                        dir="ltr"
                                        autocomplete="new-password"
                                        placeholder="برای عدم تغییر رمز، این فیلد را خالی بگذارید"
                                    >


                                    <button
                                        type="button"
                                        class="password-toggle"
                                        @click="
                                            showPassword =
                                                !showPassword
                                        "
                                    >

                                        <svg
                                            v-if="!showPassword"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M2 12C2 12 5.5 5 12 5C18.5 5 22 12 22 12C22 12 18.5 19 12 19C5.5 19 2 12 2 12ZM12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                            />
                                        </svg>


                                        <svg
                                            v-else
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M3 3L21 21M10.6 10.6C10.2 11 10 11.5 10 12C10 13.1046 10.8954 14 12 14C12.5 14 13 13.8 13.4 13.4M9.9 5.2C10.6 5.1 11.3 5 12 5C18.5 5 22 12 22 12C21.4 13.1 20.6 14.2 19.6 15.2M6.6 6.6C3.6 8.4 2 12 2 12C2 12 5.5 19 12 19C13.5 19 14.9 18.6 16.1 18"
                                                stroke="currentColor"
                                                stroke-width="1.7"
                                                stroke-linecap="round"
                                            />
                                        </svg>

                                    </button>

                                </div>


                                <div class="field-help">
                                    اگر قصد تغییر رمز را ندارید، خالی بگذارید.
                                </div>


                                <div
                                    v-if="
                                        form.errors
                                            .sms_password
                                    "
                                    class="field-error"
                                >
                                    {{
                                        form.errors
                                            .sms_password
                                    }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- VARIABLES -->
                <section class="settings-card">

                    <div class="card-heading">

                        <div class="heading-icon violet">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M8 4H5C3.89543 4 3 4.89543 3 6V9M16 4H19C20.1046 4 21 4.89543 21 6V9M8 20H5C3.89543 20 3 19.1046 3 18V15M16 20H19C20.1046 20 21 19.1046 21 18V15"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>

                            <h2>
                                متغیرهای قالب پیامک
                            </h2>

                            <p>
                                مقادیر عمومی که در متن قالب‌ها جایگزین می‌شوند
                            </p>

                        </div>

                    </div>


                    <div class="card-content">

                        <div class="variable-block">

                            <div class="variable-header">

                                <div>

                                    <label>
                                        لینک دمو
                                    </label>

                                    <code
                                        v-pre
                                    >{{demo_link}}</code>

                                </div>


                                <span
                                    class="variable-state"
                                    :class="{
                                        ready:
                                            form.demo_link,
                                    }"
                                >
                                    {{
                                        form.demo_link
                                            ? 'تنظیم شده'
                                            : 'خالی'
                                    }}
                                </span>

                            </div>


                            <input
                                v-model="form.demo_link"
                                type="text"
                                dir="ltr"
                                placeholder="https://..."
                            >


                            <div
                                v-if="
                                    form.errors
                                        .demo_link
                                "
                                class="field-error"
                            >
                                {{
                                    form.errors
                                        .demo_link
                                }}
                            </div>

                        </div>


                        <div class="variable-block">

                            <div class="variable-header">

                                <div>

                                    <label>
                                        نام محصول
                                    </label>

                                    <code
                                        v-pre
                                    >{{product_name}}</code>

                                </div>


                                <span
                                    class="variable-state"
                                    :class="{
                                        ready:
                                            form.product_name,
                                    }"
                                >
                                    {{
                                        form.product_name
                                            ? 'تنظیم شده'
                                            : 'خالی'
                                    }}
                                </span>

                            </div>


                            <input
                                v-model="form.product_name"
                                type="text"
                                placeholder="نام محصول یا سرویس"
                            >


                            <div
                                v-if="
                                    form.errors
                                        .product_name
                                "
                                class="field-error"
                            >
                                {{
                                    form.errors
                                        .product_name
                                }}
                            </div>

                        </div>


                        <div class="variable-block">

                            <div class="variable-header">

                                <div>

                                    <label>
                                        لینک سفارش
                                    </label>

                                    <code
                                        v-pre
                                    >{{order_link}}</code>

                                </div>


                                <span
                                    class="variable-state"
                                    :class="{
                                        ready:
                                            form.order_link,
                                    }"
                                >
                                    {{
                                        form.order_link
                                            ? 'تنظیم شده'
                                            : 'خالی'
                                    }}
                                </span>

                            </div>


                            <input
                                v-model="form.order_link"
                                type="text"
                                dir="ltr"
                                placeholder="https://..."
                            >


                            <div
                                v-if="
                                    form.errors
                                        .order_link
                                "
                                class="field-error"
                            >
                                {{
                                    form.errors
                                        .order_link
                                }}
                            </div>

                        </div>

                    </div>

                </section>

            </main>


            <!-- SIDEBAR -->
            <aside>

                <section class="status-card">

                    <div
                        class="status-visual"
                        :class="{
                            ready:
                                hasBasicSettings,
                        }"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                v-if="
                                    hasBasicSettings
                                "
                                d="M5 12L10 17L19 8"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />

                            <path
                                v-else
                                d="M12 8V13M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>

                    </div>


                    <h3>
                        {{
                            hasBasicSettings
                                ? 'تنظیمات پایه تکمیل است'
                                : 'تنظیمات اتصال ناقص است'
                        }}
                    </h3>


                    <p>
                        {{
                            hasBasicSettings
                                ? 'شماره فرستنده و نام کاربری پنل در سیستم ثبت شده‌اند.'
                                : 'برای استفاده از سرویس پیامک، اطلاعات اتصال را کامل کنید.'
                        }}
                    </p>


                    <div class="check-list">

                        <div>
                            <span
                                :class="{
                                    checked:
                                        form.sms_from,
                                }"
                            >
                                ✓
                            </span>

                            شماره فرستنده
                        </div>


                        <div>
                            <span
                                :class="{
                                    checked:
                                        form.sms_username,
                                }"
                            >
                                ✓
                            </span>

                            نام کاربری پنل
                        </div>


                        <div>
                            <span
                                :class="{
                                    checked:
                                        configuredVariables === 3,
                                }"
                            >
                                ✓
                            </span>

                            متغیرهای عمومی
                        </div>

                    </div>

                </section>


                <section class="security-card">

                    <div class="security-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 22C12 22 20 18 20 11V5L12 2L4 5V11C4 18 12 22 12 22ZM9 12L11 14L15 10"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>


                    <div>

                        <strong>
                            اطلاعات حساس
                        </strong>

                        <p>
                            رمز عبور فعلی در فرم نمایش داده نمی‌شود. فقط در صورت نیاز رمز جدید وارد کنید.
                        </p>

                    </div>

                </section>


                <section class="save-card">

                    <h3>
                        ذخیره تنظیمات
                    </h3>

                    <p>
                        تغییرات این صفحه روی تنظیمات پیامک و متغیرهای قالب‌ها اعمال می‌شود.
                    </p>


                    <button
                        type="submit"
                        class="save-button"
                        :disabled="
                            form.processing
                        "
                    >

                        <svg
                            v-if="
                                !form.processing
                            "
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


                        {{
                            form.processing
                                ? 'در حال ذخیره...'
                                : 'ذخیره تنظیمات'
                        }}

                    </button>

                </section>

            </aside>

        </form>

    </div>
</template>


<style scoped>
.sms-settings-page {
    width: 100%;
    max-width: 1450px;
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


.connection-badge {
    display: inline-flex;
    align-items: center;

    gap: 7px;

    padding: 8px 11px;

    border: 1px solid #fde68a;
    border-radius: 999px;

    color: #b45309;

    background: #fffbeb;

    font-size: 8px;
    font-weight: 700;
}


.connection-badge span {
    width: 6px;
    height: 6px;

    border-radius: 50%;

    background: currentColor;
}


.connection-badge.connected {
    color: #15803d;

    border-color: #bbf7d0;

    background: #f0fdf4;
}


/* SUMMARY */

.summary-grid {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 12px;

    margin-bottom: 18px;
}


.summary-card {
    min-height: 80px;

    display: flex;
    align-items: center;

    gap: 11px;

    padding: 13px 15px;

    border: 1px solid #e5ebf3;
    border-radius: 14px;

    background: #ffffff;

    box-shadow:
        0 5px 18px
        rgba(15, 23, 42, .035);
}


.summary-icon {
    width: 39px;
    height: 39px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    font-size: 13px;
    font-weight: 800;
}


.summary-icon svg {
    width: 18px;
    height: 18px;
}


.summary-icon.blue {
    color: #2563eb;
    background: #eff6ff;
}


.summary-icon.violet {
    color: #7c3aed;
    background: #f5f3ff;
}


.summary-icon.green {
    color: #059669;
    background: #ecfdf5;
}


.summary-card > div:last-child {
    min-width: 0;

    display: flex;
    flex-direction: column;
}


.summary-card span {
    color: #94a3b8;

    font-size: 8px;
}


.summary-card strong {
    margin-top: 3px;

    overflow: hidden;

    color: #172033;

    font-size: 11px;
    font-weight: 800;

    white-space: nowrap;
    text-overflow: ellipsis;
}


.summary-card .empty-value {
    color: #94a3b8;

    font-size: 9px;
}


/* LAYOUT */

.settings-layout {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr) 290px;

    gap: 18px;

    align-items: start;
}


.settings-card,
.status-card,
.security-card,
.save-card {
    margin-bottom: 16px;

    overflow: hidden;

    border: 1px solid #e5ebf3;
    border-radius: 17px;

    background: #ffffff;

    box-shadow:
        0 7px 24px
        rgba(15, 23, 42, .04);
}


.card-heading {
    display: flex;
    align-items: center;

    gap: 11px;

    padding: 14px 17px;

    border-bottom:
        1px solid #edf2f7;

    background:
        linear-gradient(
            180deg,
            #ffffff,
            #fbfcfe
        );
}


.heading-icon {
    width: 35px;
    height: 35px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;
}


.heading-icon svg {
    width: 17px;
    height: 17px;
}


.heading-icon.blue {
    color: #2563eb;

    background: #eff6ff;
}


.heading-icon.violet {
    color: #7c3aed;

    background: #f5f3ff;
}


.card-heading h2 {
    margin: 0;

    color: #1e293b;

    font-size: 11px;
    font-weight: 800;
}


.card-heading p {
    margin: 2px 0 0;

    color: #94a3b8;

    font-size: 8px;
}


.card-content {
    padding: 18px;
}


/* FIELDS */

.form-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 14px;
}


.span-2 {
    grid-column: span 2;
}


.field label,
.variable-block label {
    display: block;

    margin-bottom: 6px;

    color: #475569;

    font-size: 9px;
    font-weight: 700;
}


.field input,
.variable-block input {
    width: 100% !important;

    min-height: 42px !important;

    font-size: 10px !important;
}


.input-with-icon,
.password-input {
    position: relative;
}


.input-with-icon > svg {
    position: absolute;

    z-index: 2;

    top: 50%;
    right: 12px;

    width: 15px;
    height: 15px;

    color: #94a3b8;

    transform:
        translateY(-50%);

    pointer-events: none;
}


.input-with-icon input {
    padding-right:
        38px !important;
}


.password-input input {
    padding-left:
        45px !important;
}


.password-toggle {
    position: absolute;

    top: 50%;
    left: 7px;

    width: 32px;
    height: 32px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    border: 0;
    border-radius: 8px;

    color: #94a3b8;

    background: transparent;

    transform:
        translateY(-50%);
}


.password-toggle:hover {
    color: #2563eb;

    background: #eff6ff;
}


.password-toggle svg {
    width: 16px;
    height: 16px;
}


.field-help {
    margin-top: 5px;

    color: #94a3b8;

    font-size: 7px;
}


.field-error {
    margin-top: 5px;

    color: #dc2626;

    font-size: 8px;
    font-weight: 600;
}


/* VARIABLES */

.variable-block {
    padding: 14px;

    border: 1px solid #edf2f7;
    border-radius: 12px;

    background: #fbfcfe;
}


.variable-block + .variable-block {
    margin-top: 11px;
}


.variable-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    margin-bottom: 9px;
}


.variable-header > div {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 7px;
}


.variable-header label {
    margin: 0;
}


.variable-header code {
    padding: 4px 6px;

    border-radius: 6px;

    color: #4f46e5;

    background: #eef2ff;

    direction: ltr;

    font-size: 8px;
}


.variable-state {
    flex-shrink: 0;

    padding: 4px 7px;

    border-radius: 999px;

    color: #94a3b8;

    background: #f1f5f9;

    font-size: 7px;
    font-weight: 700;
}


.variable-state.ready {
    color: #15803d;

    background: #f0fdf4;
}


/* SIDEBAR */

aside {
    position: sticky;

    top: 98px;
}


.status-card,
.security-card,
.save-card {
    padding: 17px;
}


.status-card {
    text-align: center;
}


.status-visual {
    width: 49px;
    height: 49px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 auto 11px;

    border-radius: 14px;

    color: #d97706;

    background: #fffbeb;
}


.status-visual.ready {
    color: #059669;

    background: #ecfdf5;
}


.status-visual svg {
    width: 23px;
    height: 23px;
}


.status-card h3,
.save-card h3 {
    margin: 0;

    color: #1e293b;

    font-size: 10px;
    font-weight: 800;
}


.status-card > p,
.save-card > p {
    margin: 6px 0 0;

    color: #94a3b8;

    font-size: 8px;

    line-height: 1.9;
}


.check-list {
    display: flex;
    flex-direction: column;

    gap: 7px;

    margin-top: 15px;

    text-align: right;
}


.check-list div {
    display: flex;
    align-items: center;

    gap: 7px;

    color: #64748b;

    font-size: 8px;
}


.check-list span {
    width: 20px;
    height: 20px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 6px;

    color: #cbd5e1;

    background: #f1f5f9;

    font-size: 8px;
    font-weight: 800;
}


.check-list span.checked {
    color: #059669;

    background: #ecfdf5;
}


.security-card {
    display: flex;
    align-items: flex-start;

    gap: 10px;

    border-color: #dbeafe;

    background: #f8fbff;
}


.security-icon {
    width: 34px;
    height: 34px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: #2563eb;

    background: #eff6ff;
}


.security-icon svg {
    width: 17px;
    height: 17px;
}


.security-card strong {
    color: #334155;

    font-size: 9px;
}


.security-card p {
    margin: 4px 0 0;

    color: #64748b;

    font-size: 7px;

    line-height: 1.9;
}


.save-card {
    border-color: #dbeafe;

    background:
        linear-gradient(
            180deg,
            #ffffff,
            #f8fbff
        );
}


.save-button {
    width: 100%;
    min-height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    margin-top: 14px;

    border: 0;
    border-radius: 10px;

    color: #ffffff;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #1d4ed8
        );

    box-shadow:
        0 7px 17px
        rgba(37, 99, 235, .20);

    font-size: 9px;
    font-weight: 700;

    transition:
        transform .16s ease,
        box-shadow .16s ease,
        opacity .16s ease;
}


.save-button:hover:not(:disabled) {
    transform:
        translateY(-2px);

    box-shadow:
        0 10px 22px
        rgba(37, 99, 235, .27);
}


.save-button:disabled {
    opacity: .5;

    cursor: not-allowed;
}


.save-button svg {
    width: 16px;
    height: 16px;
}


/* RESPONSIVE */

@media (max-width: 900px) {

    .settings-layout {
        grid-template-columns:
            minmax(0, 1fr);
    }


    aside {
        position: static;
    }

}


@media (max-width: 700px) {

    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }


    .summary-grid {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .connection-badge {
        width: 100%;

        justify-content: center;
    }

}


@media (max-width: 600px) {

    .form-grid {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .span-2 {
        grid-column: span 1;
    }


    .variable-header {
        align-items: flex-start;
        flex-direction: column;
    }

}
</style>
