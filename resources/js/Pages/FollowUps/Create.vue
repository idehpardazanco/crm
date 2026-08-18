<script setup>

import {
    Link,
    useForm,
} from '@inertiajs/vue3'

import PersianDateTimePicker
    from '../../Components/PersianDateTimePicker.vue'


const props = defineProps({

    contacts: Array,

})


const form = useForm({

    contact_id: '',

    title: '',

    description: '',

    follow_up_at: '',

    status: 'pending',

})


const submit = () => {

    form.post('/followups')

}

</script>


<template>

    <div
        dir="rtl"
        class="w-full p-6 text-right"
    >


        <!--
            ml-auto باعث می‌شود فرم
            در سمت راست صفحه قرار بگیرد
        -->
        <div
            class="ml-auto w-full max-w-2xl"
        >


            <h1
                class="mb-6 text-right text-xl font-bold"
            >
                ایجاد پیگیری جدید
            </h1>



            <form
                @submit.prevent="submit"
                class="w-full"
            >


                <!-- مخاطب -->
                <div class="mb-5">

                    <label
                        class="mb-2 block text-right font-medium"
                    >
                        مخاطب
                    </label>


                    <select
                        v-model="form.contact_id"

                        class="w-full rounded border p-2 text-right"

                        dir="rtl"
                    >

                        <option value="">
                            انتخاب مخاطب
                        </option>


                        <option
                            v-for="contact in props.contacts"

                            :key="contact.id"

                            :value="contact.id"
                        >

                            {{ contact.name }}

                            <template
                                v-if="contact.business_name"
                            >
                                -
                                {{ contact.business_name }}
                            </template>

                            -
                            {{ contact.mobile }}

                        </option>

                    </select>


                    <div
                        v-if="form.errors.contact_id"

                        class="mt-1 text-right text-sm text-red-600"
                    >
                        {{ form.errors.contact_id }}
                    </div>

                </div>



                <!-- عنوان -->
                <div class="mb-5">

                    <label
                        class="mb-2 block text-right font-medium"
                    >
                        عنوان
                    </label>


                    <input
                        v-model="form.title"

                        type="text"

                        class="w-full rounded border p-2 text-right"

                        dir="rtl"
                    >


                    <div
                        v-if="form.errors.title"

                        class="mt-1 text-right text-sm text-red-600"
                    >
                        {{ form.errors.title }}
                    </div>

                </div>



                <!-- توضیحات -->
                <div class="mb-5">

                    <label
                        class="mb-2 block text-right font-medium"
                    >
                        توضیحات
                    </label>


                    <textarea
                        v-model="form.description"

                        rows="5"

                        class="w-full rounded border p-2 text-right"

                        dir="rtl"
                    ></textarea>


                    <div
                        v-if="form.errors.description"

                        class="mt-1 text-right text-sm text-red-600"
                    >
                        {{ form.errors.description }}
                    </div>

                </div>



                <!-- زمان پیگیری -->
                <div class="mb-6">

                    <label
                        class="mb-3 block text-right font-medium"
                    >
                        زمان پیگیری
                    </label>


                    <PersianDateTimePicker
                        v-model="form.follow_up_at"

                        placeholder="تاریخ پیگیری را انتخاب کنید"
                    />


                    <div
                        v-if="form.errors.follow_up_at"

                        class="mt-2 text-right text-sm text-red-600"
                    >
                        {{ form.errors.follow_up_at }}
                    </div>

                </div>



                <!-- وضعیت ثابت -->
                <input
                    v-model="form.status"

                    type="hidden"
                >



                <!-- دکمه‌ها -->
                <div
                    class="flex flex-row justify-start gap-3"
                >

                    <button
                        type="submit"

                        :disabled="form.processing"

                        class="rounded bg-green-600 px-5 py-2 text-white"

                        :class="{
                            'cursor-not-allowed opacity-50':
                                form.processing
                        }"
                    >

                        {{
                            form.processing
                                ? 'در حال ذخیره...'
                                : 'ذخیره'
                        }}

                    </button>


                    <Link
                        href="/followups"

                        class="rounded border px-5 py-2"
                    >
                        بازگشت
                    </Link>

                </div>


            </form>


        </div>

    </div>

</template>