<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    roles: {
        type: Array,
        default: () => ['employee'],
    },
})

const form = useForm({
    name: '',
    mobile: '',
    email: '',
    password: '',
    status: 'active',
    role: 'employee',
})

function submit() {
    form.post('/users', {
        preserveScroll: true,
    })
}
</script>

<template>
    <div class="p-6" dir="rtl">

        <h1 class="text-xl mb-5">
            ایجاد کاربر
        </h1>

        <form @submit.prevent="submit">

            <!-- نام -->
            <div class="mb-4">
                <label class="block mb-1">
                    نام
                </label>

                <input
                    v-model="form.name"
                    type="text"
                    placeholder="نام کاربر"
                    class="border p-2 block w-full"
                    autocomplete="off"
                />

                <div
                    v-if="form.errors.name"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.name }}
                </div>
            </div>


            <!-- موبایل -->
            <div class="mb-4">
                <label class="block mb-1">
                    شماره موبایل
                </label>

                <input
                    v-model="form.mobile"
                    type="text"
                    inputmode="numeric"
                    placeholder="مثلاً 09123456789"
                    class="border p-2 block w-full"
                    autocomplete="off"
                />

                <div
                    v-if="form.errors.mobile"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.mobile }}
                </div>
            </div>


            <!-- ایمیل -->
            <div class="mb-4">
                <label class="block mb-1">
                    ایمیل
                </label>

                <input
                    v-model="form.email"
                    type="email"
                    placeholder="ایمیل - اختیاری"
                    class="border p-2 block w-full"
                    autocomplete="off"
                />

                <div
                    v-if="form.errors.email"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.email }}
                </div>
            </div>


            <!-- رمز عبور -->
            <div class="mb-4">
                <label class="block mb-1">
                    رمز عبور
                </label>

                <input
                    v-model="form.password"
                    type="password"
                    placeholder="حداقل ۸ کاراکتر"
                    class="border p-2 block w-full"
                    autocomplete="new-password"
                />

                <div
                    v-if="form.errors.password"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.password }}
                </div>
            </div>


            <!-- نقش -->
            <div class="mb-4">
                <label class="block mb-1">
                    نقش کاربر
                </label>

                <select
                    v-model="form.role"
                    class="border border-gray-300 p-2 block w-full bg-white text-gray-900 rounded"
                    style="color-scheme: light;"
                >
                    <option
                        value="employee"
                        style="color: #111827; background-color: #ffffff;"
                    >
                        کارمند
                    </option>
                </select>

                <div
                    v-if="form.errors.role"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.role }}
                </div>
            </div>


            <!-- وضعیت -->
            <div class="mb-4">
                <label class="block mb-1">
                    وضعیت کاربر
                </label>

                <select
                    v-model="form.status"
                    class="border border-gray-300 p-2 block w-full bg-white text-gray-900 rounded"
                    style="color-scheme: light;"
                >
                    <option
                        value="active"
                        style="color: #111827; background-color: #ffffff;"
                    >
                        فعال
                    </option>

                    <option
                        value="inactive"
                        style="color: #111827; background-color: #ffffff;"
                    >
                        غیرفعال
                    </option>
                </select>

                <div
                    v-if="form.errors.status"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.status }}
                </div>
            </div>


            <!-- خطای عمومی -->
            <div
                v-if="form.hasErrors"
                class="text-red-600 text-sm mb-4"
            >
                لطفاً خطاهای فرم را بررسی کنید.
            </div>


            <!-- دکمه ذخیره -->
            <button
                type="submit"
                class="bg-green-600 text-white px-5 py-2"
                :disabled="form.processing"
                :class="{
                    'opacity-50 cursor-not-allowed': form.processing
                }"
            >
                {{ form.processing ? 'در حال ذخیره...' : 'ذخیره کاربر' }}
            </button>

        </form>

    </div>
</template>