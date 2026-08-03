<script setup>
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
    contact: Object
})

const smsForm = useForm({
    contact_id: props.contact.id,
    to: props.contact.mobile,
    message: ''
})

const interactionForm = useForm({
    contact_id: props.contact.id,
    type: 'call',
    subject: '',
    description: '',
    result: '',
    next_follow_up: ''
})

const followUpForm = useForm({
    contact_id: props.contact.id,
    title: '',
    description: '',
    follow_up_at: '',
    status: 'pending'
})

const sendSms = () => {
    smsForm.post('/sms/send', {
        onSuccess: () => {
            smsForm.reset('message')
            router.reload()
        }
    })
}

const submitInteraction = () => {
    interactionForm.post('/interactions', {
        onSuccess: () => {
            interactionForm.reset()
            router.reload()
        }
    })
}

const submitFollowUp = () => {
    followUpForm.post('/followups', {
        onSuccess: () => {
            followUpForm.reset()
            router.reload()
        }
    })
}

const removeInteraction = (id) => {
    if (confirm('حذف شود؟')) {
        router.delete(`/interactions/${id}`)
    }
}
</script>

<template>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-6">
            {{ contact.name }}
        </h1>

        <div class="border rounded p-5 mb-6">
            <p>
                موبایل: {{ contact.mobile }}
            </p>

            <p>
                کسب و کار: {{ contact.business_name }}
            </p>

            <p>
                وضعیت: {{ contact.status }}
            </p>

            <p>
                مسئول: {{ contact.assigned_user?.name ?? '-' }}
            </p>
        </div>

        <div class="border rounded p-5 mb-6">
            <h2 class="font-bold mb-4">
                ارسال پیامک
            </h2>

            <form @submit.prevent="sendSms">
                <textarea
                    v-model="smsForm.message"
                    class="border p-2 w-full mb-3"
                    placeholder="متن پیامک"
                ></textarea>

                <button
                    type="submit"
                    class="bg-blue-600 text-white px-5 py-2 rounded"
                >
                    ارسال
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

            <table class="w-full border-collapse border">

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
                </tbody>

            </table>
        </div>

        <div class="border rounded p-5">
            <h2 class="font-bold mb-4">
                تاریخچه ارتباطات
            </h2>

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
                            {{ item.subject }}
                        </td>

                        <td class="border p-2">
                            {{ item.user?.name ?? '-' }}
                        </td>

                        <td class="border p-2">
                            {{ item.created_at }}
                        </td>

                        <td class="border p-2">
                            <button
                                @click="removeInteraction(item.id)"
                                class="text-red-600"
                            >
                                حذف
                            </button>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

    </div>
</template>