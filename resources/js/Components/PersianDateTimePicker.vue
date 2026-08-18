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
        default: 'تاریخ و ساعت را انتخاب کنید',
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

            type="datetime"

            locale="fa"

            format="YYYY-MM-DD HH:mm"

            display-format="jYYYY/jMM/jDD HH:mm"

            :editable="false"

            :auto-submit="true"

            :show-now-btn="true"

            :placeholder="placeholder"

            input-class="w-full border rounded p-2 bg-white text-gray-900"
        />

    </div>

</template>


<style>

/*
 فقط کمی فضای عمودی داخل مودال.
 استایل خود فیلد تاریخ دست نخورده است.
*/
.vpd-wrapper[data-type="datetime"] .vpd-body {
    padding-top: 14px !important;
    padding-bottom: 14px !important;
}


/*
 کمی فاصله بین محتوای انتخاب ساعت
 و دکمه‌های پایین مودال
*/
.vpd-wrapper[data-type="datetime"] .vpd-actions {
    margin-top: 10px !important;
}

</style>