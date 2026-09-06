<script setup>
import {
    Link,
    router,
    useForm,
} from '@inertiajs/vue3'

import {
    computed,
    ref,
    watch,
} from 'vue'

import PersianDateTimePicker from '../../Components/PersianDateTimePicker.vue'
import { formatPersianDateTime } from '../../utils/date'


const props = defineProps({
    contact: {
        type: Object,
        required: true,
    },

    smsTemplates: {
        type: Array,
        default: () => [],
    },

    smsVariables: {
        type: Object,
        default: () => ({}),
    },

    contactStatuses: {
        type: Array,
        default: () => [],
    },

    callResults: {
        type: Array,
        default: () => [],
    },

    orderStatuses: {
        type: Array,
        default: () => [],
    },
})


const activeAction = ref('call')
const activeHistory = ref('interactions')

const interactionDeleteTarget = ref(null)
const deletingInteraction = ref(false)


const smsForm = useForm({
    contact_id: props.contact.id,
    to: props.contact.mobile,
    template_id: '',
    message: '',
})


const interactionForm = useForm({
    contact_id: props.contact.id,
    type: 'call',
    subject: '',
    description: '',
    result: '',
    status_after_call: '',
    next_follow_up: '',
})


const followUpForm = useForm({
    contact_id: props.contact.id,
    title: '',
    description: '',
    follow_up_at: '',
    status: 'pending',
})


const orderForm = useForm({
    contact_id: props.contact.id,
    product_name: '',
    amount: '',
    status: 'new',
    description: '',
    return_to_contact: true,
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


const followUpStatusLabels = {
    pending: 'در انتظار',
    completed: 'انجام‌شده',
    cancelled: 'لغوشده',
}


const orderStatusLabels = {
    new: 'جدید',
    reviewing: 'در حال بررسی',
    awaiting_payment: 'در انتظار پرداخت',
    paid: 'پرداخت‌شده',
    completed: 'انجام‌شده',
    cancelled: 'لغوشده',
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


const interactionTypeLabels = {
    call: 'تماس',
    sms: 'پیامک',
    email: 'ایمیل',
    note: 'یادداشت',
    meeting: 'جلسه',
}


const interactionsCount = computed(
    () => props.contact.interactions?.length ?? 0
)

const followUpsCount = computed(
    () => props.contact.follow_ups?.length ?? 0
)

const ordersCount = computed(
    () => props.contact.orders?.length ?? 0
)

const totalOrdersAmount = computed(() => {
    return (
        props.contact.orders ?? []
    ).reduce(
        (sum, order) =>
            sum + Number(order.amount ?? 0),
        0
    )
})


const smsCharacters = computed(
    () => smsForm.message?.length ?? 0
)


const formatAmount = (amount) => {
    return Number(
        amount ?? 0
    ).toLocaleString('fa-IR')
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
    const map = {
        new: 'badge-slate',
        contacted: 'badge-blue',
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


const orderStatusClass = (status) => {
    const map = {
        new: 'badge-blue',
        reviewing: 'badge-violet',
        awaiting_payment: 'badge-amber',
        paid: 'badge-green',
        completed: 'badge-green',
        cancelled: 'badge-red',
    }

    return map[status] ?? 'badge-slate'
}


const followUpStatusClass = (status) => {
    const map = {
        pending: 'badge-amber',
        completed: 'badge-green',
        cancelled: 'badge-red',
    }

    return map[status] ?? 'badge-slate'
}


const callResultClass = (result) => {
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


const renderTemplate = (body) => {
    let message = body ?? ''

    Object.entries(
        props.smsVariables ?? {}
    ).forEach(([key, value]) => {
        const variable = `{{${key}}}`

        message = message
            .split(variable)
            .join(value ?? '')
    })

    return message
}


watch(
    () => smsForm.template_id,
    (templateId) => {
        if (!templateId) {
            smsForm.message = ''
            return
        }

        const template =
            props.smsTemplates.find(
                (item) =>
                    String(item.id) ===
                    String(templateId)
            )

        smsForm.message =
            template
                ? renderTemplate(template.body)
                : ''
    }
)


watch(
    () =>
        interactionForm.status_after_call,
    (status) => {
        if (status !== 'follow_up') {
            interactionForm.next_follow_up = ''
        }
    }
)


const sendSms = () => {
    smsForm.post('/sms/send', {
        preserveScroll: true,

        onSuccess: () => {
            smsForm.template_id = ''
            smsForm.message = ''

            router.reload({
                only: [
                    'contact',
                    'smsTemplates',
                    'smsVariables',
                ],
            })
        },
    })
}


const submitInteraction = () => {
    interactionForm.post(
        '/interactions',
        {
            preserveScroll: true,

            onSuccess: () => {
                interactionForm.reset()

                interactionForm.contact_id =
                    props.contact.id

                interactionForm.type =
                    'call'

                router.reload({
                    only: [
                        'contact',
                        'contactStatuses',
                        'callResults',
                    ],
                })
            },
        }
    )
}


const submitFollowUp = () => {
    followUpForm.post(
        '/followups',
        {
            preserveScroll: true,

            onSuccess: () => {
                followUpForm.reset()

                followUpForm.contact_id =
                    props.contact.id

                followUpForm.status =
                    'pending'

                router.reload({
                    only: ['contact'],
                })
            },
        }
    )
}


const submitOrder = () => {
    orderForm.post(
        '/orders',
        {
            preserveScroll: true,

            onSuccess: () => {
                orderForm.reset(
                    'product_name',
                    'amount',
                    'description'
                )

                orderForm.contact_id =
                    props.contact.id

                orderForm.status =
                    'new'

                orderForm.return_to_contact =
                    true

                router.reload({
                    only: ['contact'],
                })
            },
        }
    )
}


const requestInteractionDelete = (item) => {
    interactionDeleteTarget.value = item
}


const cancelInteractionDelete = () => {
    if (deletingInteraction.value) {
        return
    }

    interactionDeleteTarget.value = null
}


const confirmInteractionDelete = () => {
    if (
        !interactionDeleteTarget.value
        ||
        deletingInteraction.value
    ) {
        return
    }

    deletingInteraction.value = true

    router.delete(
        `/interactions/${interactionDeleteTarget.value.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {
                interactionDeleteTarget.value =
                    null
            },

            onFinish: () => {
                deletingInteraction.value =
                    false
            },
        }
    )
}
</script>


<template>
    <div
        class="contact-profile-page"
        dir="rtl"
    >

        <!-- =================================================
             PROFILE HERO
        ================================================== -->

        <section class="profile-hero">

            <div class="hero-glow hero-glow-one"></div>
            <div class="hero-glow hero-glow-two"></div>


            <div class="profile-main">

                <div class="profile-avatar">
                    {{ firstLetter(contact.name) }}
                </div>


                <div class="profile-copy">

                    <div class="profile-topline">

                        <span
                            class="status-badge"
                            :class="
                                statusClass(
                                    contact.status
                                )
                            "
                        >
                            <span class="badge-dot"></span>

                            {{
                                statusLabels[
                                    contact.status
                                ]
                                ?? contact.status
                            }}
                        </span>


                        <span class="contact-code">
                            مخاطب #{{ contact.id }}
                        </span>

                    </div>


                    <h1>
                        {{ contact.name }}
                    </h1>


                    <p>
                        {{
                            contact.business_name
                            ?? 'بدون نام کسب‌وکار'
                        }}
                    </p>


                    <div class="hero-contact-row">

                        <a
                            v-if="contact.mobile"
                            :href="`tel:${contact.mobile}`"
                            class="hero-contact-item"
                            dir="ltr"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M22 16.92V20C22 20.5523 21.5523 21 21 21C11.0589 21 3 12.9411 3 3C3 2.44772 3.44772 2 4 2H7.08"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                />
                            </svg>

                            {{ contact.mobile }}
                        </a>


                        <span
                            v-if="contact.city"
                            class="hero-contact-item"
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
                        </span>


                        <span
                            v-if="
                                contact.assigned_user?.name
                            "
                            class="hero-contact-item"
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M20 21V19C20 16.7909 18.2091 15 16 15H8C5.79086 15 4 16.7909 4 19V21M12 11C14.2091 11 16 9.20914 16 7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7C8 9.20914 9.79086 11 12 11Z"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    stroke-linecap="round"
                                />
                            </svg>

                            {{ contact.assigned_user.name }}
                        </span>

                    </div>

                </div>

            </div>


            <div class="hero-actions">

                <Link
                    href="/contacts"
                    class="hero-button hero-button-secondary"
                >
                    بازگشت به مخاطبین
                </Link>


                <Link
                    :href="`/contacts/${contact.id}/edit`"
                    class="hero-button hero-button-primary"
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
                        />
                    </svg>

                    ویرایش مخاطب
                </Link>

            </div>

        </section>


        <!-- =================================================
             KPI
        ================================================== -->

        <section class="profile-stats">

            <div class="profile-stat stat-blue">

                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M22 16.92V20C22 20.5523 21.5523 21 21 21C11.0589 21 3 12.9411 3 3C3 2.44772 3.44772 2 4 2"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                        />
                    </svg>
                </div>

                <div>
                    <strong>
                        {{ interactionsCount }}
                    </strong>

                    <span>
                        ارتباط ثبت‌شده
                    </span>
                </div>

            </div>


            <div class="profile-stat stat-amber">

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

                <div>
                    <strong>
                        {{ followUpsCount }}
                    </strong>

                    <span>
                        پیگیری
                    </span>
                </div>

            </div>


            <div class="profile-stat stat-violet">

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

                <div>
                    <strong>
                        {{ ordersCount }}
                    </strong>

                    <span>
                        سفارش
                    </span>
                </div>

            </div>


            <div class="profile-stat stat-green">

                <div class="stat-icon">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path
                            d="M4 19L9 14L13 18L20 9M15 9H20V14"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <div>
                    <strong class="amount-value">
                        {{
                            formatAmount(
                                totalOrdersAmount
                            )
                        }}
                    </strong>

                    <span>
                        مجموع سفارش‌ها
                    </span>
                </div>

            </div>

        </section>


        <!-- =================================================
             TWO COLUMN
        ================================================== -->

        <div class="profile-layout">

            <!-- Sidebar -->
            <aside class="profile-sidebar">

                <section class="info-card">

                    <div class="card-heading">

                        <div>
                            <h2>
                                اطلاعات مخاطب
                            </h2>

                            <p>
                                اطلاعات پایه و ارتباطی
                            </p>
                        </div>

                    </div>


                    <div class="info-list">

                        <div class="info-row">

                            <span>
                                موبایل
                            </span>

                            <strong dir="ltr">
                                {{ contact.mobile ?? '—' }}
                            </strong>

                        </div>


                        <div class="info-row">

                            <span>
                                تلفن
                            </span>

                            <strong dir="ltr">
                                {{ contact.phone ?? '—' }}
                            </strong>

                        </div>


                        <div class="info-row">

                            <span>
                                کسب‌وکار
                            </span>

                            <strong>
                                {{
                                    contact.business_name
                                    ?? '—'
                                }}
                            </strong>

                        </div>


                        <div class="info-row">

                            <span>
                                شهر
                            </span>

                            <strong>
                                {{ contact.city ?? '—' }}
                            </strong>

                        </div>


                        <div class="info-row">

                            <span>
                                دسته‌بندی
                            </span>

                            <strong>
                                {{
                                    contact.category
                                    ?? '—'
                                }}
                            </strong>

                        </div>


                        <div class="info-row">

                            <span>
                                منبع
                            </span>

                            <strong>
                                {{
                                    contact.source
                                    ?? '—'
                                }}
                            </strong>

                        </div>


                        <div class="info-row">

                            <span>
                                مسئول
                            </span>

                            <strong>
                                {{
                                    contact
                                        .assigned_user
                                        ?.name
                                    ?? 'تخصیص‌نیافته'
                                }}
                            </strong>

                        </div>

                    </div>


                    <div
                        v-if="contact.description"
                        class="contact-note"
                    >

                        <span>
                            توضیحات
                        </span>

                        <p>
                            {{ contact.description }}
                        </p>

                    </div>

                </section>

            </aside>


            <!-- Main workspace -->
            <main class="workspace">

                <!-- Action panel -->
                <section class="workspace-card">

                    <div class="workspace-header">

                        <div>

                            <h2>
                                عملیات مخاطب
                            </h2>

                            <p>
                                فعالیت موردنظر را انتخاب کنید
                            </p>

                        </div>

                    </div>


                    <div class="action-tabs">

                        <button
                            type="button"
                            :class="{
                                active:
                                    activeAction ===
                                    'call'
                            }"
                            @click="
                                activeAction = 'call'
                            "
                        >
                            ثبت تماس
                        </button>


                        <button
                            type="button"
                            :class="{
                                active:
                                    activeAction ===
                                    'sms'
                            }"
                            @click="
                                activeAction = 'sms'
                            "
                        >
                            ارسال پیامک
                        </button>


                        <button
                            type="button"
                            :class="{
                                active:
                                    activeAction ===
                                    'followup'
                            }"
                            @click="
                                activeAction =
                                    'followup'
                            "
                        >
                            ثبت پیگیری
                        </button>


                        <button
                            type="button"
                            :class="{
                                active:
                                    activeAction ===
                                    'order'
                            }"
                            @click="
                                activeAction =
                                    'order'
                            "
                        >
                            ثبت سفارش
                        </button>

                    </div>


                    <!-- CALL -->
                    <div
                        v-if="
                            activeAction === 'call'
                        "
                        class="action-content"
                    >

                        <form
                            class="premium-form"
                            @submit.prevent="
                                submitInteraction
                            "
                        >

                            <div class="form-grid">

                                <div class="form-field span-2">

                                    <label>
                                        عنوان تماس
                                    </label>

                                    <input
                                        v-model="
                                            interactionForm
                                                .subject
                                        "
                                        type="text"
                                        placeholder="مثلاً پیگیری درخواست دمو"
                                    >

                                </div>


                                <div class="form-field">

                                    <label>
                                        نتیجه تماس
                                    </label>

                                    <select
                                        v-model="
                                            interactionForm
                                                .result
                                        "
                                    >
                                        <option value="">
                                            انتخاب کنید
                                        </option>

                                        <option
                                            v-for="
                                                result in
                                                callResults
                                            "
                                            :key="
                                                result.value
                                            "
                                            :value="
                                                result.value
                                            "
                                        >
                                            {{ result.label }}
                                        </option>
                                    </select>

                                    <div
                                        v-if="
                                            interactionForm
                                                .errors
                                                .result
                                        "
                                        class="field-error"
                                    >
                                        {{
                                            interactionForm
                                                .errors
                                                .result
                                        }}
                                    </div>

                                </div>


                                <div class="form-field">

                                    <label>
                                        وضعیت بعد از تماس
                                    </label>

                                    <select
                                        v-model="
                                            interactionForm
                                                .status_after_call
                                        "
                                    >
                                        <option value="">
                                            انتخاب کنید
                                        </option>

                                        <option
                                            v-for="
                                                status in
                                                contactStatuses
                                            "
                                            :key="
                                                status.value
                                            "
                                            :value="
                                                status.value
                                            "
                                        >
                                            {{ status.label }}
                                        </option>
                                    </select>

                                    <div
                                        v-if="
                                            interactionForm
                                                .errors
                                                .status_after_call
                                        "
                                        class="field-error"
                                    >
                                        {{
                                            interactionForm
                                                .errors
                                                .status_after_call
                                        }}
                                    </div>

                                </div>


                                <div
                                    v-if="
                                        interactionForm
                                            .status_after_call
                                        === 'follow_up'
                                    "
                                    class="form-field span-2"
                                >

                                    <label>
                                        تاریخ و ساعت پیگیری بعدی
                                    </label>

                                    <PersianDateTimePicker
                                        v-model="
                                            interactionForm
                                                .next_follow_up
                                        "
                                        placeholder="تاریخ و ساعت را انتخاب کنید"
                                    />

                                    <div
                                        v-if="
                                            interactionForm
                                                .errors
                                                .next_follow_up
                                        "
                                        class="field-error"
                                    >
                                        {{
                                            interactionForm
                                                .errors
                                                .next_follow_up
                                        }}
                                    </div>

                                </div>


                                <div class="form-field span-2">

                                    <label>
                                        توضیحات تماس
                                    </label>

                                    <textarea
                                        v-model="
                                            interactionForm
                                                .description
                                        "
                                        rows="5"
                                        placeholder="شرح مکالمه، درخواست مشتری و نکات مهم..."
                                    ></textarea>

                                </div>

                            </div>


                            <div class="form-footer">

                                <button
                                    type="submit"
                                    class="submit-button submit-green"
                                    :disabled="
                                        interactionForm
                                            .processing
                                    "
                                >
                                    {{
                                        interactionForm
                                            .processing
                                            ? 'در حال ثبت...'
                                            : 'ثبت گزارش تماس'
                                    }}
                                </button>

                            </div>

                        </form>

                    </div>


                    <!-- SMS -->
                    <div
                        v-if="
                            activeAction === 'sms'
                        "
                        class="action-content"
                    >

                        <form
                            class="premium-form"
                            @submit.prevent="sendSms"
                        >

                            <div class="form-grid">

                                <div class="form-field">

                                    <label>
                                        قالب پیامک
                                    </label>

                                    <select
                                        v-model="
                                            smsForm.template_id
                                        "
                                    >
                                        <option value="">
                                            پیامک دستی
                                        </option>

                                        <option
                                            v-for="
                                                template in
                                                smsTemplates
                                            "
                                            :key="
                                                template.id
                                            "
                                            :value="
                                                template.id
                                            "
                                        >
                                            {{ template.title }}
                                        </option>
                                    </select>

                                </div>


                                <div class="form-field">

                                    <label>
                                        شماره دریافت‌کننده
                                    </label>

                                    <input
                                        v-model="
                                            smsForm.to
                                        "
                                        type="text"
                                        dir="ltr"
                                    >

                                </div>


                                <div class="form-field span-2">

                                    <div class="label-row">

                                        <label>
                                            متن پیامک
                                        </label>

                                        <span>
                                            {{ smsCharacters }}
                                            کاراکتر
                                        </span>

                                    </div>

                                    <textarea
                                        v-model="
                                            smsForm.message
                                        "
                                        :readonly="
                                            Boolean(
                                                smsForm
                                                    .template_id
                                            )
                                        "
                                        rows="7"
                                        placeholder="متن پیامک را بنویسید..."
                                    ></textarea>

                                    <div
                                        v-if="
                                            smsForm.errors
                                                .message
                                        "
                                        class="field-error"
                                    >
                                        {{
                                            smsForm.errors
                                                .message
                                        }}
                                    </div>

                                </div>

                            </div>


                            <div class="form-footer">

                                <button
                                    type="submit"
                                    class="submit-button submit-blue"
                                    :disabled="
                                        smsForm.processing
                                    "
                                >
                                    {{
                                        smsForm.processing
                                            ? 'در حال ارسال...'
                                            : 'ارسال پیامک'
                                    }}
                                </button>

                            </div>

                        </form>

                    </div>


                    <!-- FOLLOWUP -->
                    <div
                        v-if="
                            activeAction ===
                            'followup'
                        "
                        class="action-content"
                    >

                        <form
                            class="premium-form"
                            @submit.prevent="
                                submitFollowUp
                            "
                        >

                            <div class="form-grid">

                                <div class="form-field span-2">

                                    <label>
                                        عنوان پیگیری
                                    </label>

                                    <input
                                        v-model="
                                            followUpForm.title
                                        "
                                        type="text"
                                        placeholder="عنوان پیگیری"
                                    >

                                </div>


                                <div class="form-field span-2">

                                    <label>
                                        تاریخ و ساعت پیگیری
                                    </label>

                                    <PersianDateTimePicker
                                        v-model="
                                            followUpForm
                                                .follow_up_at
                                        "
                                        placeholder="تاریخ و ساعت را انتخاب کنید"
                                    />

                                    <div
                                        v-if="
                                            followUpForm
                                                .errors
                                                .follow_up_at
                                        "
                                        class="field-error"
                                    >
                                        {{
                                            followUpForm
                                                .errors
                                                .follow_up_at
                                        }}
                                    </div>

                                </div>


                                <div class="form-field span-2">

                                    <label>
                                        توضیحات
                                    </label>

                                    <textarea
                                        v-model="
                                            followUpForm
                                                .description
                                        "
                                        rows="5"
                                        placeholder="جزئیات پیگیری..."
                                    ></textarea>

                                </div>

                            </div>


                            <div class="form-footer">

                                <button
                                    type="submit"
                                    class="submit-button submit-violet"
                                    :disabled="
                                        followUpForm
                                            .processing
                                    "
                                >
                                    {{
                                        followUpForm
                                            .processing
                                            ? 'در حال ثبت...'
                                            : 'ثبت پیگیری'
                                    }}
                                </button>

                            </div>

                        </form>

                    </div>


                    <!-- ORDER -->
                    <div
                        v-if="
                            activeAction === 'order'
                        "
                        class="action-content"
                    >

                        <div
                            v-if="
                                contact.status !==
                                'customer'
                            "
                            class="locked-order"
                        >

                            <div class="locked-icon">
                                !
                            </div>

                            <div>
                                <strong>
                                    ثبت سفارش فعال نیست
                                </strong>

                                <p>
                                    برای ثبت سفارش، وضعیت مخاطب باید «مشتری شد» باشد.
                                </p>
                            </div>

                        </div>


                        <form
                            v-else
                            class="premium-form"
                            @submit.prevent="
                                submitOrder
                            "
                        >

                            <div class="form-grid">

                                <div class="form-field">

                                    <label>
                                        محصول
                                    </label>

                                    <input
                                        v-model="
                                            orderForm
                                                .product_name
                                        "
                                        type="text"
                                    >

                                    <div
                                        v-if="
                                            orderForm
                                                .errors
                                                .product_name
                                        "
                                        class="field-error"
                                    >
                                        {{
                                            orderForm
                                                .errors
                                                .product_name
                                        }}
                                    </div>

                                </div>


                                <div class="form-field">

                                    <label>
                                        مبلغ
                                    </label>

                                    <input
                                        v-model="
                                            orderForm.amount
                                        "
                                        type="number"
                                        min="0"
                                        step="0.01"
                                    >

                                    <div
                                        v-if="
                                            orderForm
                                                .errors
                                                .amount
                                        "
                                        class="field-error"
                                    >
                                        {{
                                            orderForm
                                                .errors
                                                .amount
                                        }}
                                    </div>

                                </div>


                                <div class="form-field span-2">

                                    <label>
                                        وضعیت سفارش
                                    </label>

                                    <select
                                        v-model="
                                            orderForm.status
                                        "
                                    >
                                        <option
                                            v-for="
                                                status in
                                                orderStatuses
                                            "
                                            :key="
                                                status.value
                                            "
                                            :value="
                                                status.value
                                            "
                                        >
                                            {{ status.label }}
                                        </option>
                                    </select>

                                </div>


                                <div class="form-field span-2">

                                    <label>
                                        توضیحات
                                    </label>

                                    <textarea
                                        v-model="
                                            orderForm
                                                .description
                                        "
                                        rows="5"
                                    ></textarea>

                                </div>

                            </div>


                            <div class="form-footer">

                                <button
                                    type="submit"
                                    class="submit-button submit-indigo"
                                    :disabled="
                                        orderForm.processing
                                    "
                                >
                                    {{
                                        orderForm.processing
                                            ? 'در حال ثبت...'
                                            : 'ثبت سفارش'
                                    }}
                                </button>

                            </div>

                        </form>

                    </div>

                </section>


                <!-- History -->
                <section class="workspace-card history-card">

                    <div class="workspace-header">

                        <div>
                            <h2>
                                پرونده فعالیت‌ها
                            </h2>

                            <p>
                                تاریخچه کامل این مخاطب
                            </p>
                        </div>

                    </div>


                    <div class="history-tabs">

                        <button
                            type="button"
                            :class="{
                                active:
                                    activeHistory ===
                                    'interactions'
                            }"
                            @click="
                                activeHistory =
                                    'interactions'
                            "
                        >
                            ارتباطات

                            <span>
                                {{ interactionsCount }}
                            </span>
                        </button>


                        <button
                            type="button"
                            :class="{
                                active:
                                    activeHistory ===
                                    'followups'
                            }"
                            @click="
                                activeHistory =
                                    'followups'
                            "
                        >
                            پیگیری‌ها

                            <span>
                                {{ followUpsCount }}
                            </span>
                        </button>


                        <button
                            type="button"
                            :class="{
                                active:
                                    activeHistory ===
                                    'orders'
                            }"
                            @click="
                                activeHistory =
                                    'orders'
                            "
                        >
                            سفارش‌ها

                            <span>
                                {{ ordersCount }}
                            </span>
                        </button>

                    </div>


                    <!-- Interactions -->
                    <div
                        v-if="
                            activeHistory ===
                            'interactions'
                        "
                        class="table-scroll"
                    >

                        <table class="premium-table">

                            <thead>
                                <tr>
                                    <th>نوع</th>
                                    <th>عنوان</th>
                                    <th>نتیجه</th>
                                    <th>وضعیت بعد از تماس</th>
                                    <th>کاربر</th>
                                    <th>تاریخ</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>


                            <tbody>

                                <tr
                                    v-for="
                                        item in
                                        contact.interactions
                                    "
                                    :key="item.id"
                                >

                                    <td>
                                        <span class="type-chip">
                                            {{
                                                interactionTypeLabels[
                                                    item.type
                                                ]
                                                ?? item.type
                                            }}
                                        </span>
                                    </td>


                                    <td>
                                        {{
                                            item.subject
                                            ?? '—'
                                        }}
                                    </td>


                                    <td>
                                        <span
                                            class="status-badge"
                                            :class="
                                                callResultClass(
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
                                                statusClass(
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


                                    <td>
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
                                        <button
                                            type="button"
                                            class="delete-mini-button"
                                            @click="
                                                requestInteractionDelete(
                                                    item
                                                )
                                            "
                                        >
                                            حذف
                                        </button>
                                    </td>

                                </tr>


                                <tr
                                    v-if="
                                        !contact.interactions
                                        ?.length
                                    "
                                >
                                    <td
                                        colspan="7"
                                        class="empty-cell"
                                    >
                                        ارتباطی ثبت نشده است.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <!-- Followups -->
                    <div
                        v-if="
                            activeHistory ===
                            'followups'
                        "
                        class="table-scroll"
                    >

                        <table class="premium-table">

                            <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>تاریخ و ساعت</th>
                                    <th>وضعیت</th>
                                    <th>کاربر</th>
                                </tr>
                            </thead>


                            <tbody>

                                <tr
                                    v-for="
                                        item in
                                        contact.follow_ups
                                    "
                                    :key="item.id"
                                >

                                    <td>
                                        {{
                                            item.title
                                            ?? '—'
                                        }}
                                    </td>


                                    <td class="date-cell">
                                        {{
                                            formatPersianDateTime(
                                                item.follow_up_at
                                            )
                                        }}
                                    </td>


                                    <td>
                                        <span
                                            class="status-badge"
                                            :class="
                                                followUpStatusClass(
                                                    item.status
                                                )
                                            "
                                        >
                                            {{
                                                followUpStatusLabels[
                                                    item.status
                                                ]
                                                ?? item.status
                                            }}
                                        </span>
                                    </td>


                                    <td>
                                        {{
                                            item.user?.name
                                            ?? '—'
                                        }}
                                    </td>

                                </tr>


                                <tr
                                    v-if="
                                        !contact.follow_ups
                                        ?.length
                                    "
                                >
                                    <td
                                        colspan="4"
                                        class="empty-cell"
                                    >
                                        پیگیری ثبت نشده است.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>


                    <!-- Orders -->
                    <div
                        v-if="
                            activeHistory ===
                            'orders'
                        "
                        class="table-scroll"
                    >

                        <table class="premium-table">

                            <thead>
                                <tr>
                                    <th>محصول</th>
                                    <th>مبلغ</th>
                                    <th>وضعیت</th>
                                    <th>ثبت‌کننده</th>
                                    <th>تاریخ</th>
                                    <th>عملیات</th>
                                </tr>
                            </thead>


                            <tbody>

                                <tr
                                    v-for="
                                        order in
                                        contact.orders
                                    "
                                    :key="order.id"
                                >

                                    <td class="product-name">
                                        {{ order.product_name }}
                                    </td>


                                    <td>
                                        <strong class="order-amount">
                                            {{
                                                formatAmount(
                                                    order.amount
                                                )
                                            }}
                                        </strong>
                                    </td>


                                    <td>
                                        <span
                                            class="status-badge"
                                            :class="
                                                orderStatusClass(
                                                    order.status
                                                )
                                            "
                                        >
                                            {{
                                                orderStatusLabels[
                                                    order.status
                                                ]
                                                ?? order.status
                                            }}
                                        </span>
                                    </td>


                                    <td>
                                        {{
                                            order.user?.name
                                            ?? '—'
                                        }}
                                    </td>


                                    <td class="date-cell">
                                        {{
                                            formatPersianDateTime(
                                                order.created_at
                                            )
                                        }}
                                    </td>


                                    <td>
                                        <Link
                                            :href="
                                                `/orders/${order.id}/edit`
                                            "
                                            class="edit-mini-button"
                                        >
                                            ویرایش
                                        </Link>
                                    </td>

                                </tr>


                                <tr
                                    v-if="
                                        !contact.orders
                                        ?.length
                                    "
                                >
                                    <td
                                        colspan="6"
                                        class="empty-cell"
                                    >
                                        سفارشی ثبت نشده است.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </section>

            </main>

        </div>


        <!-- DELETE MODAL -->
        <Teleport to="body">

            <div
                v-if="interactionDeleteTarget"
                class="delete-overlay"
                dir="rtl"
                @click.self="
                    cancelInteractionDelete
                "
            >

                <div class="delete-modal">

                    <div class="delete-icon">
                        !
                    </div>


                    <h3>
                        حذف گزارش ارتباط؟
                    </h3>


                    <p>
                        این گزارش از تاریخچه مخاطب حذف می‌شود و این عملیات قابل بازگشت نیست.
                    </p>


                    <div class="delete-actions">

                        <button
                            type="button"
                            class="cancel-button"
                            :disabled="
                                deletingInteraction
                            "
                            @click="
                                cancelInteractionDelete
                            "
                        >
                            انصراف
                        </button>


                        <button
                            type="button"
                            class="confirm-delete-button"
                            :disabled="
                                deletingInteraction
                            "
                            @click="
                                confirmInteractionDelete
                            "
                        >
                            {{
                                deletingInteraction
                                    ? 'در حال حذف...'
                                    : 'حذف گزارش'
                            }}
                        </button>

                    </div>

                </div>

            </div>

        </Teleport>

    </div>
</template>


<style scoped>

.contact-profile-page {
    width: 100%;
    max-width: 1600px;
    margin: 0 auto;
    padding-bottom: 32px;
}


/* HERO */

.profile-hero {
    position: relative;
    overflow: hidden;

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 28px;

    min-height: 205px;

    margin-bottom: 20px;
    padding: 30px 32px;

    border-radius: 22px;

    color: #ffffff;

    background:
        linear-gradient(
            125deg,
            #102b55 0%,
            #17478a 50%,
            #2563eb 100%
        );

    box-shadow:
        0 17px 45px
        rgba(15, 45, 92, 0.18);
}


.profile-hero::after {
    content: '';

    position: absolute;
    inset: 0;

    background:
        linear-gradient(
            90deg,
            rgba(255,255,255,.035) 1px,
            transparent 1px
        ),
        linear-gradient(
            rgba(255,255,255,.035) 1px,
            transparent 1px
        );

    background-size: 36px 36px;

    pointer-events: none;
}


.hero-glow {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}


.hero-glow-one {
    width: 250px;
    height: 250px;
    left: -80px;
    top: -120px;

    background:
        rgba(255,255,255,.08);
}


.hero-glow-two {
    width: 180px;
    height: 180px;
    left: 20%;
    bottom: -130px;

    background:
        rgba(56,189,248,.14);
}


.profile-main {
    position: relative;
    z-index: 2;

    display: flex;
    align-items: center;

    gap: 18px;

    min-width: 0;
}


.profile-avatar {
    width: 82px;
    height: 82px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border:
        1px solid
        rgba(255,255,255,.26);

    border-radius: 22px;

    color: #ffffff;

    background:
        rgba(255,255,255,.12);

    box-shadow:
        inset 0 1px 0
        rgba(255,255,255,.15);

    font-size: 29px;
    font-weight: 800;

    backdrop-filter: blur(8px);
}


.profile-copy {
    min-width: 0;
}


.profile-topline {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 8px;

    margin-bottom: 7px;
}


.contact-code {
    color:
        rgba(255,255,255,.56);

    font-size: 10px;
}


.profile-copy h1 {
    margin: 0;

    color: #ffffff !important;

    font-size: 27px !important;
    font-weight: 800 !important;
}


.profile-copy > p {
    margin: 5px 0 0;

    color:
        rgba(255,255,255,.68);

    font-size: 12px;
}


.hero-contact-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 9px;

    margin-top: 16px;
}


.hero-contact-item {
    display: inline-flex;
    align-items: center;

    gap: 6px;

    padding: 6px 9px;

    border:
        1px solid
        rgba(255,255,255,.10);

    border-radius: 8px;

    color:
        rgba(255,255,255,.78);

    background:
        rgba(255,255,255,.07);

    font-size: 10px;
}


.hero-contact-item svg {
    width: 14px;
    height: 14px;
}


.hero-actions {
    position: relative;
    z-index: 2;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    flex-wrap: wrap;

    gap: 9px;
}


.hero-button {
    min-height: 42px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    gap: 7px;

    padding: 0 14px;

    border-radius: 11px;

    font-size: 10px;
    font-weight: 700;

    transition:
        transform .18s ease,
        background .18s ease;
}


.hero-button:hover {
    transform:
        translateY(-2px);
}


.hero-button svg {
    width: 16px;
    height: 16px;
}


.hero-button-primary {
    color: #17478a;

    background: #ffffff;

    box-shadow:
        0 8px 20px
        rgba(0,0,0,.14);
}


.hero-button-secondary {
    color: #ffffff;

    border:
        1px solid
        rgba(255,255,255,.15);

    background:
        rgba(255,255,255,.08);
}


/* KPI */

.profile-stats {
    display: grid;

    grid-template-columns:
        repeat(4, minmax(0, 1fr));

    gap: 13px;

    margin-bottom: 20px;
}


.profile-stat {
    min-height: 90px;

    display: flex;
    align-items: center;

    gap: 12px;

    padding: 15px;

    border:
        1px solid #e6ebf3;

    border-radius: 15px;

    background: #ffffff;

    box-shadow:
        0 6px 20px
        rgba(15,23,42,.04);

    transition:
        transform .18s ease,
        box-shadow .18s ease;
}


.profile-stat:hover {
    transform: translateY(-3px);

    box-shadow:
        0 12px 27px
        rgba(15,23,42,.07);
}


.stat-icon {
    width: 42px;
    height: 42px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: currentColor;
}


.stat-icon svg {
    width: 20px;
    height: 20px;

    color: #ffffff;
}


.profile-stat > div:last-child {
    display: flex;
    flex-direction: column;
}


.profile-stat strong {
    color: #172033;

    font-size: 18px;
    font-weight: 800;
}


.profile-stat span {
    margin-top: 2px;

    color: #94a3b8;

    font-size: 9px;
}


.amount-value {
    font-size: 14px !important;
}


.stat-blue {
    color: #2563eb;
}


.stat-amber {
    color: #d97706;
}


.stat-violet {
    color: #7c3aed;
}


.stat-green {
    color: #059669;
}


/* LAYOUT */

.profile-layout {
    display: grid;

    grid-template-columns:
        300px minmax(0, 1fr);

    gap: 18px;

    align-items: start;
}


.profile-sidebar {
    position: sticky;
    top: 98px;
}


.info-card,
.workspace-card {
    overflow: hidden;

    border:
        1px solid #e5ebf3;

    border-radius: 18px;

    background: #ffffff;

    box-shadow:
        0 8px 28px
        rgba(15,23,42,.04);
}


.card-heading,
.workspace-header {
    padding: 17px 18px;

    border-bottom:
        1px solid #edf2f7;
}


.card-heading h2,
.workspace-header h2 {
    margin: 0;

    color: #1e293b;

    font-size: 13px;
    font-weight: 800;
}


.card-heading p,
.workspace-header p {
    margin: 3px 0 0;

    color: #94a3b8;

    font-size: 9px;
}


.info-list {
    padding: 5px 17px;
}


.info-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;

    gap: 16px;

    padding: 11px 0;

    border-bottom:
        1px solid #f1f5f9;
}


.info-row:last-child {
    border-bottom: 0;
}


.info-row span {
    color: #94a3b8;

    font-size: 9px;
}


.info-row strong {
    max-width: 165px;

    color: #334155;

    font-size: 10px;
    font-weight: 700;

    text-align: left;

    word-break: break-word;
}


.contact-note {
    margin: 8px 14px 14px;
    padding: 12px;

    border:
        1px solid #e0e7ff;

    border-radius: 11px;

    background: #f8faff;
}


.contact-note span {
    color: #6366f1;

    font-size: 9px;
    font-weight: 700;
}


.contact-note p {
    margin: 5px 0 0;

    color: #64748b;

    font-size: 9px;
    line-height: 1.9;
}


/* WORKSPACE */

.workspace {
    min-width: 0;
}


.workspace-card {
    margin-bottom: 18px;
}


.action-tabs,
.history-tabs {
    display: flex;
    align-items: center;

    gap: 5px;

    padding: 9px 12px;

    overflow-x: auto;

    border-bottom:
        1px solid #edf2f7;

    background: #f8fafc;
}


.action-tabs button,
.history-tabs button {
    flex-shrink: 0;

    min-height: 35px;

    padding: 0 12px;

    border: 1px solid transparent;
    border-radius: 9px;

    color: #64748b;

    background: transparent;

    font-size: 9px;
    font-weight: 700;

    transition:
        color .16s ease,
        background .16s ease,
        box-shadow .16s ease;
}


.action-tabs button:hover,
.history-tabs button:hover {
    color: #2563eb;

    background: #ffffff;
}


.action-tabs button.active,
.history-tabs button.active {
    color: #2563eb;

    border-color: #dbeafe;

    background: #ffffff;

    box-shadow:
        0 3px 10px
        rgba(15,23,42,.05);
}


.history-tabs button {
    display: inline-flex;
    align-items: center;

    gap: 6px;
}


.history-tabs button span {
    min-width: 19px;
    height: 19px;

    display: inline-flex;
    align-items: center;
    justify-content: center;

    padding: 0 5px;

    border-radius: 999px;

    color: #64748b;

    background: #e9eef5;

    font-size: 8px;
}


.history-tabs button.active span {
    color: #2563eb;

    background: #eff6ff;
}


.action-content {
    padding: 20px;
}


/* FORM */

.premium-form {
    width: 100%;
}


.form-grid {
    display: grid;

    grid-template-columns:
        repeat(2, minmax(0, 1fr));

    gap: 15px;
}


.span-2 {
    grid-column: span 2;
}


.form-field {
    min-width: 0;
}


.form-field label {
    display: block;

    margin-bottom: 6px;

    color: #475569;

    font-size: 9px;
    font-weight: 700;
}


.label-row {
    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 12px;
}


.label-row span {
    color: #94a3b8;

    font-size: 8px;
}


.form-field input,
.form-field select,
.form-field textarea {
    width: 100% !important;

    font-size: 10px !important;
}


.form-field textarea {
    line-height: 1.9;
}


.field-error {
    margin-top: 5px;

    color: #dc2626;

    font-size: 8px;
}


.form-footer {
    display: flex;
    justify-content: flex-end;

    margin-top: 18px;
    padding-top: 16px;

    border-top:
        1px solid #edf2f7;
}


.submit-button {
    min-width: 130px;
    min-height: 40px;

    padding: 0 17px;

    border: 0;
    border-radius: 10px;

    color: #ffffff;

    font-size: 9px;
    font-weight: 700;

    transition:
        transform .16s ease,
        opacity .16s ease,
        box-shadow .16s ease;
}


.submit-button:hover:not(:disabled) {
    transform:
        translateY(-2px);
}


.submit-button:disabled {
    opacity: .55;
    cursor: not-allowed;
}


.submit-green {
    background:
        linear-gradient(
            135deg,
            #10b981,
            #059669
        );

    box-shadow:
        0 6px 15px
        rgba(5,150,105,.18);
}


.submit-blue {
    background:
        linear-gradient(
            135deg,
            #3b82f6,
            #2563eb
        );

    box-shadow:
        0 6px 15px
        rgba(37,99,235,.18);
}


.submit-violet {
    background:
        linear-gradient(
            135deg,
            #8b5cf6,
            #7c3aed
        );

    box-shadow:
        0 6px 15px
        rgba(124,58,237,.18);
}


.submit-indigo {
    background:
        linear-gradient(
            135deg,
            #6366f1,
            #4f46e5
        );

    box-shadow:
        0 6px 15px
        rgba(79,70,229,.18);
}


.locked-order {
    display: flex;
    align-items: flex-start;

    gap: 12px;

    padding: 20px;

    border:
        1px solid #fde68a;

    border-radius: 13px;

    color: #92400e;

    background: #fffbeb;
}


.locked-icon {
    width: 34px;
    height: 34px;

    flex-shrink: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 10px;

    color: #d97706;

    background: #fef3c7;

    font-size: 16px;
    font-weight: 800;
}


.locked-order strong {
    font-size: 11px;
}


.locked-order p {
    margin: 4px 0 0;

    color: #a16207;

    font-size: 9px;
}


/* TABLE */

.table-scroll {
    width: 100%;

    overflow-x: auto;
}


.premium-table {
    width: 100%;
    min-width: 790px;

    border: 0 !important;
    border-radius: 0 !important;

    box-shadow: none !important;
}


.premium-table thead {
    background: #f8fafc;
}


.premium-table th {
    padding:
        12px 15px !important;

    color:
        #64748b !important;

    border-bottom:
        1px solid #edf2f7 !important;

    font-size:
        9px !important;

    font-weight:
        700 !important;

    text-align: right;
    white-space: nowrap;
}


.premium-table td {
    padding:
        13px 15px !important;

    color:
        #475569 !important;

    border-bottom:
        1px solid #f1f5f9 !important;

    font-size:
        9px !important;

    vertical-align: middle;
}


.premium-table tbody tr:hover {
    background:
        #fbfdff !important;
}


.premium-table tbody tr:last-child td {
    border-bottom:
        0 !important;
}


.type-chip {
    display: inline-flex;

    padding: 5px 8px;

    border-radius: 7px;

    color: #475569;

    background: #f1f5f9;

    font-size: 8px;
    font-weight: 700;
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


.badge-dot {
    width: 5px;
    height: 5px;

    border-radius: 50%;

    background: currentColor;
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


.date-cell {
    color:
        #64748b !important;

    white-space: nowrap;
}


.order-amount {
    color: #059669;

    font-weight: 800;
}


.product-name {
    color:
        #1e293b !important;

    font-weight: 700;
}


.edit-mini-button,
.delete-mini-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;

    min-width: 52px;
    min-height: 28px;

    padding: 0 8px;

    border-radius: 8px;

    font-size: 8px;
    font-weight: 700;

    transition:
        transform .16s ease,
        background .16s ease;
}


.edit-mini-button {
    color:
        #2563eb !important;

    background: #eff6ff;
}


.delete-mini-button {
    color: #dc2626;

    border: 0;

    background: #fef2f2;
}


.edit-mini-button:hover,
.delete-mini-button:hover {
    transform:
        translateY(-1px);
}


.empty-cell {
    padding:
        35px 20px !important;

    color:
        #94a3b8 !important;

    text-align:
        center !important;
}


/* DELETE MODAL */

.delete-overlay {
    position: fixed;

    z-index: 9999;
    inset: 0;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 20px;

    background:
        rgba(15,23,42,.48);

    backdrop-filter:
        blur(5px);
}


.delete-modal {
    width: 100%;
    max-width: 380px;

    padding: 26px;

    border-radius: 19px;

    background: #ffffff;

    box-shadow:
        0 24px 65px
        rgba(15,23,42,.26);

    text-align: center;
}


.delete-icon {
    width: 50px;
    height: 50px;

    display: flex;
    align-items: center;
    justify-content: center;

    margin: 0 auto 13px;

    border-radius: 14px;

    color: #dc2626;

    background: #fef2f2;

    font-size: 20px;
    font-weight: 800;
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


.delete-actions {
    display: grid;

    grid-template-columns:
        1fr 1fr;

    gap: 8px;

    margin-top: 18px;
}


.delete-actions button {
    min-height: 39px;

    border-radius: 10px;

    font-size: 9px;
    font-weight: 700;
}


.cancel-button {
    color: #64748b;

    border:
        1px solid #e2e8f0;

    background: #ffffff;
}


.confirm-delete-button {
    color: #ffffff;

    border: 0;

    background:
        linear-gradient(
            135deg,
            #ef4444,
            #dc2626
        );
}


/* RESPONSIVE */

@media (max-width: 1150px) {

    .profile-layout {
        grid-template-columns:
            260px minmax(0, 1fr);
    }


    .profile-stats {
        grid-template-columns:
            repeat(2, minmax(0, 1fr));
    }

}


@media (max-width: 900px) {

    .profile-hero {
        align-items: flex-start;
        flex-direction: column;
    }


    .profile-layout {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .profile-sidebar {
        position: static;
    }

}


@media (max-width: 650px) {

    .profile-hero {
        padding: 24px 20px;
    }


    .profile-main {
        align-items: flex-start;
    }


    .profile-avatar {
        width: 62px;
        height: 62px;

        border-radius: 17px;

        font-size: 22px;
    }


    .profile-copy h1 {
        font-size:
            22px !important;
    }


    .profile-stats {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .form-grid {
        grid-template-columns:
            minmax(0, 1fr);
    }


    .span-2 {
        grid-column: span 1;
    }


    .hero-actions {
        width: 100%;
    }


    .hero-button {
        flex: 1;
    }

}

</style>
