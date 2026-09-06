<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },

    mode: {
        type: String,
        default: 'create',
    },
})

const emit = defineEmits(['submit'])

const characterCount = computed(
    () => props.form.body?.length ?? 0
)

const estimatedParts = computed(() => {
    const count = characterCount.value

    if (!count) {
        return 0
    }

    return Math.ceil(count / 70)
})
</script>


<template>
    <div class="template-form-page" dir="rtl">

        <section class="page-header">

            <div class="header-copy">

                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M4 4H20C21.1046 4 22 4.89543 22 6V16C22 17.1046 21.1046 18 20 18H7L2 22V6C2 4.89543 2.89543 4 4 4ZM7 8H17M7 12H14"
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
                        {{
                            mode === 'edit'
                                ? 'ویرایش قالب پیامک'
                                : 'ایجاد قالب پیامک'
                        }}
                    </h1>

                    <p>
                        {{
                            mode === 'edit'
                                ? 'محتوا و تنظیمات قالب پیامک را ویرایش کنید.'
                                : 'یک متن آماده برای ارسال سریع به مشتریان ایجاد کنید.'
                        }}
                    </p>
                </div>

            </div>


            <Link
                href="/sms/templates"
                class="back-button"
            >
                <svg viewBox="0 0 24 24" fill="none">
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


        <form
            class="page-layout"
            @submit.prevent="emit('submit')"
        >

            <main>

                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon blue">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M4 5H20M4 12H14M4 19H11"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2>
                                مشخصات قالب
                            </h2>

                            <p>
                                عنوان و نوع قالب را مشخص کنید.
                            </p>
                        </div>

                    </div>


                    <div class="card-content">

                        <div class="form-grid">

                            <div class="field">

                                <label>
                                    عنوان قالب
                                    <span>*</span>
                                </label>

                                <input
                                    v-model="form.title"
                                    type="text"
                                    placeholder="مثلاً ارسال دمو"
                                >

                                <div
                                    v-if="form.errors.title"
                                    class="field-error"
                                >
                                    {{ form.errors.title }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    نوع قالب
                                </label>

                                <input
                                    v-model="form.type"
                                    type="text"
                                    placeholder="مثلاً demo"
                                    dir="ltr"
                                >

                                <div class="field-help">
                                    یک شناسه کوتاه برای دسته‌بندی قالب
                                </div>

                                <div
                                    v-if="form.errors.type"
                                    class="field-error"
                                >
                                    {{ form.errors.type }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon violet">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M4 4H20V17H7L3 21V5C3 4.44772 3.44772 4 4 4ZM7 8H17M7 12H14"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2>
                                متن پیامک
                            </h2>

                            <p>
                                محتوای نهایی قالب را وارد کنید.
                            </p>
                        </div>

                    </div>


                    <div class="card-content">

                        <div class="field">

                            <div class="label-row">

                                <label>
                                    متن قالب
                                    <span>*</span>
                                </label>

                                <div class="counter">
                                    {{ characterCount }}
                                    کاراکتر
                                </div>

                            </div>


                            <textarea
                                v-model="form.body"
                                rows="9"
                                placeholder="متن پیامک را وارد کنید..."
                            ></textarea>


                            <div
                                v-if="form.errors.body"
                                class="field-error"
                            >
                                {{ form.errors.body }}
                            </div>

                        </div>


                        <div class="message-meta">

                            <span>
                                تعداد تقریبی بخش‌ها
                            </span>

                            <strong>
                                {{ estimatedParts }}
                            </strong>

                        </div>

                    </div>

                </section>


                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon green">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M12 8V16M8 12H16M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2>
                                وضعیت قالب
                            </h2>

                            <p>
                                مشخص کنید قالب قابل استفاده باشد یا خیر.
                            </p>
                        </div>

                    </div>


                    <div class="card-content">

                        <div class="status-options">

                            <label
                                class="status-option"
                                :class="{
                                    selected:
                                        form.status === 'active',
                                }"
                            >

                                <input
                                    v-model="form.status"
                                    type="radio"
                                    value="active"
                                >

                                <div class="status-icon active-icon">
                                    ✓
                                </div>

                                <div>
                                    <strong>
                                        فعال
                                    </strong>

                                    <span>
                                        قالب در زمان ارسال پیامک قابل انتخاب است.
                                    </span>
                                </div>

                            </label>


                            <label
                                class="status-option"
                                :class="{
                                    selected:
                                        form.status === 'inactive',
                                }"
                            >

                                <input
                                    v-model="form.status"
                                    type="radio"
                                    value="inactive"
                                >

                                <div class="status-icon inactive-icon">
                                    —
                                </div>

                                <div>
                                    <strong>
                                        غیرفعال
                                    </strong>

                                    <span>
                                        قالب ذخیره می‌شود اما برای ارسال استفاده نمی‌شود.
                                    </span>
                                </div>

                            </label>

                        </div>


                        <div
                            v-if="form.errors.status"
                            class="field-error"
                        >
                            {{ form.errors.status }}
                        </div>

                    </div>

                </section>

            </main>


            <aside>

                <section class="preview-card">

                    <div class="preview-heading">

                        <div>
                            <span>
                                پیش‌نمایش
                            </span>

                            <strong>
                                SMS Preview
                            </strong>
                        </div>


                        <div class="preview-dot"></div>

                    </div>


                    <div class="phone-preview">

                        <div class="phone-top">
                            <span>9:41</span>

                            <div></div>
                        </div>


                        <div class="conversation-title">
                            پیامک CRM
                        </div>


                        <div
                            v-if="form.body"
                            class="message-bubble"
                        >
                            {{ form.body }}
                        </div>


                        <div
                            v-else
                            class="message-placeholder"
                        >
                            متن پیامک شما اینجا نمایش داده می‌شود.
                        </div>

                    </div>


                    <div class="preview-info">

                        <div>
                            <span>
                                کاراکتر
                            </span>

                            <strong>
                                {{ characterCount }}
                            </strong>
                        </div>

                        <div>
                            <span>
                                بخش تقریبی
                            </span>

                            <strong>
                                {{ estimatedParts }}
                            </strong>
                        </div>

                    </div>

                </section>


                <section class="save-card">

                    <h3>
                        {{
                            mode === 'edit'
                                ? 'ذخیره تغییرات'
                                : 'ثبت قالب'
                        }}
                    </h3>

                    <p>
                        پس از ذخیره، قالب در مرکز پیامک CRM قابل استفاده خواهد بود.
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
                                    : 'ایجاد قالب'
                        }}

                    </button>


                    <Link
                        href="/sms/templates"
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
.template-form-page {
    width: 100%;
    max-width: 1450px;
    margin: 0 auto;
    padding-bottom: 32px;
}

.page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 24px;
    margin-bottom: 19px;
    padding: 23px 25px;
    border: 1px solid #e5ebf3;
    border-radius: 20px;
    background: linear-gradient(135deg,#fff,#f7faff);
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
    background: #fff;
    font-size: 9px;
    font-weight: 700;
    transition: .16s ease;
}

.back-button:hover {
    color: #2563eb !important;
    border-color: #bfdbfe;
    transform: translateX(2px);
}

.back-button svg {
    width: 15px;
    height: 15px;
}

.page-layout {
    display: grid;
    grid-template-columns: minmax(0,1fr) 300px;
    gap: 18px;
    align-items: start;
}

.form-card,
.preview-card,
.save-card {
    margin-bottom: 16px;
    overflow: hidden;
    border: 1px solid #e5ebf3;
    border-radius: 17px;
    background: #fff;
    box-shadow: 0 7px 24px rgba(15,23,42,.04);
}

.card-heading {
    display: flex;
    align-items: center;
    gap: 11px;
    padding: 14px 17px;
    border-bottom: 1px solid #edf2f7;
    background: linear-gradient(180deg,#fff,#fbfcfe);
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

.heading-icon.green {
    color: #059669;
    background: #ecfdf5;
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

.form-grid {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    gap: 14px;
}

.field label {
    display: block;
    margin-bottom: 6px;
    color: #475569;
    font-size: 9px;
    font-weight: 700;
}

.field label span {
    color: #dc2626;
}

.field input,
.field textarea {
    width: 100% !important;
    min-height: 42px !important;
    font-size: 10px !important;
}

.field textarea {
    line-height: 2;
    resize: vertical;
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

.label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
}

.counter {
    padding: 4px 7px;
    border-radius: 7px;
    color: #64748b;
    background: #f1f5f9;
    font-size: 7px;
}

.message-meta {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 7px;
    margin-top: 9px;
    color: #94a3b8;
    font-size: 8px;
}

.message-meta strong {
    min-width: 24px;
    height: 24px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 7px;
    color: #2563eb;
    background: #eff6ff;
}

.status-options {
    display: grid;
    grid-template-columns: repeat(2,minmax(0,1fr));
    gap: 11px;
}

.status-option {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 79px;
    padding: 12px;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    cursor: pointer;
    transition: .16s ease;
}

.status-option:hover,
.status-option.selected {
    border-color: #bfdbfe;
    background: #f8fbff;
}

.status-option input {
    position: absolute;
    opacity: 0;
}

.status-icon {
    width: 34px;
    height: 34px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 800;
}

.active-icon {
    color: #059669;
    background: #ecfdf5;
}

.inactive-icon {
    color: #64748b;
    background: #f1f5f9;
}

.status-option strong {
    display: block;
    color: #334155;
    font-size: 9px;
}

.status-option span {
    display: block;
    margin-top: 3px;
    color: #94a3b8;
    font-size: 7px;
    line-height: 1.7;
}

aside {
    position: sticky;
    top: 98px;
}

.preview-card {
    padding: 15px;
    background: linear-gradient(180deg,#fff,#f8fafc);
}

.preview-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 13px;
}

.preview-heading > div:first-child {
    display: flex;
    flex-direction: column;
}

.preview-heading span {
    color: #94a3b8;
    font-size: 7px;
}

.preview-heading strong {
    color: #334155;
    font-size: 9px;
}

.preview-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    box-shadow: 0 0 0 4px #ecfdf5;
}

.phone-preview {
    min-height: 285px;
    padding: 11px;
    border: 5px solid #172033;
    border-radius: 25px;
    background: linear-gradient(180deg,#f8fafc,#eff6ff);
    box-shadow: 0 12px 30px rgba(15,23,42,.13);
}

.phone-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: #64748b;
    font-size: 7px;
}

.phone-top div {
    width: 42px;
    height: 5px;
    border-radius: 999px;
    background: #cbd5e1;
}

.conversation-title {
    margin: 17px 0 14px;
    padding-bottom: 9px;
    border-bottom: 1px solid #e2e8f0;
    color: #334155;
    font-size: 8px;
    font-weight: 800;
    text-align: center;
}

.message-bubble {
    max-width: 90%;
    margin-right: auto;
    padding: 10px;
    border-radius: 13px 13px 4px 13px;
    color: #334155;
    background: #fff;
    box-shadow: 0 4px 12px rgba(15,23,42,.06);
    font-size: 8px;
    line-height: 1.9;
    white-space: pre-wrap;
    word-break: break-word;
}

.message-placeholder {
    padding: 12px;
    border: 1px dashed #cbd5e1;
    border-radius: 11px;
    color: #94a3b8;
    background: rgba(255,255,255,.5);
    font-size: 8px;
    line-height: 1.8;
    text-align: center;
}

.preview-info {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 7px;
    margin-top: 12px;
}

.preview-info div {
    padding: 8px;
    border-radius: 9px;
    background: #f8fafc;
    text-align: center;
}

.preview-info span {
    display: block;
    color: #94a3b8;
    font-size: 7px;
}

.preview-info strong {
    display: block;
    margin-top: 2px;
    color: #334155;
    font-size: 11px;
}

.save-card {
    padding: 17px;
    border-color: #dbeafe;
    background: linear-gradient(180deg,#fff,#f8fbff);
}

.save-card h3 {
    margin: 0;
    color: #1e293b;
    font-size: 10px;
    font-weight: 800;
}

.save-card p {
    margin: 6px 0 0;
    color: #94a3b8;
    font-size: 8px;
    line-height: 1.9;
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
    color: #fff;
    background: linear-gradient(135deg,#2563eb,#1d4ed8);
    box-shadow: 0 7px 17px rgba(37,99,235,.20);
    font-size: 9px;
    font-weight: 700;
    transition: .16s ease;
}

.save-button:hover:not(:disabled) {
    transform: translateY(-2px);
}

.save-button:disabled {
    opacity: .5;
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
    background: #fff;
    font-size: 8px;
    font-weight: 700;
}

@media (max-width: 900px) {
    .page-layout {
        grid-template-columns: 1fr;
    }

    aside {
        position: static;
    }
}

@media (max-width: 650px) {
    .page-header {
        align-items: flex-start;
        flex-direction: column;
    }

    .back-button {
        width: 100%;
    }

    .form-grid,
    .status-options {
        grid-template-columns: 1fr;
    }
}
</style>
