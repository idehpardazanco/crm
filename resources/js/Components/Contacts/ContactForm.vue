<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },

    users: {
        type: Array,
        default: () => [],
    },

    contactStatuses: {
        type: Array,
        default: () => [],
    },

    isAdmin: {
        type: Boolean,
        default: false,
    },

    mode: {
        type: String,
        default: 'create',
    },

    backHref: {
        type: String,
        default: '/contacts',
    },
})

const emit = defineEmits(['submit'])
</script>


<template>
    <div class="contact-form-page" dir="rtl">

        <!-- HEADER -->
        <section class="form-hero">

            <div class="hero-copy">

                <div class="hero-icon">

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

                </div>


                <div>

                    <div class="hero-eyebrow">
                        مدیریت مخاطبین
                    </div>

                    <h1>
                        {{
                            mode === 'edit'
                                ? 'ویرایش مخاطب'
                                : 'ایجاد مخاطب جدید'
                        }}
                    </h1>

                    <p>
                        {{
                            mode === 'edit'
                                ? 'اطلاعات مخاطب را ویرایش و به‌روزرسانی کنید.'
                                : 'اطلاعات مخاطب جدید را با دقت وارد کنید.'
                        }}
                    </p>

                </div>

            </div>


            <Link
                :href="backHref"
                class="back-button"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                >
                    <path
                        d="M15 18L9 12L15 6"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>

                بازگشت
            </Link>

        </section>


        <!-- MAIN -->
        <form
            class="form-layout"
            @submit.prevent="emit('submit')"
        >

            <div class="form-main">

                <!-- IDENTITY -->
                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon blue-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>
                            <h2>
                                مشخصات اصلی
                            </h2>

                            <p>
                                اطلاعات پایه مخاطب و کسب‌وکار
                            </p>
                        </div>

                    </div>


                    <div class="card-content">

                        <div class="form-grid">

                            <div class="field">

                                <label>
                                    نام مخاطب
                                    <span class="required">*</span>
                                </label>

                                <input
                                    v-model="form.name"
                                    type="text"
                                    placeholder="نام و نام خانوادگی"
                                >

                                <div
                                    v-if="form.errors.name"
                                    class="field-error"
                                >
                                    {{ form.errors.name }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    نام کسب‌وکار
                                </label>

                                <input
                                    v-model="form.business_name"
                                    type="text"
                                    placeholder="نام شرکت یا کسب‌وکار"
                                >

                                <div
                                    v-if="form.errors.business_name"
                                    class="field-error"
                                >
                                    {{ form.errors.business_name }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    شهر
                                </label>

                                <input
                                    v-model="form.city"
                                    type="text"
                                    placeholder="مثلاً تهران"
                                >

                                <div
                                    v-if="form.errors.city"
                                    class="field-error"
                                >
                                    {{ form.errors.city }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    دسته‌بندی کسب‌وکار
                                </label>

                                <input
                                    v-model="form.category"
                                    type="text"
                                    placeholder="مثلاً فروشگاه، شرکت، سازمان"
                                >

                                <div
                                    v-if="form.errors.category"
                                    class="field-error"
                                >
                                    {{ form.errors.category }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- CONTACT -->
                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon green-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M22 16.92V20C22 20.5523 21.5523 21 21 21C11.0589 21 3 12.9411 3 3C3 2.44772 3.44772 2 4 2H7.08M5 4L9 8M9 4L5 8"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>
                            <h2>
                                اطلاعات تماس
                            </h2>

                            <p>
                                راه‌های ارتباطی با مخاطب
                            </p>
                        </div>

                    </div>


                    <div class="card-content">

                        <div class="form-grid">

                            <div class="field">

                                <label>
                                    موبایل
                                    <span class="required">*</span>
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
                                        v-model="form.mobile"
                                        type="text"
                                        dir="ltr"
                                        placeholder="09xxxxxxxxx"
                                    >

                                </div>

                                <div
                                    v-if="form.errors.mobile"
                                    class="field-error"
                                >
                                    {{ form.errors.mobile }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    تلفن ثابت
                                </label>

                                <div class="input-with-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M22 16.92V20C22 20.5523 21.5523 21 21 21C11.0589 21 3 12.9411 3 3C3 2.44772 3.44772 2 4 2"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                        />
                                    </svg>

                                    <input
                                        v-model="form.phone"
                                        type="text"
                                        dir="ltr"
                                        placeholder="021xxxxxxxx"
                                    >

                                </div>

                                <div
                                    v-if="form.errors.phone"
                                    class="field-error"
                                >
                                    {{ form.errors.phone }}
                                </div>

                            </div>


                            <div class="field span-2">

                                <label>
                                    ایمیل
                                </label>

                                <div class="input-with-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                    >
                                        <path
                                            d="M4 4H20C21.1046 4 22 4.89543 22 6V18C22 19.1046 21.1046 20 20 20H4C2.89543 20 2 19.1046 2 18V6C2 4.89543 2.89543 4 4 4ZM3 6L12 13L21 6"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </svg>

                                    <input
                                        v-model="form.email"
                                        type="email"
                                        dir="ltr"
                                        placeholder="example@email.com"
                                    >

                                </div>

                                <div
                                    v-if="form.errors.email"
                                    class="field-error"
                                >
                                    {{ form.errors.email }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- CRM -->
                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon violet-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M4 6H20M7 12H17M10 18H14"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>
                            <h2>
                                اطلاعات CRM
                            </h2>

                            <p>
                                وضعیت، منبع و مسئول پیگیری
                            </p>
                        </div>

                    </div>


                    <div class="card-content">

                        <div class="form-grid">

                            <div class="field">

                                <label>
                                    وضعیت مخاطب
                                </label>

                                <select
                                    v-model="form.status"
                                >

                                    <option
                                        v-for="status in contactStatuses"
                                        :key="status.value"
                                        :value="status.value"
                                    >
                                        {{ status.label }}
                                    </option>

                                </select>

                                <div
                                    v-if="form.errors.status"
                                    class="field-error"
                                >
                                    {{ form.errors.status }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    منبع شماره
                                </label>

                                <input
                                    v-model="form.source"
                                    type="text"
                                    placeholder="مثلاً سایت، نمایشگاه، معرفی"
                                >

                                <div
                                    v-if="form.errors.source"
                                    class="field-error"
                                >
                                    {{ form.errors.source }}
                                </div>

                            </div>


                            <div
                                v-if="isAdmin"
                                class="field span-2"
                            >

                                <label>
                                    مسئول مخاطب
                                </label>

                                <select
                                    v-model="form.assigned_user_id"
                                >

                                    <option value="">
                                        بدون مسئول
                                    </option>

                                    <option
                                        v-for="user in users"
                                        :key="user.id"
                                        :value="user.id"
                                    >
                                        {{ user.name }}
                                    </option>

                                </select>

                                <div
                                    v-if="form.errors.assigned_user_id"
                                    class="field-error"
                                >
                                    {{ form.errors.assigned_user_id }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <!-- NOTES -->
                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon amber-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M4 4H20V20H4V4ZM8 8H16M8 12H16M8 16H12"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                />
                            </svg>

                        </div>


                        <div>
                            <h2>
                                آدرس و توضیحات
                            </h2>

                            <p>
                                اطلاعات تکمیلی مخاطب
                            </p>
                        </div>

                    </div>


                    <div class="card-content">

                        <div class="field">

                            <label>
                                آدرس
                            </label>

                            <textarea
                                v-model="form.address"
                                rows="3"
                                placeholder="آدرس کامل..."
                            ></textarea>

                            <div
                                v-if="form.errors.address"
                                class="field-error"
                            >
                                {{ form.errors.address }}
                            </div>

                        </div>


                        <div class="field description-field">

                            <label>
                                توضیحات داخلی
                            </label>

                            <textarea
                                v-model="form.description"
                                rows="5"
                                placeholder="نکات مهم درباره مخاطب، درخواست‌ها، شرایط تماس و..."
                            ></textarea>

                            <div
                                v-if="form.errors.description"
                                class="field-error"
                            >
                                {{ form.errors.description }}
                            </div>

                        </div>

                    </div>

                </section>

            </div>


            <!-- SIDEBAR -->
            <aside class="form-sidebar">

                <section class="side-card">

                    <div class="side-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 8V12M12 16H12.01"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>

                    </div>


                    <h3>
                        نکات ثبت مخاطب
                    </h3>

                    <p>
                        اطلاعات صحیح باعث گزارش‌گیری دقیق‌تر و پیگیری بهتر تیم فروش می‌شود.
                    </p>


                    <div class="tips-list">

                        <div>
                            <span></span>
                            شماره موبایل را دقیق وارد کنید.
                        </div>

                        <div>
                            <span></span>
                            وضعیت مخاطب را متناسب با مرحله فروش انتخاب کنید.
                        </div>

                        <div>
                            <span></span>
                            در صورت نیاز، مخاطب را به کارمند مسئول تخصیص دهید.
                        </div>

                    </div>

                </section>


                <section class="save-card">

                    <div class="save-card-title">
                        {{
                            mode === 'edit'
                                ? 'ذخیره تغییرات'
                                : 'ثبت مخاطب'
                        }}
                    </div>

                    <p>
                        پس از ذخیره، اطلاعات در CRM ثبت و قابل استفاده در تماس‌ها و پیگیری‌ها خواهد بود.
                    </p>


                    <button
                        type="submit"
                        class="save-button"
                        :disabled="form.processing"
                    >

                        <svg
                            v-if="!form.processing"
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
                                : mode === 'edit'
                                    ? 'ذخیره تغییرات'
                                    : 'ثبت مخاطب'
                        }}

                    </button>


                    <Link
                        :href="backHref"
                        class="cancel-button"
                    >
                        انصراف
                    </Link>

                </section>

            </aside>

        </form>

    </div>
</template>


<style scoped>
.contact-form-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding-bottom: 32px;
}


/* HERO */

.form-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 24px;

    margin-bottom: 20px;
    padding: 23px 25px;

    border: 1px solid #e5ebf3;
    border-radius: 20px;

    background:
        linear-gradient(
            135deg,
            #ffffff 0%,
            #f7faff 100%
        );

    box-shadow:
        0 8px 28px
        rgba(15, 23, 42, .045);
}


.hero-copy {
    display: flex;
    align-items: center;
    gap: 14px;
}


.hero-icon {
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


.hero-icon svg {
    width: 24px;
    height: 24px;
}


.hero-eyebrow {
    margin-bottom: 3px;

    color: #94a3b8;

    font-size: 9px;
    font-weight: 700;
}


.form-hero h1 {
    margin: 0;

    color: #172033;

    font-size: 23px !important;
    font-weight: 800 !important;
}


.form-hero p {
    margin: 4px 0 0;

    color: #64748b;

    font-size: 10px;
}


.back-button {
    min-height: 39px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 6px;

    padding: 0 13px;

    border: 1px solid #e2e8f0;
    border-radius: 10px;

    color: #64748b !important;

    background: #ffffff;

    font-size: 9px;
    font-weight: 700;

    transition:
        transform .16s ease,
        border-color .16s ease,
        color .16s ease;
}


.back-button:hover {
    transform: translateX(2px);

    color: #2563eb !important;

    border-color: #bfdbfe;
}


.back-button svg {
    width: 15px;
    height: 15px;
}


/* LAYOUT */

.form-layout {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr) 290px;

    gap: 18px;

    align-items: start;
}


.form-main {
    min-width: 0;
}


.form-card {
    overflow: hidden;

    margin-bottom: 16px;

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


.blue-icon {
    color: #2563eb;
    background: #eff6ff;
}


.green-icon {
    color: #059669;
    background: #ecfdf5;
}


.violet-icon {
    color: #7c3aed;
    background: #f5f3ff;
}


.amber-icon {
    color: #d97706;
    background: #fffbeb;
}


.card-heading h2 {
    margin: 0;

    color: #1e293b;

    font-size: 12px;
    font-weight: 800;
}


.card-heading p {
    margin: 2px 0 0;

    color: #94a3b8;

    font-size: 8px;
}


.card-content {
    padding: 17px;
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


.field label {
    display: block;

    margin-bottom: 6px;

    color: #475569;

    font-size: 9px;
    font-weight: 700;
}


.required {
    color: #dc2626;
}


.field input,
.field select,
.field textarea {
    width: 100% !important;

    min-height: 42px !important;

    font-size: 10px !important;
}


.field textarea {
    line-height: 1.9;
}


.description-field {
    margin-top: 14px;
}


.input-with-icon {
    position: relative;
}


.input-with-icon svg {
    position: absolute;

    top: 50%;
    right: 12px;

    width: 15px;
    height: 15px;

    color: #94a3b8;

    transform: translateY(-50%);

    pointer-events: none;
}


.input-with-icon input {
    padding-right:
        38px !important;
}


.field-error {
    margin-top: 5px;

    color: #dc2626;

    font-size: 8px;
    font-weight: 600;
}


/* SIDEBAR */

.form-sidebar {
    position: sticky;
    top: 98px;
}


.side-card,
.save-card {
    margin-bottom: 14px;
    padding: 18px;

    border: 1px solid #e5ebf3;
    border-radius: 16px;

    background: #ffffff;

    box-shadow:
        0 7px 24px
        rgba(15, 23, 42, .04);
}


.side-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 12px;

    border-radius: 11px;

    color: #2563eb;

    background: #eff6ff;
}


.side-icon svg {
    width: 18px;
    height: 18px;
}


.side-card h3 {
    margin: 0;

    color: #1e293b;

    font-size: 11px;
    font-weight: 800;
}


.side-card > p,
.save-card > p {
    margin: 6px 0 0;

    color: #94a3b8;

    font-size: 8px;

    line-height: 1.9;
}


.tips-list {
    display: flex;
    flex-direction: column;

    gap: 9px;

    margin-top: 15px;
}


.tips-list div {
    display: flex;
    align-items: flex-start;

    gap: 7px;

    color: #64748b;

    font-size: 8px;

    line-height: 1.8;
}


.tips-list span {
    width: 6px;
    height: 6px;

    flex-shrink: 0;

    margin-top: 4px;

    border-radius: 50%;

    background: #60a5fa;
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


.save-card-title {
    color: #1e293b;

    font-size: 11px;
    font-weight: 800;
}


.save-button {
    width: 100%;
    min-height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    margin-top: 15px;

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
    transform: translateY(-2px);

    box-shadow:
        0 10px 22px
        rgba(37, 99, 235, .26);
}


.save-button:disabled {
    opacity: .55;
    cursor: not-allowed;
}


.save-button svg {
    width: 16px;
    height: 16px;
}


.cancel-button {
    width: 100%;
    min-height: 37px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-top: 8px;

    border: 1px solid #e2e8f0;
    border-radius: 9px;

    color: #64748b !important;

    background: #ffffff;

    font-size: 8px;
    font-weight: 700;
}


/* RESPONSIVE */

@media (max-width: 1050px) {

    .form-layout {
        grid-template-columns:
            minmax(0, 1fr) 250px;
    }

}


@media (max-width: 850px) {

    .form-layout {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .form-sidebar {
        position: static;
    }

}


@media (max-width: 650px) {

    .form-hero {
        align-items: flex-start;
        flex-direction: column;
    }


    .form-grid {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .span-2 {
        grid-column: span 1;
    }


    .back-button {
        width: 100%;
    }

}
</style>
