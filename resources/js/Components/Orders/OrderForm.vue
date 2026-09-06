<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    form: {
        type: Object,
        required: true,
    },

    contacts: {
        type: Array,
        default: () => [],
    },

    orderStatuses: {
        type: Array,
        default: () => [],
    },

    mode: {
        type: String,
        default: 'create',
    },
})

const emit = defineEmits(['submit'])

const selectedContact = computed(() =>
    props.contacts.find(
        item =>
            String(item.id) ===
            String(props.form.contact_id)
    ) ?? null
)
</script>

<template>
    <div class="order-form-page" dir="rtl">

        <section class="page-header">

            <div class="header-copy">

                <div class="header-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M3 6H21L19 20H5L3 6ZM8 6V5C8 2.79086 9.79086 1 12 1C14.2091 1 16 2.79086 16 5V6"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <div>
                    <div class="eyebrow">
                        مدیریت سفارش‌ها
                    </div>

                    <h1>
                        {{
                            mode === 'edit'
                                ? 'ویرایش سفارش'
                                : 'ثبت سفارش جدید'
                        }}
                    </h1>

                    <p>
                        {{
                            mode === 'edit'
                                ? 'اطلاعات و وضعیت سفارش را به‌روزرسانی کنید.'
                                : 'اطلاعات سفارش مشتری را ثبت کنید.'
                        }}
                    </p>
                </div>

            </div>

            <Link
                href="/orders"
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
                                    d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2>مشتری سفارش</h2>
                            <p>
                                مخاطب مرتبط با این سفارش را انتخاب کنید.
                            </p>
                        </div>

                    </div>

                    <div class="card-content">

                        <div class="field">
                            <label>
                                مخاطب
                                <span>*</span>
                            </label>

                            <select v-model="form.contact_id">

                                <option value="">
                                    انتخاب مخاطب
                                </option>

                                <option
                                    v-for="contact in contacts"
                                    :key="contact.id"
                                    :value="contact.id"
                                >
                                    {{ contact.name }}
                                    -
                                    {{ contact.business_name ?? 'بدون کسب‌وکار' }}
                                    -
                                    {{ contact.mobile }}
                                </option>

                            </select>

                            <div
                                v-if="form.errors.contact_id"
                                class="field-error"
                            >
                                {{ form.errors.contact_id }}
                            </div>
                        </div>


                        <div
                            v-if="selectedContact"
                            class="contact-preview"
                        >

                            <div class="avatar">
                                {{
                                    selectedContact.name
                                        ?.substring(0, 1)
                                        ?? '؟'
                                }}
                            </div>

                            <div class="contact-info">
                                <strong>
                                    {{ selectedContact.name }}
                                </strong>

                                <span>
                                    {{
                                        selectedContact.business_name
                                        ?? 'بدون کسب‌وکار'
                                    }}
                                </span>
                            </div>

                            <a
                                v-if="selectedContact.mobile"
                                :href="`tel:${selectedContact.mobile}`"
                                dir="ltr"
                            >
                                {{ selectedContact.mobile }}
                            </a>

                        </div>

                    </div>

                </section>


                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon violet">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M3 6H21L19 20H5L3 6ZM8 6V5C8 2.79086 9.79086 1 12 1C14.2091 1 16 2.79086 16 5V6"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2>اطلاعات سفارش</h2>
                            <p>
                                محصول و مبلغ سفارش
                            </p>
                        </div>

                    </div>

                    <div class="card-content">

                        <div class="form-grid">

                            <div class="field">

                                <label>
                                    محصول
                                    <span>*</span>
                                </label>

                                <input
                                    v-model="form.product_name"
                                    type="text"
                                    placeholder="نام محصول یا خدمت"
                                >

                                <div
                                    v-if="form.errors.product_name"
                                    class="field-error"
                                >
                                    {{ form.errors.product_name }}
                                </div>

                            </div>


                            <div class="field">

                                <label>
                                    مبلغ
                                    <span>*</span>
                                </label>

                                <input
                                    v-model="form.amount"
                                    type="number"
                                    min="0"
                                    step="0.01"
                                    placeholder="0"
                                >

                                <div
                                    v-if="form.errors.amount"
                                    class="field-error"
                                >
                                    {{ form.errors.amount }}
                                </div>

                            </div>

                        </div>

                    </div>

                </section>


                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon amber">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M4 6H20M7 12H17M10 18H14"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2>وضعیت سفارش</h2>
                            <p>
                                مرحله فعلی سفارش را مشخص کنید.
                            </p>
                        </div>

                    </div>

                    <div class="card-content">

                        <div class="field">

                            <label>
                                وضعیت
                            </label>

                            <select v-model="form.status">

                                <option
                                    v-for="status in orderStatuses"
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

                    </div>

                </section>


                <section class="form-card">

                    <div class="card-heading">

                        <div class="heading-icon green">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M4 4H20V20H4V4ZM8 8H16M8 12H16M8 16H12"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                />
                            </svg>
                        </div>

                        <div>
                            <h2>توضیحات</h2>
                            <p>
                                جزئیات و نکات تکمیلی سفارش
                            </p>
                        </div>

                    </div>

                    <div class="card-content">

                        <div class="field">

                            <textarea
                                v-model="form.description"
                                rows="6"
                                placeholder="توضیحات سفارش، شرایط، درخواست مشتری و..."
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

            </main>


            <aside>

                <section class="side-card">

                    <div class="side-icon">
                        <svg viewBox="0 0 24 24" fill="none">
                            <path
                                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22ZM12 8V12M12 16H12.01"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                            />
                        </svg>
                    </div>

                    <h3>
                        مدیریت سفارش
                    </h3>

                    <p>
                        وضعیت سفارش را با روند واقعی فروش هماهنگ نگه دارید.
                    </p>

                    <div class="tips">

                        <div>
                            <i></i>
                            نام محصول را واضح وارد کنید.
                        </div>

                        <div>
                            <i></i>
                            مبلغ سفارش را دقیق ثبت کنید.
                        </div>

                        <div>
                            <i></i>
                            بعد از هر تغییر، وضعیت سفارش را به‌روزرسانی کنید.
                        </div>

                    </div>

                </section>


                <section class="save-card">

                    <h3>
                        {{
                            mode === 'edit'
                                ? 'ذخیره تغییرات'
                                : 'ثبت سفارش'
                        }}
                    </h3>

                    <p>
                        اطلاعات سفارش در پرونده مشتری ذخیره خواهد شد.
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
                                    : 'ثبت سفارش'
                        }}

                    </button>


                    <Link
                        href="/orders"
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
.order-form-page {
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
    color: #4f46e5;
    background: linear-gradient(135deg,#eef2ff,#e0e7ff);
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
    grid-template-columns: minmax(0,1fr) 285px;
    gap: 18px;
    align-items: start;
}

.form-card,
.side-card,
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

.heading-icon.amber {
    color: #d97706;
    background: #fffbeb;
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
.field select,
.field textarea {
    width: 100% !important;
    min-height: 42px !important;
    font-size: 10px !important;
}

.field textarea {
    line-height: 1.9;
}

.field-error {
    margin-top: 5px;
    color: #dc2626;
    font-size: 8px;
    font-weight: 600;
}

.contact-preview {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 13px;
    padding: 11px;
    border: 1px solid #dbeafe;
    border-radius: 11px;
    background: #f8fbff;
}

.avatar {
    width: 36px;
    height: 36px;
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

.contact-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.contact-info strong {
    color: #334155;
    font-size: 9px;
}

.contact-info span {
    margin-top: 2px;
    color: #94a3b8;
    font-size: 8px;
}

.contact-preview a {
    color: #2563eb !important;
    font-size: 8px;
    font-weight: 700;
}

aside {
    position: sticky;
    top: 98px;
}

.side-card,
.save-card {
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
    color: #4f46e5;
    background: #eef2ff;
}

.side-icon svg {
    width: 18px;
    height: 18px;
}

.side-card h3,
.save-card h3 {
    margin: 0;
    color: #1e293b;
    font-size: 10px;
    font-weight: 800;
}

.side-card > p,
.save-card > p {
    margin: 6px 0 0;
    color: #94a3b8;
    font-size: 8px;
    line-height: 1.9;
}

.tips {
    display: flex;
    flex-direction: column;
    gap: 9px;
    margin-top: 14px;
}

.tips div {
    display: flex;
    align-items: flex-start;
    gap: 7px;
    color: #64748b;
    font-size: 8px;
    line-height: 1.8;
}

.tips i {
    width: 6px;
    height: 6px;
    flex-shrink: 0;
    margin-top: 4px;
    border-radius: 50%;
    background: #818cf8;
}

.save-card {
    border-color: #e0e7ff;
    background: linear-gradient(180deg,#fff,#f8f9ff);
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
    background: linear-gradient(135deg,#6366f1,#4f46e5);
    box-shadow: 0 7px 17px rgba(79,70,229,.20);
    font-size: 9px;
    font-weight: 700;
    transition: .16s ease;
}

.save-button:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 23px rgba(79,70,229,.26);
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

@media (max-width: 850px) {
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

    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
