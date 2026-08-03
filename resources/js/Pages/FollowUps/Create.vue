<script setup>
import { useForm } from '@inertiajs/vue3'

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
                    مشتری
                </label>

                <select
                    v-model="form.contact_id"
                    class="border p-2 rounded w-full"
                >
                    <option value="">
                        انتخاب مشتری
                    </option>

                    <option
                        v-for="contact in props.contacts"
                        :key="contact.id"
                        :value="contact.id"
                    >
                        {{ contact.name }} - {{ contact.mobile }}
                    </option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2">
                    عنوان
                </label>

                <input
                    v-model="form.title"
                    type="text"
                    class="border p-2 rounded w-full"
                >
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

            <div class="mb-4">
                <label class="block mb-2">
                    زمان پیگیری
                </label>

                <input
                    v-model="form.follow_up_at"
                    type="datetime-local"
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
            </div>

            <button
                type="submit"
                class="bg-green-600 text-white px-5 py-2 rounded"
            >
                ذخیره
            </button>
        </form>
    </div>
</template>