<script setup>
import {
    Link,
    useForm,
} from '@inertiajs/vue3'

const props = defineProps({
    contact: Object,
    users: Array,
    contactStatuses: Array,
    isAdmin: Boolean,
})

const form = useForm({
    business_name:
        props.contact.business_name ?? '',

    name:
        props.contact.name ?? '',

    mobile:
        props.contact.mobile ?? '',

    phone:
        props.contact.phone ?? '',

    email:
        props.contact.email ?? '',

    city:
        props.contact.city ?? '',

    category:
        props.contact.category ?? '',

    source:
        props.contact.source ?? '',

    status:
        props.contact.status ?? 'new',

    assigned_user_id:
        props.contact.assigned_user_id ?? '',

    address:
        props.contact.address ?? '',

    description:
        props.contact.description ?? '',
})

const submit = () => {
    form.put(
        `/contacts/${props.contact.id}`
    )
}
</script>

<template>
    <div class="p-6">

        <h1 class="text-xl font-bold mb-6">
            ویرایش مخاطب
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
                    class="border p-2 rounded w-full"
                >
            </div>


            <div class="mb-4">

                <label class="block mb-2">
                    نام مخاطب
                </label>

                <input
                    v-model="form.name"
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
                    class="border p-2 rounded w-full"
                >
            </div>


            <div class="mb-4">
                <label class="block mb-2">
                    دسته‌بندی
                </label>

                <input
                    v-model="form.category"
                    class="border p-2 rounded w-full"
                >
            </div>


            <div class="mb-4">
                <label class="block mb-2">
                    منبع شماره
                </label>

                <input
                    v-model="form.source"
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
                        bg-blue-600
                        text-white
                        px-5
                        py-2
                        rounded
                    "
                >
                    ذخیره تغییرات
                </button>

                <Link
                    :href="
                        `/contacts/${contact.id}`
                    "
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