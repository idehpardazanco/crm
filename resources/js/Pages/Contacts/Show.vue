<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    contact: Object,
    smsTemplates: Array,
    smsVariables: Object,
    contactStatuses: Array,
    callResults: Array,
    orderStatuses: Array,
})

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

const orderStatusLabels = {
    new: 'جدید',
    reviewing: 'در حال بررسی',
    awaiting_payment: 'در انتظار پرداخت',
    paid: 'پرداخت شده',
    completed: 'انجام شده',
    cancelled: 'لغو شده',
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

const formatAmount = (amount) => {
    return Number(
        amount ?? 0
    ).toLocaleString('fa-IR')
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
                ? renderTemplate(
                    template.body
                )
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

const removeInteraction = (id) => {
    if (
        !confirm(
            'گزارش ارتباط حذف شود؟'
        )
    ) {
        return
    }

    router.delete(
        `/interactions/${id}`,
        {
            preserveScroll: true,
        }
    )
}
</script>

<template>
    <div
        class="p-6"
        dir="rtl"
    >

        <!-- اطلاعات مخاطب -->
        <div class="mb-6">

            <h1 class="text-2xl font-bold mb-4">
                {{ contact.name }}
            </h1>

            <div class="border rounded p-5">

                <div
                    class="
                        grid
                        grid-cols-1
                        md:grid-cols-2
                        gap-3
                    "
                >

                    <p>
                        <strong>موبایل:</strong>
                        {{ contact.mobile }}
                    </p>

                    <p>
                        <strong>تلفن:</strong>
                        {{ contact.phone ?? '-' }}
                    </p>

                    <p>
                        <strong>کسب‌وکار:</strong>
                        {{ contact.business_name ?? '-' }}
                    </p>

                    <p>
                        <strong>شهر:</strong>
                        {{ contact.city ?? '-' }}
                    </p>

                    <p>
                        <strong>دسته‌بندی:</strong>
                        {{ contact.category ?? '-' }}
                    </p>

                    <p>
                        <strong>منبع:</strong>
                        {{ contact.source ?? '-' }}
                    </p>

                    <p>
                        <strong>وضعیت:</strong>

                        {{
                            statusLabels[
                                contact.status
                            ] ?? contact.status
                        }}
                    </p>

                    <p>
                        <strong>مسئول:</strong>

                        {{
                            contact.assigned_user
                                ?.name
                                ?? '-'
                        }}
                    </p>

                </div>

                <div
                    v-if="contact.description"
                    class="mt-4"
                >
                    <strong>
                        توضیحات:
                    </strong>

                    <p class="mt-1">
                        {{ contact.description }}
                    </p>
                </div>

            </div>

        </div>


        <!-- ثبت تماس -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                ثبت تماس
            </h2>

            <form
                @submit.prevent="
                    submitInteraction
                "
            >

                <input
                    v-model="
                        interactionForm.subject
                    "
                    type="text"
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                    placeholder="عنوان تماس"
                >


                <select
                    v-model="
                        interactionForm.result
                    "
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                >

                    <option value="">
                        نتیجه تماس را انتخاب کنید
                    </option>

                    <option
                        v-for="
                            result in callResults
                        "
                        :key="result.value"
                        :value="result.value"
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
                    class="text-red-600 mb-3"
                >
                    {{
                        interactionForm
                            .errors
                            .result
                    }}
                </div>


                <select
                    v-model="
                        interactionForm
                            .status_after_call
                    "
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                >

                    <option value="">
                        وضعیت مخاطب بعد از تماس
                    </option>

                    <option
                        v-for="
                            status in
                            contactStatuses
                        "
                        :key="status.value"
                        :value="status.value"
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
                    class="text-red-600 mb-3"
                >
                    {{
                        interactionForm
                            .errors
                            .status_after_call
                    }}
                </div>


                <textarea
                    v-model="
                        interactionForm
                            .description
                    "
                    rows="5"
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                    placeholder="توضیحات تماس"
                ></textarea>


                <div
                    v-if="
                        interactionForm
                            .status_after_call
                        === 'follow_up'
                    "
                    class="mb-3"
                >

                    <label class="block mb-2">
                        تاریخ و ساعت پیگیری
                    </label>

                    <input
                        v-model="
                            interactionForm
                                .next_follow_up
                        "
                        type="datetime-local"
                        class="
                            border
                            p-2
                            w-full
                            rounded
                        "
                    >

                    <div
                        v-if="
                            interactionForm
                                .errors
                                .next_follow_up
                        "
                        class="text-red-600 mt-1"
                    >
                        {{
                            interactionForm
                                .errors
                                .next_follow_up
                        }}
                    </div>

                </div>


                <button
                    type="submit"
                    :disabled="
                        interactionForm
                            .processing
                    "
                    class="
                        bg-green-600
                        text-white
                        px-5
                        py-2
                        rounded
                    "
                >
                    ثبت تماس
                </button>

            </form>

        </div>


        <!-- ارسال پیامک -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                ارسال پیامک
            </h2>

            <form @submit.prevent="sendSms">

                <select
                    v-model="
                        smsForm.template_id
                    "
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
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
                        :key="template.id"
                        :value="template.id"
                    >
                        {{ template.title }}
                    </option>

                </select>


                <input
                    v-model="smsForm.to"
                    type="text"
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                >


                <textarea
                    v-model="smsForm.message"
                    :readonly="
                        Boolean(
                            smsForm.template_id
                        )
                    "
                    rows="7"
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                    placeholder="متن پیامک"
                ></textarea>

                <div
                    v-if="
                        smsForm.errors.message
                    "
                    class="text-red-600 mb-3"
                >
                    {{
                        smsForm.errors.message
                    }}
                </div>


                <button
                    type="submit"
                    :disabled="
                        smsForm.processing
                    "
                    class="
                        bg-blue-600
                        text-white
                        px-5
                        py-2
                        rounded
                    "
                >
                    ارسال پیامک
                </button>

            </form>

        </div>


        <!-- ثبت سفارش -->
        <div
            v-if="
                contact.status ===
                'customer'
            "
            class="border rounded p-5 mb-6"
        >

            <h2 class="font-bold text-lg mb-4">
                ثبت سفارش جدید
            </h2>

            <form @submit.prevent="submitOrder">

                <div class="mb-3">

                    <label class="block mb-2">
                        محصول
                    </label>

                    <input
                        v-model="
                            orderForm
                                .product_name
                        "
                        type="text"
                        class="
                            border
                            p-2
                            w-full
                            rounded
                        "
                    >

                    <div
                        v-if="
                            orderForm
                                .errors
                                .product_name
                        "
                        class="text-red-600 mt-1"
                    >
                        {{
                            orderForm
                                .errors
                                .product_name
                        }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        مبلغ
                    </label>

                    <input
                        v-model="
                            orderForm.amount
                        "
                        type="number"
                        min="0"
                        step="0.01"
                        class="
                            border
                            p-2
                            w-full
                            rounded
                        "
                    >

                    <div
                        v-if="
                            orderForm
                                .errors
                                .amount
                        "
                        class="text-red-600 mt-1"
                    >
                        {{
                            orderForm
                                .errors
                                .amount
                        }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        وضعیت سفارش
                    </label>

                    <select
                        v-model="
                            orderForm.status
                        "
                        class="
                            border
                            p-2
                            w-full
                            rounded
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


                <div class="mb-3">

                    <label class="block mb-2">
                        توضیحات
                    </label>

                    <textarea
                        v-model="
                            orderForm
                                .description
                        "
                        rows="4"
                        class="
                            border
                            p-2
                            w-full
                            rounded
                        "
                    ></textarea>

                </div>


                <button
                    type="submit"
                    :disabled="
                        orderForm.processing
                    "
                    class="
                        bg-indigo-600
                        text-white
                        px-5
                        py-2
                        rounded
                    "
                >
                    ثبت سفارش
                </button>

            </form>

        </div>


        <div
            v-else
            class="
                border
                border-yellow-300
                bg-yellow-50
                rounded
                p-4
                mb-6
            "
        >
            برای ثبت سفارش، وضعیت مخاطب باید
            «مشتری شد» باشد.
        </div>


        <!-- سفارش‌های مخاطب -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                سفارش‌های مخاطب
            </h2>

            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
                >

                    <thead>

                        <tr>

                            <th class="border p-2">
                                محصول
                            </th>

                            <th class="border p-2">
                                مبلغ
                            </th>

                            <th class="border p-2">
                                وضعیت
                            </th>

                            <th class="border p-2">
                                ثبت‌کننده
                            </th>

                            <th class="border p-2">
                                تاریخ
                            </th>

                            <th class="border p-2">
                                عملیات
                            </th>

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

                            <td class="border p-2">
                                {{
                                    order.product_name
                                }}
                            </td>

                            <td class="border p-2">

                                {{
                                    formatAmount(
                                        order.amount
                                    )
                                }}

                            </td>

                            <td class="border p-2">

                                {{
                                    orderStatusLabels[
                                        order.status
                                    ]
                                        ?? order.status
                                }}

                            </td>

                            <td class="border p-2">

                                {{
                                    order.user
                                        ?.name
                                        ?? '-'
                                }}

                            </td>

                            <td class="border p-2">
                                {{
                                    order.created_at
                                }}
                            </td>

                            <td class="border p-2">

                                <a
                                    :href="
                                        `/orders/${order.id}/edit`
                                    "
                                    class="text-blue-600"
                                >
                                    ویرایش
                                </a>

                            </td>

                        </tr>


                        <tr
                            v-if="
                                !contact.orders
                                ||
                                !contact.orders.length
                            "
                        >

                            <td
                                colspan="6"
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                سفارشی ثبت نشده است
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- ثبت پیگیری دستی -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                ثبت پیگیری جدید
            </h2>

            <form
                @submit.prevent="
                    submitFollowUp
                "
            >

                <input
                    v-model="
                        followUpForm.title
                    "
                    type="text"
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                    placeholder="عنوان پیگیری"
                >


                <textarea
                    v-model="
                        followUpForm
                            .description
                    "
                    rows="4"
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                    placeholder="توضیحات"
                ></textarea>


                <input
                    v-model="
                        followUpForm
                            .follow_up_at
                    "
                    type="datetime-local"
                    class="
                        border
                        p-2
                        w-full
                        rounded
                        mb-3
                    "
                >


                <button
                    type="submit"
                    :disabled="
                        followUpForm
                            .processing
                    "
                    class="
                        bg-purple-600
                        text-white
                        px-5
                        py-2
                        rounded
                    "
                >
                    ثبت پیگیری
                </button>

            </form>

        </div>


        <!-- پیگیری‌ها -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                پیگیری‌های مخاطب
            </h2>

            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
                >

                    <thead>

                        <tr>
                            <th class="border p-2">
                                عنوان
                            </th>

                            <th class="border p-2">
                                تاریخ
                            </th>

                            <th class="border p-2">
                                وضعیت
                            </th>

                            <th class="border p-2">
                                کاربر
                            </th>
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

                            <td class="border p-2">
                                {{ item.title }}
                            </td>

                            <td class="border p-2">
                                {{
                                    item.follow_up_at
                                }}
                            </td>

                            <td class="border p-2">
                                {{ item.status }}
                            </td>

                            <td class="border p-2">
                                {{
                                    item.user?.name
                                    ?? '-'
                                }}
                            </td>

                        </tr>


                        <tr
                            v-if="
                                !contact.follow_ups
                                ||
                                !contact
                                    .follow_ups
                                    .length
                            "
                        >

                            <td
                                colspan="4"
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                پیگیری ثبت نشده است
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>


        <!-- تاریخچه ارتباطات -->
        <div class="border rounded p-5">

            <h2 class="font-bold text-lg mb-4">
                تاریخچه ارتباطات
            </h2>

            <div class="overflow-x-auto">

                <table
                    class="
                        w-full
                        border-collapse
                        border
                    "
                >

                    <thead>

                        <tr>

                            <th class="border p-2">
                                نوع
                            </th>

                            <th class="border p-2">
                                عنوان
                            </th>

                            <th class="border p-2">
                                نتیجه
                            </th>

                            <th class="border p-2">
                                وضعیت بعد از تماس
                            </th>

                            <th class="border p-2">
                                کاربر
                            </th>

                            <th class="border p-2">
                                تاریخ
                            </th>

                            <th class="border p-2">
                                عملیات
                            </th>

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

                            <td class="border p-2">
                                {{ item.type }}
                            </td>

                            <td class="border p-2">
                                {{
                                    item.subject
                                    ?? '-'
                                }}
                            </td>

                            <td class="border p-2">

                                {{
                                    callResultLabels[
                                        item.result
                                    ]
                                        ?? item.result
                                        ?? '-'
                                }}

                            </td>

                            <td class="border p-2">

                                {{
                                    statusLabels[
                                        item
                                            .status_after_call
                                    ]
                                        ??
                                        item
                                            .status_after_call
                                        ??
                                        '-'
                                }}

                            </td>

                            <td class="border p-2">

                                {{
                                    item.user?.name
                                    ?? '-'
                                }}

                            </td>

                            <td class="border p-2">
                                {{
                                    item.created_at
                                }}
                            </td>

                            <td class="border p-2">

                                <button
                                    type="button"
                                    @click="
                                        removeInteraction(
                                            item.id
                                        )
                                    "
                                    class="text-red-600"
                                >
                                    حذف
                                </button>

                            </td>

                        </tr>


                        <tr
                            v-if="
                                !contact.interactions
                                ||
                                !contact
                                    .interactions
                                    .length
                            "
                        >

                            <td
                                colspan="7"
                                class="
                                    border
                                    p-4
                                    text-center
                                "
                            >
                                ارتباطی ثبت نشده است
                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</template>