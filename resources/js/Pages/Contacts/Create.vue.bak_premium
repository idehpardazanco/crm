<script setup>
import {
    Link,
    useForm,
} from '@inertiajs/vue3'

const props = defineProps({
    users: Array,
    contactStatuses: Array,
    isAdmin: Boolean,
})

const form = useForm({
    business_name: '',
    name: '',
    mobile: '',
    phone: '',
    email: '',
    city: '',
    category: '',
    source: '',
    status: 'new',
    assigned_user_id: '',
    address: '',
    description: '',
})

const submit = () => {
    form.post('/contacts')
}
</script>

<template>
    <div class="p-6">

        <h1 class="text-xl font-bold mb-6">
            ایجاد مخاطب
        </h1>


        <form
            @submit.prevent="submit"
            class="max-w-2xl"
        >

            <div class="mb-4">

                <label class="block mb-2">
                    نام کسب‌وکار
                </label>

                <input
                    v-model="form.business_name"
                    type="text"
                    class="border p-2 rounded w-full"
                >

                <div
                    v-if="form.errors.business_name"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.business_name }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    نام مخاطب
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    class="border p-2 rounded w-full"
                >

                <div
                    v-if="form.errors.name"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.name }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    موبایل
                </label>

                <input
                    v-model="form.mobile"
                    type="text"
                    class="border p-2 rounded w-full"
                >

                <div
                    v-if="form.errors.mobile"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.mobile }}
                </div>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    تلفن ثابت
                </label>

                <input
                    v-model="form.phone"
                    type="text"
                    class="border p-2 rounded w-full"
                >

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    ایمیل
                </label>

                <input
                    v-model="form.email"
                    type="email"
                    class="border p-2 rounded w-full"
                >

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    شهر
                </label>

                <input
                    v-model="form.city"
                    type="text"
                    class="border p-2 rounded w-full"
                >

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    دسته‌بندی کسب‌وکار
                </label>

                <input
                    v-model="form.category"
                    type="text"
                    class="border p-2 rounded w-full"
                >

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    منبع شماره
                </label>

                <input
                    v-model="form.source"
                    type="text"
                    class="border p-2 rounded w-full"
                >

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    وضعیت
                </label>

                <select
                    v-model="form.status"
                    class="border p-2 rounded w-full"
                >

                    <option
                        v-for="status in contactStatuses"
                        :key="status.value"
                        :value="status.value"
                    >
                        {{ status.label }}
                    </option>

                </select>

                <div
                    v-if="form.errors.status"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.status }}
                </div>

            </div>


            <div
                v-if="isAdmin"
                class="mb-4"
            >

                <label class="block mb-2">
                    مسئول مخاطب
                </label>

                <select
                    v-model="form.assigned_user_id"
                    class="border p-2 rounded w-full"
                >

                    <option value="">
                        بدون مسئول
                    </option>

                    <option
                        v-for="user in users"
                        :key="user.id"
                        :value="user.id"
                    >
                        {{ user.name }}
                    </option>

                </select>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    آدرس
                </label>

                <textarea
                    v-model="form.address"
                    rows="3"
                    class="border p-2 rounded w-full"
                ></textarea>

            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    توضیحات
                </label>

                <textarea
                    v-model="form.description"
                    rows="4"
                    class="border p-2 rounded w-full"
                ></textarea>

            </div>


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
                    href="/contacts"
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