<script setup>

import { useForm, Link } from '@inertiajs/vue3'


const props = defineProps({
    user: Object,
})


const form = useForm({

    name: props.user.name ?? '',

    mobile: props.user.mobile ?? '',

    email: props.user.email ?? '',

    password: '',

    // نقش کاربران قابل تغییر نیست
    role: 'employee',

    status: props.user.status ?? 'active',

})


function submit() {

    form.put(`/users/${props.user.id}`, {
        preserveScroll: true,
    })

}

</script>


<template>

    <div
        class="p-6"
        dir="rtl"
    >

        <h1 class="text-xl font-bold mb-6">
            ویرایش کاربر
        </h1>


        <form
            @submit.prevent="submit"
            class="max-w-md"
        >


            <!-- نام کاربر -->
            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    نام کاربر
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


            <!-- شماره موبایل -->
            <div class="mb-4">

                <label class="block mb-2 font-medium">
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

                <label class="block mb-2 font-medium">
                    ایمیل
                    <span class="text-gray-500 text-sm">
                        (اختیاری)
                    </span>
                </label>

                <input
                    v-model="form.email"
                    type="email"
                    placeholder="مثلاً user@example.com"
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

                <label class="block mb-2 font-medium">
                    رمز عبور جدید
                </label>

                <input
                    v-model="form.password"
                    type="password"
                    placeholder="اگر نمی‌خواهید تغییر کند، خالی بگذارید"
                    class="border p-2 block w-full"
                    autocomplete="new-password"
                />

                <div class="text-gray-500 text-sm mt-1">
                    فقط در صورتی این قسمت را پر کنید که می‌خواهید رمز عبور کاربر تغییر کند.
                </div>

                <div
                    v-if="form.errors.password"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.password }}
                </div>

            </div>


            <!-- نقش -->
            <div class="mb-5">

                <label class="block mb-2 font-medium">
                    نقش کاربر
                </label>

                <div class="border p-2 bg-gray-50">
                    کارمند
                </div>

                <div
                    v-if="form.errors.role"
                    class="text-red-600 text-sm mt-1"
                >
                    {{ form.errors.role }}
                </div>

            </div>


            <!-- وضعیت -->
            <div class="mb-6">

                <label class="block mb-3 font-medium">
                    وضعیت کاربر
                </label>


                <label class="inline-flex items-center ml-5 cursor-pointer">

                    <input
                        v-model="form.status"
                        type="radio"
                        value="active"
                        class="ml-2"
                    />

                    <span>
                        فعال
                    </span>

                </label>


                <label class="inline-flex items-center cursor-pointer">

                    <input
                        v-model="form.status"
                        type="radio"
                        value="inactive"
                        class="ml-2"
                    />

                    <span>
                        غیرفعال
                    </span>

                </label>


                <div
                    v-if="form.errors.status"
                    class="text-red-600 text-sm mt-2"
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


            <!-- دکمه‌ها -->
            <div class="flex gap-3">

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-blue-600 text-white px-5 py-2"
                    :class="{
                        'opacity-50 cursor-not-allowed': form.processing
                    }"
                >
                    {{ form.processing ? 'در حال ذخیره...' : 'ذخیره تغییرات' }}
                </button>


                <Link
                    href="/users"
                    class="border px-5 py-2"
                >
                    انصراف
                </Link>

            </div>


        </form>

    </div>

</template>