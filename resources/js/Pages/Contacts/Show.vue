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
            item =>
                String(item.id) ===
                String(templateId)
        )

        smsForm.message = template
            ? renderTemplate(template.body)
            : ''
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

            interactionForm.type = 'call'

            router.reload({
                only: ['contact'],
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

            followUpForm.status = 'pending'

            router.reload({
                only: ['contact'],
            })
        },
    })
}

const removeInteraction = (id) => {
    if (!confirm('حذف شود؟')) {
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

        <h1 class="text-2xl font-bold mb-6">
            {{ contact.name }}
        </h1>

        <div class="border rounded p-5 mb-6">

            <p>
                موبایل:
                {{ contact.mobile }}
            </p>

            <p>
                کسب و کار:
                {{ contact.business_name ?? '-' }}
            </p>

            <p>
                وضعیت:
                {{ contact.status }}
            </p>

            <p>
                مسئول:
                {{ contact.assigned_user?.name ?? '-' }}
            </p>

        </div>


        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold mb-4">
                ارسال پیامک
            </h2>

            <form @submit.prevent="sendSms">

                <select
                    v-model="smsForm.template_id"
                    class="border p-2 w-full mb-3"
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
                    class="text-red-600 mb-3"
                >
                    {{ smsForm.errors.template_id }}
                </div>

                <textarea
                    v-model="smsForm.message"
                    :readonly="Boolean(
                        smsForm.template_id
                    )"
                    rows="7"
                    class="border p-2 w-full mb-3"
                    placeholder="متن پیامک"
                ></textarea>

                <div
                    v-if="smsForm.errors.message"
                    class="text-red-600 mb-3"
                >
                    {{ smsForm.errors.message }}
                </div>

                <div
                    v-if="smsForm.errors.to"
                    class="text-red-600 mb-3"
                >
                    {{ smsForm.errors.to }}
                </div>

                <button
                    type="submit"
                    :disabled="smsForm.processing"
                    class="bg-blue-600 text-white px-5 py-2 rounded"
                >
                    ارسال پیامک
                </button>

            </form>

        </div>


        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold mb-4">
                ثبت پیگیری جدید
            </h2>

            <form @submit.prevent="submitFollowUp">

                <input
                    v-model="followUpForm.title"
                    class="border p-2 w-full mb-3"
                    placeholder="عنوان پیگیری"
                >

                <textarea
                    v-model="followUpForm.description"
                    class="border p-2 w-full mb-3"
                    placeholder="توضیحات"
                ></textarea>

                <input
                    v-model="followUpForm.follow_up_at"
                    type="datetime-local"
                    class="border p-2 w-full mb-3"
                >

                <select
                    v-model="followUpForm.status"
                    class="border p-2 w-full mb-3"
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

                <button
                    type="submit"
                    :disabled="followUpForm.processing"
                    class="bg-purple-600 text-white px-5 py-2 rounded"
                >
                    ثبت پیگیری
                </button>

            </form>

        </div>


        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold mb-4">
                پیگیری‌های مشتری
            </h2>

            <table
                class="w-full border-collapse border"
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
                        v-for="item in contact.follow_ups"
                        :key="item.id"
                    >
                        <td class="border p-2">
                            {{ item.title }}
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
                            colspan="4"
                            class="border p-4 text-center"
                        >
                            پیگیری ثبت نشده است
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>


        <div class="border rounded p-5 mb-6">

            <h2 class="font-bold mb-4">
                ثبت تماس
            </h2>

            <form @submit.prevent="submitInteraction">

                <input
                    v-model="interactionForm.subject"
                    class="border p-2 w-full mb-3"
                    placeholder="عنوان تماس"
                >

                <textarea
                    v-model="interactionForm.description"
                    class="border p-2 w-full mb-3"
                    placeholder="توضیحات تماس"
                ></textarea>

                <input
                    v-model="interactionForm.result"
                    class="border p-2 w-full mb-3"
                    placeholder="نتیجه تماس"
                >

                <input
                    v-model="interactionForm.next_follow_up"
                    type="datetime-local"
                    class="border p-2 w-full mb-3"
                >

                <button
                    type="submit"
                    :disabled="interactionForm.processing"
                    class="bg-green-600 text-white px-5 py-2 rounded"
                >
                    ثبت تماس
                </button>

            </form>

        </div>


        <div class="border rounded p-5">

            <h2 class="font-bold mb-4">
                تاریخچه ارتباطات
            </h2>

            <table
                class="w-full border-collapse border"
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
                            colspan="6"
                            class="border p-4 text-center"
                        >
                            ارتباطی ثبت نشده است
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>
</template>