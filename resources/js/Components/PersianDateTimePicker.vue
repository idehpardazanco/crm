<script setup>
import { ref, watch } from 'vue'
import DatePicker from 'vue3-persian-datetime-picker'

const props = defineProps({
    modelValue: {
        type: String,
        default: '',
    },

    placeholder: {
        type: String,
        default: 'تاریخ را انتخاب کنید',
    },
})

const emit = defineEmits([
    'update:modelValue',
])


// تاریخ میلادی که برای Backend ارسال می‌شود
const datePart = ref('')

// ساعت
const timePart = ref('')


// اگر از قبل مقداری وجود داشت
const syncFromModel = (value) => {

    if (!value) {

        datePart.value = ''
        timePart.value = ''

        return
    }

    const normalized = String(value).replace('T', ' ')

    const [
        date = '',
        time = '',
    ] = normalized.split(' ')

    datePart.value = date

    timePart.value = time
        ? time.slice(0, 5)
        : ''
}


// مقدار اولیه
syncFromModel(props.modelValue)


// اگر مقدار از والد تغییر کرد
watch(
    () => props.modelValue,

    (value) => {

        const current =
            datePart.value && timePart.value
                ? `${datePart.value} ${timePart.value}`
                : ''

        if ((value ?? '') !== current) {

            syncFromModel(value)

        }

    }
)


// ارسال تاریخ + ساعت به فرم
watch(
    [datePart, timePart],

    ([date, time]) => {

        if (date && time) {

            emit(
                'update:modelValue',
                `${date} ${time}`
            )

        } else {

            emit(
                'update:modelValue',
                ''
            )

        }

    }
)
</script>


<template>

    <div
        dir="rtl"
        class="grid grid-cols-1 gap-4 sm:grid-cols-2"
    >

        <!-- تاریخ -->
        <div>

            <label
                class="mb-2 block text-right text-sm font-medium text-gray-700"
            >
                تاریخ پیگیری
            </label>


            <DatePicker
                v-model="datePart"

                type="date"

                locale="fa"

                format="YYYY-MM-DD"

                display-format="jYYYY/jMM/jDD"

                :editable="false"

                :auto-submit="true"

                :show-now-btn="true"

                :clearable="false"

                simple

                popover="bottom-right"

                append-to="body"

                :placeholder="placeholder"

                input-class="w-full border border-gray-300 rounded p-2 bg-white text-gray-900 text-right"
            />

        </div>



        <!-- ساعت -->
        <div>

            <label
                class="mb-2 block text-right text-sm font-medium text-gray-700"
            >
                ساعت پیگیری
            </label>


            <input
                v-model="timePart"

                type="time"

                step="60"

                class="w-full rounded border border-gray-300 bg-white p-2 text-gray-900"

                dir="ltr"
            >

        </div>

    </div>

</template>