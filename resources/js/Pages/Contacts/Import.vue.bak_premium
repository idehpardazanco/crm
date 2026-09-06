<script setup>
import {
    Link,
    useForm,
} from '@inertiajs/vue3'

const props = defineProps({
    isAdmin: Boolean,
    users: Array,
    importResult: Object,
})

const form = useForm({
    file: null,
    assigned_user_id: '',
})

const selectFile = (event) => {
    form.file =
        event.target.files[0]
        ?? null
}

const submit = () => {
    form.post(
        '/contacts/import',
        {
            forceFormData: true,
        }
    )
}
</script>

<template>
    <div
        class="p-6"
        dir="rtl"
    >

        <div
            class="
                flex
                justify-between
                items-center
                mb-6
            "
        >

            <div>

                <h1 class="text-2xl font-bold">
                    ورود مخاطبین از Excel
                </h1>

                <p class="text-gray-500 mt-2">
                    ورود گروهی مخاطبین به CRM
                </p>

            </div>


            <Link
                href="/contacts"
                class="
                    border
                    px-4
                    py-2
                    rounded
                "
            >
                بازگشت
            </Link>

        </div>


        <div
            class="
                border
                rounded
                p-5
                mb-6
            "
        >

            <div class="mb-5">

                <a
                    href="/contacts/import/template"
                    class="
                        text-blue-600
                        font-medium
                    "
                >
                    دانلود فایل نمونه Excel
                </a>

            </div>


            <form
                @submit.prevent="submit"
            >

                <div class="mb-5">

                    <label
                        class="
                            block
                            font-medium
                            mb-2
                        "
                    >
                        فایل Excel
                    </label>

                    <input
                        type="file"
                        accept="
                            .xlsx,
                            .xls,
                            .csv
                        "
                        @change="
                            selectFile
                        "
                        class="
                            border
                            rounded
                            p-2
                            w-full
                        "
                    >

                    <div
                        v-if="
                            form.errors.file
                        "
                        class="
                            text-red-600
                            mt-2
                        "
                    >
                        {{ form.errors.file }}
                    </div>

                </div>


                <div
                    v-if="isAdmin"
                    class="mb-5"
                >

                    <label
                        class="
                            block
                            font-medium
                            mb-2
                        "
                    >
                        تخصیص مخاطبین به کارمند
                    </label>

                    <select
                        v-model="
                            form.assigned_user_id
                        "
                        class="
                            border
                            rounded
                            p-2
                            w-full
                        "
                    >

                        <option value="">
                            بدون تخصیص
                        </option>

                        <option
                            v-for="
                                user in users
                            "
                            :key="user.id"
                            :value="user.id"
                        >
                            {{ user.name }}
                        </option>

                    </select>

                    <div
                        v-if="
                            form.errors
                                .assigned_user_id
                        "
                        class="
                            text-red-600
                            mt-2
                        "
                    >
                        {{
                            form.errors
                                .assigned_user_id
                        }}
                    </div>

                </div>


                <div
                    v-else
                    class="
                        bg-blue-50
                        border
                        border-blue-200
                        rounded
                        p-3
                        mb-5
                    "
                >
                    مخاطبین واردشده به‌صورت خودکار
                    به حساب شما تخصیص داده می‌شوند.
                </div>


                <button
                    type="submit"
                    :disabled="
                        form.processing
                    "
                    class="
                        bg-green-600
                        text-white
                        px-5
                        py-2
                        rounded
                        disabled:opacity-50
                    "
                >

                    {{
                        form.processing
                            ? 'در حال ورود اطلاعات...'
                            : 'شروع Import'
                    }}

                </button>

            </form>

        </div>


        <!-- ستون‌های لازم -->
        <div
            class="
                border
                rounded
                p-5
                mb-6
            "
        >

            <h2 class="font-bold mb-3">
                ستون‌های فایل
            </h2>

            <div
                class="
                    bg-gray-50
                    rounded
                    p-4
                    text-left
                    overflow-x-auto
                "
                dir="ltr"
            >
                business_name,
                name,
                mobile,
                phone,
                email,
                city,
                category,
                source,
                status,
                address,
                description
            </div>


            <div class="mt-4 text-sm">

                <p class="mb-2">
                    <strong>
                        name
                    </strong>
                    و
                    <strong>
                        mobile
                    </strong>
                    اجباری هستند.
                </p>

                <p class="mb-2">
                    وضعیت پیش‌فرض:
                    <strong>
                        new
                    </strong>
                </p>

                <p>
                    شماره‌هایی مثل
                    09121234567،
                    +989121234567
                    و
                    989121234567
                    به فرمت استاندارد تبدیل می‌شوند.
                </p>

            </div>

        </div>


        <!-- نتیجه Import -->
        <div
            v-if="importResult"
            class="
                border
                rounded
                p-5
            "
        >

            <h2 class="font-bold mb-4">
                نتیجه Import
            </h2>


            <div
                class="
                    grid
                    grid-cols-1
                    md:grid-cols-3
                    gap-4
                    mb-5
                "
            >

                <div
                    class="
                        border
                        border-green-300
                        rounded
                        p-4
                    "
                >
                    <div class="text-gray-500">
                        وارد شده
                    </div>

                    <div
                        class="
                            text-2xl
                            font-bold
                            text-green-600
                        "
                    >
                        {{ importResult.imported }}
                    </div>
                </div>


                <div
                    class="
                        border
                        border-yellow-300
                        rounded
                        p-4
                    "
                >
                    <div class="text-gray-500">
                        تکراری
                    </div>

                    <div
                        class="
                            text-2xl
                            font-bold
                            text-yellow-600
                        "
                    >
                        {{ importResult.duplicates }}
                    </div>
                </div>


                <div
                    class="
                        border
                        border-red-300
                        rounded
                        p-4
                    "
                >
                    <div class="text-gray-500">
                        خطادار
                    </div>

                    <div
                        class="
                            text-2xl
                            font-bold
                            text-red-600
                        "
                    >
                        {{ importResult.failed }}
                    </div>
                </div>

            </div>


            <div
                v-if="
                    importResult.failures
                    &&
                    importResult.failures.length
                "
            >

                <h3
                    class="
                        font-bold
                        text-red-600
                        mb-3
                    "
                >
                    ردیف‌های دارای خطا
                </h3>


                <div
                    class="
                        overflow-x-auto
                    "
                >

                    <table
                        class="
                            w-full
                            border-collapse
                            border
                        "
                    >

                        <thead>

                            <tr>

                                <th
                                    class="
                                        border
                                        p-2
                                    "
                                >
                                    ردیف Excel
                                </th>

                                <th
                                    class="
                                        border
                                        p-2
                                    "
                                >
                                    خطا
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <tr
                                v-for="
                                    failure in
                                    importResult.failures
                                "
                                :key="
                                    failure.row
                                "
                            >

                                <td
                                    class="
                                        border
                                        p-2
                                    "
                                >
                                    {{ failure.row }}
                                </td>


                                <td
                                    class="
                                        border
                                        p-2
                                    "
                                >

                                    <ul
                                        class="
                                            list-disc
                                            pr-5
                                        "
                                    >

                                        <li
                                            v-for="
                                                error in
                                                failure.errors
                                            "
                                            :key="error"
                                        >
                                            {{ error }}
                                        </li>

                                    </ul>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
</template>