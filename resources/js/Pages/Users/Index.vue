<script setup>

import { router } from '@inertiajs/vue3'
import { ref } from 'vue'


const props = defineProps({
    users: Object,
})


const search = ref('')


// کاربری که قرار است حذف شود
const selectedUser = ref(null)


// وضعیت حذف
const deleting = ref(false)



function doSearch() {

    router.get(
        '/users',
        {
            search: search.value
        },
        {
            preserveState: true
        }
    )

}



// باز کردن پنجره تایید حذف
function askRemove(user) {

    selectedUser.value = user

}



// بستن پنجره
function cancelRemove() {

    if (deleting.value) {
        return
    }

    selectedUser.value = null

}



// حذف واقعی کاربر
function confirmRemove() {

    if (!selectedUser.value) {
        return
    }

    deleting.value = true


    router.delete(
        `/users/${selectedUser.value.id}`,
        {
            preserveScroll: true,

            onSuccess: () => {

                selectedUser.value = null

            },

            onFinish: () => {

                deleting.value = false

            }
        }
    )

}

</script>



<template>

<div
    class="p-6"
    dir="rtl"
>


    <h1 class="text-xl font-bold mb-5">
        مدیریت کاربران
    </h1>



    <!-- جستجو و کاربر جدید -->
    <div class="mb-5">

        <input
            v-model="search"
            @keyup.enter="doSearch"
            class="border p-2"
            placeholder="جستجو..."
        />


        <a
            href="/users/create"
            class="bg-blue-600 text-white px-4 py-2 rounded mr-3"
        >
            کاربر جدید
        </a>

    </div>



    <!-- جدول کاربران -->
    <table class="w-full border">


        <thead>

            <tr>

                <th class="border p-2">
                    نام
                </th>


                <th class="border p-2">
                    موبایل
                </th>


                <th class="border p-2">
                    نقش
                </th>


                <th class="border p-2">
                    وضعیت
                </th>


                <th class="border p-2">
                    عملیات
                </th>

            </tr>

        </thead>



        <tbody>


            <tr
                v-for="user in users.data"
                :key="user.id"
            >


                <td class="border p-2">

                    {{ user.name }}

                </td>


                <td class="border p-2">

                    {{ user.mobile }}

                </td>


                <td class="border p-2">

                    {{ user.roles?.[0]?.name }}

                </td>


                <td class="border p-2">

                    {{ user.status }}

                </td>


                <td class="border p-2">


                    <a
                        :href="`/users/${user.id}/edit`"
                        class="text-blue-600"
                    >
                        ویرایش
                    </a>


                    <button
                        type="button"
                        @click="askRemove(user)"
                        class="text-red-600 mr-3"
                    >
                        حذف
                    </button>


                </td>


            </tr>


        </tbody>


    </table>




    <!-- پنجره تایید حذف -->
    <div
        v-if="selectedUser"
        class="fixed inset-0 z-50 flex items-center justify-center"
    >


        <!-- پس زمینه تیره -->
        <div
            class="absolute inset-0 bg-black/40"
            @click="cancelRemove"
        ></div>



        <!-- پنجره -->
        <div
            class="relative bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4"
        >


            <h2 class="text-lg font-bold mb-4">

                حذف کاربر

            </h2>



            <p class="mb-2">

                آیا از حذف کاربر

                <strong>
                    «{{ selectedUser.name }}»
                </strong>

                مطمئن هستید؟

            </p>



            <p class="text-sm text-gray-600 mb-6">

                شماره موبایل:
                {{ selectedUser.mobile }}

            </p>



            <div class="flex gap-3">


                <button
                    type="button"
                    @click="confirmRemove"
                    :disabled="deleting"
                    class="bg-red-600 text-white px-4 py-2 rounded"
                >

                    {{ deleting ? 'در حال حذف...' : 'بله، حذف شود' }}

                </button>



                <button
                    type="button"
                    @click="cancelRemove"
                    :disabled="deleting"
                    class="border px-4 py-2 rounded"
                >

                    انصراف

                </button>


            </div>


        </div>


    </div>


</div>

</template>