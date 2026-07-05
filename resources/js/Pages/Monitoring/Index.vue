<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

/**
 * Active Tab Control
 */
const tab = ref('activities')

/**
 * Data States
 */
const activities = ref([])
const systemLogs = ref([])
const requestLogs = ref([])

/**
 * Loading State
 */
const loading = ref(false)

/**
 * Fetch Data Based on Tab
 */
const fetchData = async () => {
    loading.value = true

    try {
        if (tab.value === 'activities') {
            const res = await axios.get('/api/v1/monitoring/activities')
            activities.value = res.data.data
        }

        if (tab.value === 'system') {
            const res = await axios.get('/api/v1/monitoring/system-logs')
            systemLogs.value = res.data.data
        }

        if (tab.value === 'requests') {
            const res = await axios.get('/api/v1/monitoring/request-logs')
            requestLogs.value = res.data.data
        }

    } finally {
        loading.value = false
    }
}

/**
 * Change Tab
 */
const changeTab = async (name) => {
    tab.value = name
    await fetchData()
}

/**
 * Initial Load
 */
onMounted(fetchData)
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-6" dir="rtl">

        <!-- HEADER -->
        <div class="mb-6">
            <h1 class="text-xl font-bold text-gray-800">
                مانیتورینگ سیستم
            </h1>
            <p class="text-sm text-gray-500">
                بررسی لاگ‌ها و رفتار سیستم
            </p>
        </div>

        <!-- TABS -->
        <div class="flex gap-2 mb-6">

            <button
                @click="changeTab('activities')"
                class="px-4 py-2 rounded bg-blue-600 text-white"
            >
                فعالیت‌ها
            </button>

            <button
                @click="changeTab('system')"
                class="px-4 py-2 rounded bg-red-600 text-white"
            >
                خطاها
            </button>

            <button
                @click="changeTab('requests')"
                class="px-4 py-2 rounded bg-green-600 text-white"
            >
                درخواست‌ها
            </button>

        </div>

        <!-- LOADING -->
        <div v-if="loading" class="text-center py-10 text-gray-500">
            در حال بارگذاری...
        </div>

        <!-- TABLES -->

        <!-- Activities -->
        <div v-if="tab === 'activities' && !loading">
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">عمل</th>
                        <th class="p-2">ماژول</th>
                        <th class="p-2">جزئیات</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="item in activities" :key="item.id">
                        <td class="p-2">{{ item.action }}</td>
                        <td class="p-2">{{ item.module }}</td>
                        <td class="p-2 text-xs">
                            {{ item.meta }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- System Logs -->
        <div v-if="tab === 'system' && !loading">
            <table class="w-full bg-white shadow rounded">
                <tbody>
                    <tr v-for="item in systemLogs" :key="item.id">
                        <td class="p-2 text-red-600 font-bold">
                            {{ item.level }}
                        </td>
                        <td class="p-2">
                            {{ item.message }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Request Logs -->
        <div v-if="tab === 'requests' && !loading">
            <table class="w-full bg-white shadow rounded">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2">Method</th>
                        <th class="p-2">URL</th>
                        <th class="p-2">Status</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="item in requestLogs" :key="item.id">
                        <td class="p-2">{{ item.method }}</td>
                        <td class="p-2 text-xs">{{ item.url }}</td>
                        <td class="p-2">{{ item.status_code }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>