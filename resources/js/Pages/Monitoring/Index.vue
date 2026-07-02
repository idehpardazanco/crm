<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const activeTab = ref('activities')

const activities = ref([])
const systemLogs = ref([])
const requestLogs = ref([])

const fetchData = async () => {

    if (activeTab.value === 'activities') {
        const res = await axios.get('/api/v1/monitoring/activities')
        activities.value = res.data.data
    }

    if (activeTab.value === 'system') {
        const res = await axios.get('/api/v1/monitoring/system-logs')
        systemLogs.value = res.data.data
    }

    if (activeTab.value === 'requests') {
        const res = await axios.get('/api/v1/monitoring/request-logs')
        requestLogs.value = res.data.data
    }
}

onMounted(fetchData)
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen" dir="rtl">

        <!-- Tabs -->
        <div class="flex gap-3 mb-6">
            <button @click="activeTab='activities'; fetchData()"
                class="px-4 py-2 bg-blue-600 text-white rounded">
                فعالیت‌ها
            </button>

            <button @click="activeTab='system'; fetchData()"
                class="px-4 py-2 bg-red-600 text-white rounded">
                خطاها
            </button>

            <button @click="activeTab='requests'; fetchData()"
                class="px-4 py-2 bg-green-600 text-white rounded">
                درخواست‌ها
            </button>
        </div>

        <!-- Activities -->
        <div v-if="activeTab === 'activities'">
            <table class="w-full bg-white shadow rounded">
                <thead>
                    <tr class="bg-gray-100">
                        <th>عمل</th>
                        <th>ماژول</th>
                        <th>اطلاعات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in activities" :key="item.id">
                        <td>{{ item.action }}</td>
                        <td>{{ item.module }}</td>
                        <td>{{ item.meta }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- System Logs -->
        <div v-if="activeTab === 'system'">
            <table class="w-full bg-white shadow rounded">
                <tr v-for="item in systemLogs" :key="item.id">
                    <td>{{ item.level }}</td>
                    <td>{{ item.message }}</td>
                </tr>
            </table>
        </div>

        <!-- Requests -->
        <div v-if="activeTab === 'requests'">
            <table class="w-full bg-white shadow rounded">
                <tr v-for="item in requestLogs" :key="item.id">
                    <td>{{ item.method }}</td>
                    <td>{{ item.url }}</td>
                    <td>{{ item.status_code }}</td>
                </tr>
            </table>
        </div>

    </div>
</template>