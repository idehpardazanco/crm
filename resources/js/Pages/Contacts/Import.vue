<script setup>
import {
    Link,
    useForm,
} from '@inertiajs/vue3'

import {
    computed,
    ref,
} from 'vue'


const props = defineProps({
    isAdmin: Boolean,
    users: Array,
    importResult: Object,
})


const fileInput = ref(null)


const form = useForm({
    file: null,
    assigned_user_id: '',
})


const selectedFileName = computed(() => {
    return form.file?.name ?? ''
})


const selectedFileSize = computed(() => {
    if (!form.file?.size) {
        return ''
    }

    const size =
        form.file.size / 1024

    if (size < 1024) {
        return `${Math.round(size)} KB`
    }

    return `${(
        size / 1024
    ).toFixed(1)} MB`
})


const selectFile = (event) => {
    form.file =
        event.target.files[0]
        ?? null
}


const clearFile = () => {
    form.file = null

    if (fileInput.value) {
        fileInput.value.value = ''
    }
}


const submit = () => {
    form.post(
        '/contacts/import',
        {
            forceFormData: true,
        }
    )
}
</script>


<template>
    <div
        class="import-page"
        dir="rtl"
    >

        <!-- HEADER -->
        <section class="import-header">

            <div class="header-copy">

                <div class="header-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M12 16V4M12 4L8 8M12 4L16 8M5 14V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V14"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                </div>


                <div>

                    <div class="header-eyebrow">
                        مدیریت مخاطبین
                    </div>

                    <h1>
                        ورود گروهی مخاطبین
                    </h1>

                    <p>
                        مخاطبین را از فایل Excel یا CSV به CRM اضافه کنید.
                    </p>

                </div>

            </div>


            <div class="header-actions">

                <a
                    href="/contacts/import/template"
                    class="secondary-button"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M12 3V15M12 15L8 11M12 15L16 11M5 21H19"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>

                    دانلود فایل نمونه

                </a>


                <Link
                    href="/contacts"
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

            </div>

        </section>


        <div class="import-layout">

            <!-- MAIN -->
            <main class="import-main">

                <!-- UPLOAD -->
                <section class="premium-card">

                    <div class="card-heading">

                        <div class="heading-icon blue-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M12 16V4M12 4L8 8M12 4L16 8M5 14V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V14"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>

                        </div>


                        <div>
                            <h2>
                                انتخاب فایل
                            </h2>

                            <p>
                                فایل Excel یا CSV خود را انتخاب کنید.
                            </p>
                        </div>

                    </div>


                    <form
                        class="upload-form"
                        @submit.prevent="submit"
                    >

                        <!-- File -->
                        <div class="field">

                            <label>
                                فایل مخاطبین
                                <span class="required">*</span>
                            </label>


                            <label
                                class="upload-zone"
                                :class="{
                                    'upload-zone-selected':
                                        form.file,
                                }"
                            >

                                <input
                                    ref="fileInput"
                                    type="file"
                                    accept=".xlsx,.xls,.csv"
                                    @change="selectFile"
                                >


                                <template v-if="!form.file">

                                    <div class="upload-icon">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M12 16V4M12 4L8 8M12 4L16 8M5 14V19C5 20.1046 5.89543 21 7 21H17C18.1046 21 19 20.1046 19 19V14"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>

                                    </div>


                                    <strong>
                                        برای انتخاب فایل کلیک کنید
                                    </strong>

                                    <span>
                                        XLSX، XLS یا CSV
                                    </span>

                                </template>


                                <template v-else>

                                    <div class="selected-file">

                                        <div class="file-icon">

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                            >
                                                <path
                                                    d="M6 2H14L20 8V22H6V2ZM14 2V8H20"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>

                                        </div>


                                        <div class="file-info">

                                            <strong>
                                                {{ selectedFileName }}
                                            </strong>

                                            <span>
                                                {{ selectedFileSize }}
                                            </span>

                                        </div>


                                        <button
                                            type="button"
                                            class="remove-file"
                                            @click.prevent.stop="clearFile"
                                        >
                                            حذف
                                        </button>

                                    </div>

                                </template>

                            </label>


                            <div
                                v-if="form.errors.file"
                                class="field-error"
                            >
                                {{ form.errors.file }}
                            </div>

                        </div>


                        <!-- Assignment -->
                        <div
                            v-if="isAdmin"
                            class="field assignment-field"
                        >

                            <label>
                                تخصیص مخاطبین به کارمند
                            </label>

                            <select
                                v-model="form.assigned_user_id"
                            >

                                <option value="">
                                    بدون تخصیص
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
                                v-if="
                                    form.errors
                                        .assigned_user_id
                                "
                                class="field-error"
                            >
                                {{
                                    form.errors
                                        .assigned_user_id
                                }}
                            </div>

                        </div>


                        <div
                            v-else
                            class="employee-info"
                        >

                            <div class="employee-info-icon">
                                i
                            </div>


                            <div>

                                <strong>
                                    تخصیص خودکار
                                </strong>

                                <p>
                                    مخاطبین واردشده به‌صورت خودکار به حساب شما تخصیص داده می‌شوند.
                                </p>

                            </div>

                        </div>


                        <!-- Progress -->
                        <div
                            v-if="
                                form.progress
                            "
                            class="upload-progress"
                        >

                            <div class="progress-header">

                                <span>
                                    در حال آپلود فایل
                                </span>

                                <strong>
                                    {{
                                        form.progress
                                            .percentage
                                    }}%
                                </strong>

                            </div>


                            <div class="progress-track">

                                <div
                                    class="progress-bar"
                                    :style="{
                                        width:
                                            `${form.progress.percentage}%`
                                    }"
                                ></div>

                            </div>

                        </div>


                        <div class="form-footer">

                            <button
                                type="submit"
                                class="import-button"
                                :disabled="
                                    form.processing
                                    || !form.file
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
                                        ? 'در حال ورود اطلاعات...'
                                        : 'شروع ورود مخاطبین'
                                }}

                            </button>

                        </div>

                    </form>

                </section>


                <!-- RESULT -->
                <section
                    v-if="importResult"
                    class="premium-card result-card"
                >

                    <div class="card-heading">

                        <div class="heading-icon green-icon">

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


                        <div>
                            <h2>
                                نتیجه ورود اطلاعات
                            </h2>

                            <p>
                                گزارش پردازش فایل آپلودشده
                            </p>
                        </div>

                    </div>


                    <div class="result-content">

                        <div class="result-stats">

                            <div class="result-stat result-success">

                                <div class="result-stat-icon">
                                    ✓
                                </div>

                                <div>

                                    <span>
                                        وارد شده
                                    </span>

                                    <strong>
                                        {{
                                            importResult.imported
                                            ?? 0
                                        }}
                                    </strong>

                                </div>

                            </div>


                            <div class="result-stat result-duplicate">

                                <div class="result-stat-icon">
                                    =
                                </div>

                                <div>

                                    <span>
                                        تکراری
                                    </span>

                                    <strong>
                                        {{
                                            importResult.duplicates
                                            ?? 0
                                        }}
                                    </strong>

                                </div>

                            </div>


                            <div class="result-stat result-failed">

                                <div class="result-stat-icon">
                                    !
                                </div>

                                <div>

                                    <span>
                                        خطادار
                                    </span>

                                    <strong>
                                        {{
                                            importResult.failed
                                            ?? 0
                                        }}
                                    </strong>

                                </div>

                            </div>

                        </div>


                        <!-- Failures -->
                        <div
                            v-if="
                                importResult.failures
                                &&
                                importResult.failures.length
                            "
                            class="failures-section"
                        >

                            <div class="failures-heading">

                                <div>

                                    <h3>
                                        ردیف‌های دارای خطا
                                    </h3>

                                    <p>
                                        موارد زیر وارد سیستم نشده‌اند.
                                    </p>

                                </div>


                                <span class="failure-count">
                                    {{
                                        importResult.failures
                                            .length
                                    }}
                                    ردیف
                                </span>

                            </div>


                            <div class="table-scroll">

                                <table class="failure-table">

                                    <thead>
                                        <tr>
                                            <th>
                                                ردیف Excel
                                            </th>

                                            <th>
                                                شرح خطا
                                            </th>
                                        </tr>
                                    </thead>


                                    <tbody>

                                        <tr
                                            v-for="
                                                failure in
                                                importResult.failures
                                            "
                                            :key="failure.row"
                                        >

                                            <td>

                                                <span class="row-number">
                                                    {{
                                                        failure.row
                                                    }}
                                                </span>

                                            </td>


                                            <td>

                                                <ul class="error-list">

                                                    <li
                                                        v-for="
                                                            error in
                                                            failure.errors
                                                        "
                                                        :key="error"
                                                    >
                                                        {{ error }}
                                                    </li>

                                                </ul>

                                            </td>

                                        </tr>

                                    </tbody>

                                </table>

                            </div>

                        </div>

                    </div>

                </section>

            </main>


            <!-- SIDEBAR -->
            <aside class="import-sidebar">

                <!-- TEMPLATE -->
                <section class="side-card template-card">

                    <div class="side-icon blue-side-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M6 2H14L20 8V22H6V2ZM14 2V8H20"
                                stroke="currentColor"
                                stroke-width="1.7"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                    </div>


                    <h3>
                        فایل نمونه
                    </h3>

                    <p>
                        برای جلوگیری از خطای ساختار، بهتر است از فایل نمونه CRM استفاده کنید.
                    </p>


                    <a
                        href="/contacts/import/template"
                        class="template-button"
                    >
                        دانلود فایل نمونه
                    </a>

                </section>


                <!-- COLUMNS -->
                <section class="side-card">

                    <div class="side-title">
                        ستون‌های قابل شناسایی
                    </div>


                    <div class="column-list">

                        <span>
                            business_name
                        </span>

                        <span class="required-column">
                            name
                        </span>

                        <span class="required-column">
                            mobile
                        </span>

                        <span>
                            phone
                        </span>

                        <span>
                            email
                        </span>

                        <span>
                            city
                        </span>

                        <span>
                            category
                        </span>

                        <span>
                            source
                        </span>

                        <span>
                            status
                        </span>

                        <span>
                            address
                        </span>

                        <span>
                            description
                        </span>

                    </div>


                    <div class="required-hint">
                        ستون‌های قرمز اجباری هستند.
                    </div>

                </section>


                <!-- GUIDE -->
                <section class="side-card guide-card">

                    <div class="side-title">
                        نکات مهم
                    </div>


                    <div class="guide-list">

                        <div>

                            <span class="guide-number">
                                ۱
                            </span>

                            <p>
                                ستون‌های
                                <strong>name</strong>
                                و
                                <strong>mobile</strong>
                                الزامی هستند.
                            </p>

                        </div>


                        <div>

                            <span class="guide-number">
                                ۲
                            </span>

                            <p>
                                وضعیت پیش‌فرض مخاطبین
                                <strong>new</strong>
                                است.
                            </p>

                        </div>


                        <div>

                            <span class="guide-number">
                                ۳
                            </span>

                            <p>
                                شماره‌هایی با فرمت
                                0912،
                                +98
                                و
                                98
                                استانداردسازی می‌شوند.
                            </p>

                        </div>

                    </div>

                </section>

            </aside>

        </div>

    </div>
</template>


<style scoped>
.import-page {
    width: 100%;
    max-width: 1500px;
    margin: 0 auto;
    padding-bottom: 32px;
}


/* HEADER */

.import-header {
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


.header-eyebrow {
    margin-bottom: 3px;

    color: #94a3b8;

    font-size: 9px;
    font-weight: 700;
}


.import-header h1 {
    margin: 0;

    color: #172033;

    font-size: 23px !important;
    font-weight: 800 !important;
}


.import-header p {
    margin: 4px 0 0;

    color: #64748b;

    font-size: 10px;
}


.header-actions {
    display: flex;
    align-items: center;

    gap: 8px;
}


.back-button,
.secondary-button {
    min-height: 39px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 0 13px;

    border: 1px solid #e2e8f0;
    border-radius: 10px;

    color: #64748b !important;

    background: #ffffff;

    font-size: 9px;
    font-weight: 700;

    transition:
        transform .16s ease,
        color .16s ease,
        border-color .16s ease;
}


.back-button:hover,
.secondary-button:hover {
    transform: translateY(-1px);

    color: #2563eb !important;

    border-color: #bfdbfe;
}


.back-button svg,
.secondary-button svg {
    width: 15px;
    height: 15px;
}


/* LAYOUT */

.import-layout {
    display: grid;

    grid-template-columns:
        minmax(0, 1fr) 290px;

    gap: 18px;

    align-items: start;
}


.import-main {
    min-width: 0;
}


.import-sidebar {
    position: sticky;
    top: 98px;
}


/* CARDS */

.premium-card,
.side-card {
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


/* FORM */

.upload-form {
    padding: 18px;
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


.assignment-field {
    margin-top: 17px;
}


.assignment-field select {
    width: 100% !important;
    min-height: 42px !important;

    font-size: 10px !important;
}


.field-error {
    margin-top: 5px;

    color: #dc2626;

    font-size: 8px;
    font-weight: 600;
}


/* UPLOAD ZONE */

.upload-zone {
    width: 100%;
    min-height: 190px;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    padding: 24px;

    border:
        1.5px dashed #bfdbfe;

    border-radius: 15px;

    background:
        linear-gradient(
            180deg,
            #fbfdff,
            #f7faff
        );

    cursor: pointer;

    transition:
        border-color .18s ease,
        background .18s ease,
        transform .18s ease;
}


.upload-zone:hover {
    transform: translateY(-2px);

    border-color: #60a5fa;

    background: #f4f9ff;
}


.upload-zone input {
    display: none;
}


.upload-icon {
    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 12px;

    border-radius: 15px;

    color: #2563eb;

    background: #eff6ff;
}


.upload-icon svg {
    width: 23px;
    height: 23px;
}


.upload-zone > strong {
    color: #334155;

    font-size: 11px;
    font-weight: 800;
}


.upload-zone > span {
    margin-top: 5px;

    color: #94a3b8;

    font-size: 8px;
}


.upload-zone-selected {
    min-height: 110px;

    border-style: solid;

    border-color: #bbf7d0;

    background: #f8fffb;
}


.selected-file {
    width: 100%;

    display: flex;
    align-items: center;

    gap: 11px;
}


.file-icon {
    width: 43px;
    height: 43px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    color: #059669;

    background: #ecfdf5;
}


.file-icon svg {
    width: 20px;
    height: 20px;
}


.file-info {
    flex: 1;
    min-width: 0;

    display: flex;
    flex-direction: column;
}


.file-info strong {
    overflow: hidden;

    color: #334155;

    font-size: 10px;
    font-weight: 700;

    white-space: nowrap;
    text-overflow: ellipsis;
}


.file-info span {
    margin-top: 2px;

    color: #94a3b8;

    font-size: 8px;
}


.remove-file {
    flex-shrink: 0;

    min-height: 30px;

    padding: 0 9px;

    border: 0;
    border-radius: 8px;

    color: #dc2626;

    background: #fef2f2;

    font-size: 8px;
    font-weight: 700;
}


/* EMPLOYEE INFO */

.employee-info {
    display: flex;
    align-items: flex-start;

    gap: 10px;

    margin-top: 17px;
    padding: 12px;

    border: 1px solid #dbeafe;
    border-radius: 11px;

    background: #f8fbff;
}


.employee-info-icon {
    width: 30px;
    height: 30px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 9px;

    color: #2563eb;

    background: #eff6ff;

    font-size: 12px;
    font-weight: 800;
}


.employee-info strong {
    color: #334155;

    font-size: 9px;
}


.employee-info p {
    margin: 3px 0 0;

    color: #64748b;

    font-size: 8px;

    line-height: 1.8;
}


/* PROGRESS */

.upload-progress {
    margin-top: 17px;
}


.progress-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    margin-bottom: 6px;

    color: #64748b;

    font-size: 8px;
}


.progress-header strong {
    color: #2563eb;
}


.progress-track {
    height: 6px;

    overflow: hidden;

    border-radius: 999px;

    background: #eff6ff;
}


.progress-bar {
    height: 100%;

    border-radius: inherit;

    background:
        linear-gradient(
            90deg,
            #60a5fa,
            #2563eb
        );

    transition: width .2s ease;
}


.form-footer {
    display: flex;
    justify-content: flex-end;

    margin-top: 19px;
    padding-top: 16px;

    border-top: 1px solid #edf2f7;
}


.import-button {
    min-width: 155px;
    min-height: 41px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 0 16px;

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
        opacity .16s ease;
}


.import-button:hover:not(:disabled) {
    transform: translateY(-2px);
}


.import-button:disabled {
    opacity: .45;
    cursor: not-allowed;
}


.import-button svg {
    width: 16px;
    height: 16px;
}


/* SIDEBAR */

.side-card {
    padding: 17px;
}


.side-icon {
    width: 38px;
    height: 38px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-bottom: 11px;

    border-radius: 11px;
}


.side-icon svg {
    width: 18px;
    height: 18px;
}


.blue-side-icon {
    color: #2563eb;
    background: #eff6ff;
}


.side-card h3,
.side-title {
    margin: 0;

    color: #1e293b;

    font-size: 10px;
    font-weight: 800;
}


.side-card > p {
    margin: 6px 0 0;

    color: #94a3b8;

    font-size: 8px;

    line-height: 1.9;
}


.template-button {
    width: 100%;
    min-height: 36px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin-top: 13px;

    border: 1px solid #dbeafe;
    border-radius: 9px;

    color: #2563eb !important;

    background: #eff6ff;

    font-size: 8px;
    font-weight: 700;
}


.column-list {
    display: flex;
    flex-wrap: wrap;

    gap: 6px;

    margin-top: 12px;
}


.column-list span {
    padding: 5px 7px;

    border: 1px solid #e2e8f0;
    border-radius: 7px;

    direction: ltr;

    color: #64748b;

    background: #f8fafc;

    font-size: 7px;
}


.column-list .required-column {
    color: #dc2626;

    border-color: #fecaca;

    background: #fef2f2;
}


.required-hint {
    margin-top: 10px;

    color: #94a3b8;

    font-size: 7px;
}


.guide-list {
    display: flex;
    flex-direction: column;

    gap: 12px;

    margin-top: 13px;
}


.guide-list > div {
    display: flex;
    align-items: flex-start;

    gap: 8px;
}


.guide-number {
    width: 22px;
    height: 22px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 7px;

    color: #2563eb;

    background: #eff6ff;

    font-size: 8px;
    font-weight: 800;
}


.guide-list p {
    margin: 1px 0 0;

    color: #64748b;

    font-size: 8px;

    line-height: 1.8;
}


.guide-list strong {
    direction: ltr;
    display: inline-block;

    color: #334155;
}


/* RESULT */

.result-content {
    padding: 18px;
}


.result-stats {
    display: grid;

    grid-template-columns:
        repeat(3, minmax(0, 1fr));

    gap: 12px;
}


.result-stat {
    min-height: 85px;

    display: flex;
    align-items: center;

    gap: 10px;

    padding: 13px;

    border: 1px solid;
    border-radius: 13px;
}


.result-stat-icon {
    width: 35px;
    height: 35px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    font-size: 14px;
    font-weight: 800;
}


.result-stat > div:last-child {
    display: flex;
    flex-direction: column;
}


.result-stat span {
    font-size: 8px;
}


.result-stat strong {
    margin-top: 2px;

    font-size: 19px;
    font-weight: 800;
}


.result-success {
    color: #059669;

    border-color: #bbf7d0;

    background: #f7fef9;
}


.result-success .result-stat-icon {
    background: #dcfce7;
}


.result-duplicate {
    color: #d97706;

    border-color: #fde68a;

    background: #fffdf5;
}


.result-duplicate .result-stat-icon {
    background: #fef3c7;
}


.result-failed {
    color: #dc2626;

    border-color: #fecaca;

    background: #fffafa;
}


.result-failed .result-stat-icon {
    background: #fee2e2;
}


/* FAILURE TABLE */

.failures-section {
    margin-top: 20px;

    overflow: hidden;

    border: 1px solid #fee2e2;
    border-radius: 13px;
}


.failures-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;

    padding: 13px 15px;

    border-bottom: 1px solid #fee2e2;

    background: #fffafa;
}


.failures-heading h3 {
    margin: 0;

    color: #b91c1c;

    font-size: 10px;
    font-weight: 800;
}


.failures-heading p {
    margin: 2px 0 0;

    color: #94a3b8;

    font-size: 7px;
}


.failure-count {
    padding: 5px 8px;

    border-radius: 999px;

    color: #dc2626;

    background: #fef2f2;

    font-size: 7px;
    font-weight: 700;
}


.table-scroll {
    overflow-x: auto;
}


.failure-table {
    width: 100%;
    min-width: 600px;

    border: 0 !important;
    border-radius: 0 !important;

    box-shadow: none !important;
}


.failure-table th {
    padding: 10px 13px !important;

    color: #64748b !important;

    background: #f8fafc;

    border-bottom: 1px solid #edf2f7 !important;

    font-size: 8px !important;

    text-align: right;
}


.failure-table td {
    padding: 11px 13px !important;

    border-bottom: 1px solid #f1f5f9 !important;

    color: #475569 !important;

    font-size: 8px !important;

    vertical-align: top;
}


.row-number {
    min-width: 27px;
    height: 27px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    border-radius: 8px;

    color: #dc2626;

    background: #fef2f2;

    font-weight: 800;
}


.error-list {
    margin: 0;
    padding-right: 14px;

    color: #b91c1c;

    line-height: 1.9;
}


/* RESPONSIVE */

@media (max-width: 900px) {

    .import-layout {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .import-sidebar {
        position: static;
    }

}


@media (max-width: 700px) {

    .import-header {
        align-items: flex-start;
        flex-direction: column;
    }


    .header-actions {
        width: 100%;
    }


    .header-actions > * {
        flex: 1;
    }


    .result-stats {
        grid-template-columns:
            minmax(0, 1fr);
    }

}


@media (max-width: 480px) {

    .header-actions {
        flex-direction: column;
    }


    .header-actions > * {
        width: 100%;
    }


    .form-footer {
        display: block;
    }


    .import-button {
        width: 100%;
    }

}
</style>
