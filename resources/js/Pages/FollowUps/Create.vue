<script setup>
import {
    Link,
    useForm,
} from '@inertiajs/vue3'
import PersianDateTimePicker from '../../Components/PersianDateTimePicker.vue'

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
    <div class="p-6">

        <h1 class="text-xl font-bold mb-6">
            ایجاد پیگیری جدید
        </h1>


        <form
            @submit.prevent="submit"
            class="max-w-xl"
        >

            <div class="mb-4">

                <label class="block mb-2">
                    مخاطب
                </label>

                <select
                    v-model="form.contact_id"
                    class="
                        border
                        p-2
                        rounded
                        w-full
                    "
                >
                    <option value="">
                        انتخاب مخاطب
                    </option>

                    <option
                        v-for="contact in contacts"
                        :key="contact.id"
                        :value="contact.id"
                    >
                        {{ contact.name }}

                        <template
                            v-if="
                                contact.business_name
                            "
                        >
                            -
                            {{ contact.business_name }}
                        </template>

                        -
                        {{ contact.mobile }}
                    </option>

                </select>

                <div
                    v-if="
                        form.errors.contact_id
                    "
                    class="
                        text-red-600
                        mt-1
                    "
                >
                    {{ form.errors.contact_id }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    عنوان
                </label>

                <input
                    v-model="form.title"
                    type="text"
                    class="
                        border
                        p-2
                        rounded
                        w-full
                    "
                >

                <div
                    v-if="form.errors.title"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.title }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    توضیحات
                </label>

                <textarea
                    v-model="form.description"
                    rows="4"
                    class="
                        border
                        p-2
                        rounded
                        w-full
                    "
                ></textarea>

                <div
                    v-if="
                        form.errors.description
                    "
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.description }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    زمان پیگیری
                </label>

                <PersianDateTimePicker
                    v-model="form.follow_up_at"
                    placeholder="تاریخ و ساعت پیگیری را انتخاب کنید"
                />

                <div class="text-sm text-gray-500 mt-1">
                    تاریخ برای شما شمسی نمایش داده می‌شود و در سیستم به‌صورت استاندارد ذخیره می‌شود.
                </div>

                <div
                    v-if="
                        form.errors.follow_up_at
                    "
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.follow_up_at }}
                </div>

            </div>


            <input
                v-model="form.status"
                type="hidden"
            >


            <div class="flex gap-3">

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="
                        bg-green-600
                        text-white
                        px-5
                        py-2
                        rounded
                    "
                >
                    ذخیره
                </button>

                <Link
                    href="/followups"
                    class="
                        border
                        px-5
                        py-2
                        rounded
                    "
                >
                    بازگشت
                </Link>

            </div>

        </form>

    </div>
</template>