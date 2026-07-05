<script setup>
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

const tab = ref('activities')
const loading = ref(false)
//
const selectedLog = ref(null)
const showModal = ref(false)

const from = ref('')
const to = ref('')

const openModal = (item) => {
    selectedLog.value = item
    showModal.value = true
}

/**
 * Data
 */
const activities = ref([])
const systemLogs = ref([])
const requestLogs = ref([])

/**
 * Filters
 */
const search = ref('')
const moduleFilter = ref('')
const levelFilter = ref('')

/**
 * Pagination
 */
const page = ref(1)
const meta = ref({})
/**
 * Fetch Data
 */

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

if (tab.value === 'activities') {
    activities.value = res.data.data
}

if (tab.value === 'system') {
    systemLogs.value = res.data.data
}

if (tab.value === 'requests') {
    requestLogs.value = res.data.data
}

meta.value = res.data.meta ?? res.data
/**
 * Tab Change
 */
const changeTab = async (t) => {
    tab.value = t
    page.value = 1
    await fetchData()
}

/**
 * Auto reload on filter change
 */
watch([search, moduleFilter, levelFilter, page], fetchData)
onMounted(fetchData)
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen" dir="rtl">

        <!-- HEADER -->
        <div class="mb-4">
            <h1 class="text-xl font-bold">Monitoring Dashboard</h1>
        </div>

        <!-- FILTERS -->
        <div class="flex gap-2 mb-4">

            <input
                v-model="search"
                placeholder="جستجو..."
                class="border p-2 rounded w-1/3"
            />

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

        </div>

        <!-- TABS -->
        <div class="flex gap-2 mb-4">

            <button @click="changeTab('activities')" class="px-4 py-2 bg-blue-600 text-white rounded">
                Activities
            </button>

            <button @click="changeTab('system')" class="px-4 py-2 bg-red-600 text-white rounded">
                Errors
            </button>

            <button @click="changeTab('requests')" class="px-4 py-2 bg-green-600 text-white rounded">
                Requests
            </button>

        </div>

        <!-- LOADING -->
        <div v-if="loading" class="text-center py-10 text-gray-500">
            <div class="animate-spin mb-2">⏳</div>
            در حال دریافت اطلاعات...
        </div>

        <!-- TABLE -->
        <table v-if="!loading" class="w-full bg-white shadow rounded">

            <tbody v-if="tab === 'activities'">
                <tr v-for="i in activities" :key="i.id" @click="openModal(i)" class="cursor-pointer">
                    <td>{{ i.action }}</td>
                    <td>{{ i.module }}</td>
                </tr>
            </tbody>

            <tbody v-if="tab === 'system'">
                <tr v-for="i in systemLogs" :key="i.id">
                    <td class="text-red-600">{{ i.level }}</td>
                    <td>{{ i.message }}</td>
                </tr>
            </tbody>

            <tbody v-if="tab === 'requests'">
                <tr v-for="i in requestLogs" :key="i.id">
                    <td>{{ i.method }}</td>
                    <td>{{ i.url }}</td>
                    <td>{{ i.status_code }}</td>
                </tr>
            </tbody>

        </table>

    </div>
    <div v-if="showModal" class="fixed inset-0 bg-black/50 flex items-center justify-center">

    <div class="bg-white w-1/2 p-6 rounded">

        <h2 class="text-lg font-bold mb-4">جزئیات لاگ</h2>

        <pre class="text-sm bg-gray-100 p-3 rounded overflow-auto">
            {{ selectedLog }}
        </pre>

        <button
            class="mt-4 px-4 py-2 bg-red-600 text-white rounded"
            @click="showModal = false"
        >
            بستن
        </button>

    </div>

</div>
</template>
