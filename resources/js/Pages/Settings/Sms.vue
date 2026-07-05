<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout
})


const form = ref({
    sms_from: '',
    sms_username: '',
    sms_password: '',
    sms_driver: ''
})

const loading = ref(false)

/**
 * Load settings
 */
const load = async () => {
    const res = await axios.get('/api/settings/sms')

    form.value = res.data
}

/**
 * Save settings
 */
const save = async () => {
    loading.value = true

    await axios.post('/api/settings/sms', form.value)

    loading.value = false
}

onMounted(load)
</script>

<template>
    <div class="p-6" dir="rtl">

        <h1 class="text-xl font-bold mb-6">
            تنظیمات SMS
        </h1>

        <div class="space-y-3">

            <input v-model="form.sms_from"
                   placeholder="شماره فرستنده"
                   class="border p-2 w-full" />

            <input v-model="form.sms_username"
                   placeholder="یوزرنیم"
                   class="border p-2 w-full" />

            <input v-model="form.sms_password"
                   placeholder="پسورد"
                   class="border p-2 w-full" />

            <input v-model="form.sms_driver"
                   placeholder="درایور"
                   class="border p-2 w-full" />

            <button @click="save"
                    class="bg-blue-600 text-white px-4 py-2 rounded">
                ذخیره
            </button>

        </div>

    </div>
</template>