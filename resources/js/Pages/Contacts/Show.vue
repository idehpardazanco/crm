<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { watch } from 'vue'

const props = defineProps({
    contact: Object,
    smsTemplates: Array,
    smsVariables: Object,
    contactStatuses: Array,
    callResults: Array,
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

        const template = props.smsTemplates.find(
            (item) =>
                String(item.id) === String(templateId)
        )

        smsForm.message = template
            ? renderTemplate(template.body)
            : ''
    }
)

watch(
    () => interactionForm.status_after_call,
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
    interactionForm.post('/interactions', {
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
    })
}

const submitFollowUp = () => {
    followUpForm.post('/followups', {
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
    })
}

const removeInteraction = (id) => {
    if (!confirm('گزارش ارتباط حذف شود؟')) {
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
    <div class="p-6">

        <!-- اطلاعات مخاطب -->
        <div class="mb-6">

            <h1 class="text-2xl font-bold mb-4">
                {{ contact.name }}
            </h1>

            <div class="border rounded p-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">

                    <p>
                        <strong>
                            موبایل:
                        </strong>

                        {{ contact.mobile }}
                    </p>

                    <p>
                        <strong>
                            تلفن:
                        </strong>

                        {{ contact.phone ?? '-' }}
                    </p>

                    <p>
                        <strong>
                            کسب و کار:
                        </strong>

                        {{ contact.business_name ?? '-' }}
                    </p>

                    <p>
                        <strong>
                            شهر:
                        </strong>

                        {{ contact.city ?? '-' }}
                    </p>

                    <p>
                        <strong>
                            دسته‌بندی:
                        </strong>

                        {{ contact.category ?? '-' }}
                    </p>

                    <p>
                        <strong>
                            منبع:
                        </strong>

                        {{ contact.source ?? '-' }}
                    </p>

                    <p>
                        <strong>
                            وضعیت:
                        </strong>

                        {{ contact.status }}
                    </p>

                    <p>
                        <strong>
                            مسئول:
                        </strong>

                        {{ contact.assigned_user?.name ?? '-' }}
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

            <form @submit.prevent="submitInteraction">

                <div class="mb-3">

                    <label class="block mb-2">
                        عنوان تماس
                    </label>

                    <input
                        v-model="interactionForm.subject"
                        type="text"
                        class="border p-2 w-full rounded"
                        placeholder="عنوان تماس"
                    >

                    <div
                        v-if="interactionForm.errors.subject"
                        class="text-red-600 mt-1"
                    >
                        {{ interactionForm.errors.subject }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        نتیجه تماس
                    </label>

                    <select
                        v-model="interactionForm.result"
                        class="border p-2 w-full rounded"
                    >
                        <option value="">
                            نتیجه تماس را انتخاب کنید
                        </option>

                        <option
                            v-for="result in callResults"
                            :key="result.value"
                            :value="result.value"
                        >
                            {{ result.label }}
                        </option>
                    </select>

                    <div
                        v-if="interactionForm.errors.result"
                        class="text-red-600 mt-1"
                    >
                        {{ interactionForm.errors.result }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        وضعیت مخاطب بعد از تماس
                    </label>

                    <select
                        v-model="interactionForm.status_after_call"
                        class="border p-2 w-full rounded"
                    >
                        <option value="">
                            وضعیت مخاطب را انتخاب کنید
                        </option>

                        <option
                            v-for="status in contactStatuses"
                            :key="status.value"
                            :value="status.value"
                        >
                            {{ status.label }}
                        </option>
                    </select>

                    <div
                        v-if="interactionForm.errors.status_after_call"
                        class="text-red-600 mt-1"
                    >
                        {{ interactionForm.errors.status_after_call }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        توضیحات تماس
                    </label>

                    <textarea
                        v-model="interactionForm.description"
                        rows="5"
                        class="border p-2 w-full rounded"
                        placeholder="توضیحات تماس"
                    ></textarea>

                    <div
                        v-if="interactionForm.errors.description"
                        class="text-red-600 mt-1"
                    >
                        {{ interactionForm.errors.description }}
                    </div>

                </div>


                <div
                    v-if="
                        interactionForm.status_after_call ===
                        'follow_up'
                    "
                    class="mb-3"
                >

                    <label class="block mb-2">
                        تاریخ و ساعت پیگیری بعدی
                    </label>

                    <input
                        v-model="interactionForm.next_follow_up"
                        type="datetime-local"
                        class="border p-2 w-full rounded"
                    >

                    <div
                        v-if="interactionForm.errors.next_follow_up"
                        class="text-red-600 mt-1"
                    >
                        {{ interactionForm.errors.next_follow_up }}
                    </div>

                </div>


                <button
                    type="submit"
                    :disabled="interactionForm.processing"
                    class="bg-green-600 text-white px-5 py-2 rounded"
                >
                    {{
                        interactionForm.processing
                            ? 'در حال ثبت...'
                            : 'ثبت تماس'
                    }}
                </button>

            </form>

        </div>


        <!-- ارسال پیامک -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                ارسال پیامک
            </h2>

            <form @submit.prevent="sendSms">

                <div class="mb-3">

                    <label class="block mb-2">
                        قالب پیامک
                    </label>

                    <select
                        v-model="smsForm.template_id"
                        class="border p-2 w-full rounded"
                    >
                        <option value="">
                            پیامک دستی
                        </option>

                        <option
                            v-for="template in smsTemplates"
                            :key="template.id"
                            :value="template.id"
                        >
                            {{ template.title }}
                        </option>
                    </select>

                    <div
                        v-if="smsForm.errors.template_id"
                        class="text-red-600 mt-1"
                    >
                        {{ smsForm.errors.template_id }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        شماره گیرنده
                    </label>

                    <input
                        v-model="smsForm.to"
                        type="text"
                        class="border p-2 w-full rounded"
                    >

                    <div
                        v-if="smsForm.errors.to"
                        class="text-red-600 mt-1"
                    >
                        {{ smsForm.errors.to }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        متن پیامک
                    </label>

                    <textarea
                        v-model="smsForm.message"
                        :readonly="Boolean(smsForm.template_id)"
                        rows="7"
                        class="border p-2 w-full rounded"
                        placeholder="متن پیامک"
                    ></textarea>

                    <div
                        v-if="smsForm.errors.message"
                        class="text-red-600 mt-1"
                    >
                        {{ smsForm.errors.message }}
                    </div>

                </div>


                <button
                    type="submit"
                    :disabled="smsForm.processing"
                    class="bg-blue-600 text-white px-5 py-2 rounded"
                >
                    {{
                        smsForm.processing
                            ? 'در حال ارسال...'
                            : 'ارسال پیامک'
                    }}
                </button>

            </form>

        </div>


        <!-- ثبت پیگیری دستی -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                ثبت پیگیری جدید
            </h2>

            <form @submit.prevent="submitFollowUp">

                <div class="mb-3">

                    <label class="block mb-2">
                        عنوان پیگیری
                    </label>

                    <input
                        v-model="followUpForm.title"
                        type="text"
                        class="border p-2 w-full rounded"
                        placeholder="عنوان پیگیری"
                    >

                    <div
                        v-if="followUpForm.errors.title"
                        class="text-red-600 mt-1"
                    >
                        {{ followUpForm.errors.title }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        توضیحات
                    </label>

                    <textarea
                        v-model="followUpForm.description"
                        rows="4"
                        class="border p-2 w-full rounded"
                        placeholder="توضیحات پیگیری"
                    ></textarea>

                    <div
                        v-if="followUpForm.errors.description"
                        class="text-red-600 mt-1"
                    >
                        {{ followUpForm.errors.description }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        تاریخ و ساعت پیگیری
                    </label>

                    <input
                        v-model="followUpForm.follow_up_at"
                        type="datetime-local"
                        class="border p-2 w-full rounded"
                    >

                    <div
                        v-if="followUpForm.errors.follow_up_at"
                        class="text-red-600 mt-1"
                    >
                        {{ followUpForm.errors.follow_up_at }}
                    </div>

                </div>


                <div class="mb-3">

                    <label class="block mb-2">
                        وضعیت
                    </label>

                    <select
                        v-model="followUpForm.status"
                        class="border p-2 w-full rounded"
                    >
                        <option value="pending">
                            در انتظار
                        </option>

                        <option value="done">
                            انجام شده
                        </option>

                        <option value="cancelled">
                            لغو شده
                        </option>
                    </select>

                    <div
                        v-if="followUpForm.errors.status"
                        class="text-red-600 mt-1"
                    >
                        {{ followUpForm.errors.status }}
                    </div>

                </div>


                <button
                    type="submit"
                    :disabled="followUpForm.processing"
                    class="bg-purple-600 text-white px-5 py-2 rounded"
                >
                    {{
                        followUpForm.processing
                            ? 'در حال ثبت...'
                            : 'ثبت پیگیری'
                    }}
                </button>

            </form>

        </div>


        <!-- لیست پیگیری‌ها -->
        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold text-lg mb-4">
                پیگیری‌های مخاطب
            </h2>

            <div class="overflow-x-auto">

                <table class="w-full border-collapse border">

                    <thead>

                        <tr>
                            <th class="border p-2">
                                عنوان
                            </th>

                            <th class="border p-2">
                                توضیحات
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
                            v-for="item in contact.follow_ups"
                            :key="item.id"
                        >

                            <td class="border p-2">
                                {{ item.title }}
                            </td>

                            <td class="border p-2">
                                {{ item.description ?? '-' }}
                            </td>

                            <td class="border p-2">
                                {{ item.follow_up_at }}
                            </td>

                            <td class="border p-2">
                                {{ item.status }}
                            </td>

                            <td class="border p-2">
                                {{ item.user?.name ?? '-' }}
                            </td>

                        </tr>


                        <tr
                            v-if="
                                !contact.follow_ups ||
                                !contact.follow_ups.length
                            "
                        >
                            <td
                                colspan="5"
                                class="border p-4 text-center"
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

                <table class="w-full border-collapse border">

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
                                توضیحات
                            </th>

                            <th class="border p-2">
                                پیگیری بعدی
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
                            v-for="item in contact.interactions"
                            :key="item.id"
                        >

                            <td class="border p-2">
                                {{ item.type }}
                            </td>

                            <td class="border p-2">
                                {{ item.subject ?? '-' }}
                            </td>

                            <td class="border p-2">
                                {{ item.result ?? '-' }}
                            </td>

                            <td class="border p-2">
                                {{
                                    item.status_after_call
                                        ?? '-'
                                }}
                            </td>

                            <td class="border p-2">
                                {{ item.description ?? '-' }}
                            </td>

                            <td class="border p-2">
                                {{
                                    item.next_follow_up
                                        ?? '-'
                                }}
                            </td>

                            <td class="border p-2">
                                {{ item.user?.name ?? '-' }}
                            </td>

                            <td class="border p-2">
                                {{ item.created_at }}
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
                                !contact.interactions ||
                                !contact.interactions.length
                            "
                        >
                            <td
                                colspan="9"
                                class="border p-4 text-center"
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