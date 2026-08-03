<script setup>

import {useForm, router} from '@inertiajs/vue3'

const props = defineProps({

    contact:Object

})

const form = useForm({

    contact_id: props.contact.id,

    type:'call',

    subject:'',

    description:'',

    result:'',

    next_follow_up:''

})

function submit(){

    form.post('/interactions')

}

function remove(id){

    if(confirm('حذف شود؟')){

        router.delete(`/interactions/${id}`)

    }

}

</script>

<template>

    <div class="p-6">
        <h1 class="text-2xl font-bold mb-5">
    {{contact.name}}
        </h1>
        <div class="border p-5 mb-6">

            <p>
            موبایل:
            {{contact.mobile}}
            </p>


            <p>
            کسب و کار:
            {{contact.business_name}}
            </p>


            <p>
            وضعیت:
            {{contact.status}}
            </p>


            <p>
            مسئول:
            {{contact.assigned_user?.name ?? '-'}}
            </p>

        </div>

        <h2 class="text-xl mb-3">
        ثبت ارتباط جدید
        </h2>

        <form
        @submit.prevent="submit"
        class="mb-8"
        >


            <select
            v-model="form.type"
            class="border p-2 block mb-3"
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
            v-model="form.subject"
            placeholder="عنوان"
            class="border p-2 block mb-3"
            />



            <textarea
            v-model="form.description"
            placeholder="توضیحات"
            class="border p-2 block mb-3"
            />



            <input
            v-model="form.result"
            placeholder="نتیجه"
            class="border p-2 block mb-3"
            />



            <input
            v-model="form.next_follow_up"
            type="datetime-local"
            class="border p-2 block mb-3"
            />



            <button
            class="bg-green-600 text-white px-5 py-2"
            >
            ثبت
            </button>


        </form>

        <h2 class="text-xl mb-3">
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


                <th>
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
                {{item.type}}
                </td>


                <td class="border p-2">
                {{item.subject}}
                </td>


                <td class="border p-2">
                {{item.user?.name ?? '-'}}
                </td>


                <td class="border p-2">
                {{item.created_at}}
                </td>


                <td>

                <button
                @click="remove(item.id)"
                class="text-red-600"
                >
                حذف
                </button>


                </td>


                </tr>


                </tbody>


        </table>

    </div>

</template>