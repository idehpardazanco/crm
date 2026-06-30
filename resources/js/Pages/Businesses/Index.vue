<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, watch } from 'vue'

/* =====================
   PROPS
===================== */
// const props = defineProps({
//     businesses: Object,
//     filters: Object
// })

/* =====================
   SEARCH
===================== */
const props = defineProps({
    businesses: Object,
    filters: Object
})
const search = ref(props.filters?.search || '')
const status = ref(props.filters?.status || '')

watch([search, status], ([searchVal, statusVal]) => {
    router.get(
        route('businesses.index'),
        {
            search: searchVal,
            status: statusVal
        },
        {
            preserveState: true,
            replace: true
        }
    )
})

/* =====================
   MODAL STATE
===================== */
const showModal = ref(false)
const isEdit = ref(false)

/* =====================
   LOADING GUARD
===================== */
const isLoading = ref(false)

/* =====================
   TOAST
===================== */
const toast = ref(null)

const showToast = (message) => {
    toast.value = message
    setTimeout(() => {
        toast.value = null
    }, 3000)
}

/* =====================
   FORM
===================== */
const form = useForm({
    id: null,
    business_name: '',
    contact_name: '',
    mobile: '',
    phone: '',
    city: '',
    category: '',
    source: '',
    status: 'new',
    description: ''
})

/* =====================
   CREATE
===================== */
const openCreate = () => {
    isEdit.value = false
    form.reset()
    showModal.value = true
}

/* =====================
   EDIT
===================== */
const openEdit = (business) => {
    isEdit.value = true

    form.id = business.id
    form.business_name = business.business_name
    form.contact_name = business.contact_name
    form.mobile = business.mobile
    form.phone = business.phone
    form.city = business.city
    form.category = business.category
    form.source = business.source
    form.status = business.status
    form.description = business.description

    showModal.value = true
}

/* =====================
   SUBMIT (FIXED - NO DUPLICATE)
===================== */
const submit = () => {
    if (isLoading.value) return
    isLoading.value = true

    if (isEdit.value) {
        form.put(route('businesses.update', form.id), {
            onSuccess: () => {
                showModal.value = false
                showToast('ویرایش با موفقیت انجام شد')
            },
            onFinish: () => {
                isLoading.value = false
            }
        })
    } else {
        form.post(route('businesses.store'), {
            onSuccess: () => {
                showModal.value = false
                showToast('مخاطب با موفقیت ذخیره شد')
            },
            onFinish: () => {
                isLoading.value = false
            }
        })
    }
}

/* =====================
   DELETE (FIXED)
===================== */

const confirmDelete = (id) => {

    // router.post(`/businesses/${id}`, {
    //     onStart: () => console.log('START'),
    //     onSuccess: () => console.log('SUCCESS'),
    //     onError: (err) => console.log('ERROR', err),
    //     onFinish: () => console.log('FINISH'),
    // })

    if (!id) return

    if (confirm('آیا مطمئنی؟')) {
        router.post(`/businesses/${id}`, {
            preserveScroll: true,
            onSuccess: () => showToast('حذف شد'),
        })
    }
}
/* =====================
   PAGINATION
===================== */
const goToPage = (page) => {
    router.get(route('businesses.index'), {
        page,
        search: search.value,
        status: status.value
    }, {
        preserveState: true,
        replace: true
    })
}
/* =====================
   CHANGE STATUS
===================== */
const changeStatus = (id, status) => {
console.log(route('businesses.update', 1))

    router.post(route('businesses.update', id), {
        status: status
    }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            showToast('وضعیت تغییر کرد')
        }
    })
}
</script>

<template>
    
    <Head title="مخاطبین" />

    <AuthenticatedLayout>
        <div class="p-6">

            <!-- HEADER -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-xl font-bold">مخاطبین</h1>

                <button
                    @click="openCreate"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg"
                >
                    + مخاطب جدید
                </button>
            </div>

            <!-- SEARCH -->
            <input
                v-model="search"
                type="text"
                placeholder="جستجو نام یا موبایل..."
                class="border p-2 rounded w-full mb-4 focus:outline-none focus:ring"
            />
            <select v-model="status" class="border p-2 rounded mb-4 ml-2">
                <option value="">همه وضعیت‌ها</option>
                <option value="new">جدید</option>
                <option value="called">تماس گرفته شده</option>
                <option value="customer">مشتری</option>
                <option value="rejected">رد شده</option>
                <option value="interested">علاقه‌مند</option>
            </select>

            <!-- TABLE -->
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="w-full text-sm border-collapse">

                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3 text-right">نام کسب‌وکار</th>
                            <th class="p-3 text-right">موبایل</th>
                            <th class="p-3 text-right">شهر</th>
                            <th class="p-3 text-right">وضعیت</th>
                            <th class="p-3 text-right">عملیات</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="b in businesses.data"
                            :key="b.id"
                            class="border-t hover:bg-gray-50"
                        >
                            <td class="p-3">{{ b.business_name }}</td>
                            <td class="p-3">{{ b.mobile }}</td>
                            <td class="p-3">{{ b.city }}</td>

                            <td class="p-3">
                                <select
                                    :value="b.status"
                                    @change="changeStatus(b.id, $event.target.value)"
                                >
                                    <option value="new">new</option>
                                    <option value="called">called</option>
                                    <option value="interested">interested</option>
                                    <option value="customer">customer</option>
                                    <option value="rejected">rejected</option>
                                </select>
                            </td>

                            <td class="p-3 flex gap-2">
                                <button
                                    @click="openEdit(b)"
                                    class="text-blue-600"
                                >
                                    ویرایش
                                </button>

                                <button
                                    type="button"
                                    @click="confirmDelete(b.id)"
                                    class="text-red-600"
                                >
                                    حذف
                                </button>
                            </td>
                        </tr>

                        <tr v-if="businesses.data.length === 0">
                            <td colspan="5" class="text-center p-6 text-gray-500">
                                هیچ مخاطبی یافت نشد
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex gap-2 mt-4 flex-wrap">
                <button
                    v-for="(link, index) in businesses.links"
                    :key="index"
                    :disabled="!link.url"
                    @click="link.url && router.get(link.url, {
                        preserveState: true,
                        preserveScroll: true
                    })"
                    v-html="link.label"
                    class="px-3 py-1 border rounded"
                    :class="{
                        'bg-blue-600 text-white': link.active,
                        'opacity-50': !link.url
                    }"
                />
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- MODAL -->
    <div
        v-if="showModal"
        class="fixed inset-0 bg-black/50 flex items-center justify-center"
    >
        <div class="bg-white w-[500px] p-6 rounded-lg">

            <h2 class="text-lg font-bold mb-4">
                {{ isEdit ? 'ویرایش مخاطب' : 'ایجاد مخاطب' }}
            </h2>

            <div class="space-y-2">

                <input v-model="form.business_name" placeholder="نام کسب‌وکار" class="border p-2 w-full" />
                <input v-model="form.contact_name" placeholder="نام شخص" class="border p-2 w-full" />
                <input v-model="form.mobile" placeholder="موبایل" class="border p-2 w-full" />
                <!-- <input v-model="form.phone" placeholder="تلفن" class="border p-2 w-full" /> -->
                <input v-model="form.city" placeholder="شهر" class="border p-2 w-full" />
                <!-- <input v-model="form.category" placeholder="دسته‌بندی" class="border p-2 w-full" /> -->
                <!-- <input v-model="form.source" placeholder="منبع" class="border p-2 w-full" /> -->

                <textarea
                    v-model="form.description"
                    placeholder="توضیحات"
                    class="border p-2 w-full"
                ></textarea>

            </div>

            <div class="flex justify-end gap-2 mt-4">

                <button
                    @click="showModal = false"
                    class="px-4 py-2 border"
                >
                    لغو
                </button>

                <button
                    @click="submit"
                    class="px-4 py-2 bg-green-600 text-white"
                >
                    ذخیره
                </button>

            </div>

        </div>
    </div>

    <!-- TOAST -->
    <div
        v-if="toast"
        class="fixed bottom-4 right-4 bg-green-600 text-white px-4 py-2 rounded"
    >
        {{ toast }}
    </div>

</template>