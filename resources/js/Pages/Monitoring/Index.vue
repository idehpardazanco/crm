<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

/**
 * Tabs
 */
const tab = ref('activities')

/**
 * Data
 */
const activities = ref([])
const systemLogs = ref([])
const requestLogs = ref([])

/**
 * Loading
 */
const loading = ref(false)

/**
 * Filters
 */
const search = ref('')
const moduleFilter = ref('')
const levelFilter = ref('')
const from = ref('')
const to = ref('')

/**
 * Pagination
 */
const page = ref(1)
const meta = ref({})

/**
 * Modal
 */
const selectedLog = ref(null)
const showModal = ref(false)

/**
 * Open modal
 */
const openModal = (item) => {
    selectedLog.value = item
    showModal.value = true
}

/**
 * Fetch data
 */
const fetchData = async () => {

    loading.value = true

    let url = ''

    if (tab.value === 'activities') {
        url = '/api/v1/monitoring/activities'
    }

    if (tab.value === 'system') {
        url = '/api/v1/monitoring/system-logs'
    }

    if (tab.value === 'requests') {
        url = '/api/v1/monitoring/request-logs'
    }

    const res = await axios.get(url, {
        params: {
            search: search.value,
            module: moduleFilter.value,
            level: levelFilter.value,
            page: page.value,
            from: from.value,
            to: to.value
        }
    })

    const payload = res.data

    if (tab.value === 'activities') activities.value = payload.data
    if (tab.value === 'system') systemLogs.value = payload.data
    if (tab.value === 'requests') requestLogs.value = payload.data

    meta.value = payload.meta

    loading.value = false
}

/**
 * Change tab
 */
const changeTab = async (t) => {
    tab.value = t
    page.value = 1
    await fetchData()
}

/**
 * Auto refresh on filter change
 */
onMounted(fetchData)
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen" dir="rtl">

        <!-- HEADER -->
        <div class="mb-4">
            <h1 class="text-xl font-bold text-gray-800">
                Monitoring Dashboard
            </h1>
        </div>

        <!-- FILTERS -->
        <div class="flex gap-2 mb-4 flex-wrap">

            <input v-model="search" placeholder="جستجو..."
                   class="border p-2 rounded" />

            <select v-model="moduleFilter" class="border p-2 rounded">
                <option value="">همه ماژول‌ها</option>
                <option value="Auth">Auth</option>
                <option value="Sms">Sms</option>
            </select>

            <select v-model="levelFilter" class="border p-2 rounded">
                <option value="">همه سطح‌ها</option>
                <option value="error">Error</option>
                <option value="info">Info</option>
            </select>

            <input type="date" v-model="from" class="border p-2 rounded" />
            <input type="date" v-model="to" class="border p-2 rounded" />

            <button @click="fetchData"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                اعمال فیلتر
            </button>

        </div>

        <!-- TABS -->
        <div class="flex gap-2 mb-4">

            <button @click="changeTab('activities')"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                Activities
            </button>

            <button @click="changeTab('system')"
                    class="px-4 py-2 bg-red-600 text-white rounded">
                Errors
            </button>

            <button @click="changeTab('requests')"
                    class="px-4 py-2 bg-green-600 text-white rounded">
                Requests
            </button>

        </div>

        <!-- LOADING -->
        <div v-if="loading" class="text-center py-10 text-gray-500">
            در حال بارگذاری...
        </div>

        <!-- TABLES -->
        <div v-if="!loading">

            <!-- Activities -->
            <table v-if="tab === 'activities'" class="w-full bg-white shadow rounded">
                <tbody>
                    <tr v-for="i in activities" :key="i.id"
                        @click="openModal(i)"
                        class="border-b hover:bg-gray-50 cursor-pointer">

                        <td class="p-3 font-bold">{{ i.action }}</td>

                        <td class="p-3">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs">
                                {{ i.module }}
                            </span>
                        </td>

                        <td class="p-3 text-xs text-gray-500">
                            {{ i.created_at }}
                        </td>

                    </tr>
                </tbody>
            </table>

            <!-- System Logs -->
            <table v-if="tab === 'system'" class="w-full bg-white shadow rounded">
                <tbody>
                    <tr v-for="i in systemLogs" :key="i.id"
                        @click="openModal(i)"
                        class="border-b hover:bg-gray-50 cursor-pointer">

                        <td class="p-3">
                            <span :class="i.level === 'error'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-yellow-100 text-yellow-700'"
                                  class="px-2 py-1 rounded text-xs font-bold">
                                {{ i.level }}
                            </span>
                        </td>

                        <td class="p-3 text-gray-700">
                            {{ i.message }}
                        </td>

                    </tr>
                </tbody>
            </table>

            <!-- Requests -->
            <table v-if="tab === 'requests'" class="w-full bg-white shadow rounded">
                <tbody>
                    <tr v-for="i in requestLogs" :key="i.id"
                        @click="openModal(i)"
                        class="border-b hover:bg-gray-50 cursor-pointer">

                        <td class="p-3 font-bold">{{ i.method }}</td>

                        <td class="p-3 text-xs text-gray-600">{{ i.url }}</td>

                        <td class="p-3">
                            <span :class="i.status_code >= 400
                                ? 'bg-red-100 text-red-700'
                                : 'bg-green-100 text-green-700'"
                                  class="px-2 py-1 rounded text-xs">
                                {{ i.status_code }}
                            </span>
                        </td>

                    </tr>
                </tbody>
            </table>

        </div>

        <!-- PAGINATION -->
        <div class="flex justify-center gap-2 mt-6" v-if="meta.last_page > 1">

            <button class="px-3 py-1 bg-gray-200 rounded"
                    @click="page--"
                    :disabled="page <= 1">
                قبلی
            </button>

            <span class="px-3 py-1">
                صفحه {{ page }} از {{ meta.last_page }}
            </span>

            <button class="px-3 py-1 bg-gray-200 rounded"
                    @click="page++"
                    :disabled="page >= meta.last_page">
                بعدی
            </button>

        </div>

        <!-- MODAL -->
        <div v-if="showModal"
             class="fixed inset-0 bg-black/50 flex items-center justify-center">

            <div class="bg-white w-1/2 p-6 rounded">

                <h2 class="font-bold mb-4">جزئیات لاگ</h2>

                <pre class="text-xs bg-gray-100 p-3 rounded overflow-auto">
{{ selectedLog }}
                </pre>

                <button @click="showModal = false"
                        class="mt-4 px-4 py-2 bg-red-600 text-white rounded">
                    بستن
                </button>

            </div>

        </div>

    </div>
</template>