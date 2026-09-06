<script setup>
import { computed } from 'vue'
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

const value = computed({
    get: () => props.modelValue,

    set: (newValue) => {
        emit(
            'update:modelValue',
            newValue ?? ''
        )
    },
})
</script>


<template>

    <div dir="rtl">

        <DatePicker
            v-model="value"

            type="date"

            locale="fa"

            format="YYYY-MM-DD"

            display-format="jYYYY/jMM/jDD"

            :editable="false"

            :auto-submit="true"

            :show-now-btn="true"

            :clearable="true"

            :placeholder="placeholder"

            input-class="w-full border rounded p-2 bg-white text-gray-900"
        />

    </div>

</template>
