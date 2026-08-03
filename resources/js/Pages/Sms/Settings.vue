<script setup>

import { useForm } from '@inertiajs/vue3'


const props = defineProps({

    settings: Object,

})


const form = useForm({

    sms_from: props.settings.sms_from ?? '',

    sms_username: props.settings.sms_username ?? '',

    sms_password: props.settings.sms_password ?? '',

})


const submit = () => {

    form.post('/sms/settings')

}

</script>


<template>

    <div class="p-6">

        <h1 class="text-xl font-bold mb-5">
            تنظیمات پیامک
        </h1>


        <form @submit.prevent="submit">


            <div class="mb-4">

                <label class="block mb-2">
                    شماره فرستنده
                </label>


                <input
                    v-model="form.sms_from"
                    type="text"
                    class="border p-2 w-full"
                />


            </div>



            <div class="mb-4">

                <label class="block mb-2">
                    نام کاربری
                </label>


                <input
                    v-model="form.sms_username"
                    type="text"
                    class="border p-2 w-full"
                />


            </div>



            <div class="mb-4">

                <label class="block mb-2">
                    رمز عبور
                </label>


                <input
                    v-model="form.sms_password"
                    type="password"
                    class="border p-2 w-full"
                />


            </div>



            <button
                type="submit"
                class="bg-blue-600 text-white px-5 py-2 rounded"
            >

                ذخیره

            </button>


        </form>


    </div>

</template>