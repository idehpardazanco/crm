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

const removeInteraction = (id) => {
    if (confirm('حذف شود؟')) {
        router.delete(`/interactions/${id}`, {
            onSuccess: () => {
                router.reload()
            }
        })
    }
}
</script>


<template>
    <div class="p-6">

        <h1 class="text-2xl font-bold mb-5">
            {{ contact.name }}
        </h1>


        <div class="border p-5 mb-6 rounded">

            <p>
                موبایل:
                {{ contact.mobile }}
            </p>

            <p>
                کسب و کار:
                {{ contact.business_name }}
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


        <div class="border p-5 mb-6 rounded">

            <h2 class="text-lg font-bold mb-4">
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
                    ارسال پیامک
                </button>

            </form>

        </div>


        <div class="border p-5 mb-6 rounded">

            <h2 class="text-lg font-bold mb-4">
                ثبت ارتباط جدید
            </h2>


            <form @submit.prevent="submitInteraction">

                <select
                    v-model="interactionForm.type"
                    class="border p-2 w-full mb-3"
                >
                    <option value="call">
                        تماس
                    </option>

                    <option value="sms">
                        پیامک
                    </option>

                    <option value="email">
                        ایمیل
                    </option>

                    <option value="meeting">
                        جلسه
                    </option>

                    <option value="note">
                        یادداشت
                    </option>

                </select>


                <input
                    v-model="interactionForm.subject"
                    class="border p-2 w-full mb-3"
                    placeholder="عنوان"
                />


                <textarea
                    v-model="interactionForm.description"
                    class="border p-2 w-full mb-3"
                    placeholder="توضیحات"
                ></textarea>


                <input
                    v-model="interactionForm.result"
                    class="border p-2 w-full mb-3"
                    placeholder="نتیجه"
                />


                <input
                    v-model="interactionForm.next_follow_up"
                    type="datetime-local"
                    class="border p-2 w-full mb-3"
                />


                <button
                    type="submit"
                    class="bg-green-600 text-white px-5 py-2 rounded"
                >
                    ثبت ارتباط
                </button>

            </form>

        </div>


        <div class="border p-5 rounded">

            <h2 class="text-lg font-bold mb-4">
                تاریخچه ارتباطات
            </h2>


            <table class="w-full border">

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