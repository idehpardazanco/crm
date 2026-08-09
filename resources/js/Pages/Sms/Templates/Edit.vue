<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    template: Object,
})

const form = useForm({
    title: props.template.title ?? '',
    body: props.template.body ?? '',
    type: props.template.type ?? '',
    status: props.template.status ?? 'active',
})

const submit = () => {
    form.put(
        `/sms/templates/${props.template.id}`
    )
}
</script>

<template>
    <div class="p-6">

        <h1 class="text-xl font-bold mb-6">
            ویرایش قالب پیامک
        </h1>

        <form
            @submit.prevent="submit"
            class="max-w-2xl"
        >

            <div class="mb-4">

                <label class="block mb-2">
                    عنوان
                </label>

                <input
                    v-model="form.title"
                    type="text"
                    class="border p-2 w-full"
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
                    متن پیامک
                </label>

                <textarea
                    v-model="form.body"
                    rows="7"
                    class="border p-2 w-full"
                ></textarea>

                <div
                    v-if="form.errors.body"
                    class="text-red-600 mt-1"
                >
                    {{ form.errors.body }}
                </div>

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    نوع
                </label>

                <input
                    v-model="form.type"
                    type="text"
                    class="border p-2 w-full"
                >

            </div>

            <div class="mb-4">

                <label class="block mb-2">
                    وضعیت
                </label>

                <select
                    v-model="form.status"
                    class="border p-2 w-full"
                >
                    <option value="active">
                        فعال
                    </option>

                    <option value="inactive">
                        غیرفعال
                    </option>
                </select>

            </div>

            <div class="flex gap-3">

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-blue-600 text-white px-5 py-2 rounded"
                >
                    ذخیره تغییرات
                </button>

                <Link
                    href="/sms/templates"
                    class="border px-5 py-2 rounded"
                >
                    بازگشت
                </Link>

            </div>

        </form>

    </div>
</template>