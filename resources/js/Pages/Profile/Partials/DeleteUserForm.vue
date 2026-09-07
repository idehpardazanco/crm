<script setup>
import Modal from '@/Components/Modal.vue'
import { useForm } from '@inertiajs/vue3'
import { nextTick, ref } from 'vue'

const confirmingUserDeletion = ref(false)
const passwordInput = ref(null)

const form = useForm({
    password: '',
})

const openModal = () => {
    confirmingUserDeletion.value = true

    nextTick(() => {
        passwordInput.value?.focus()
    })
}

const closeModal = () => {
    confirmingUserDeletion.value = false
    form.clearErrors()
    form.reset()
}

const deleteUser = () => {
    form.delete(
        route('profile.destroy'),
        {
            preserveScroll: true,

            onSuccess: () => {
                closeModal()
            },

            onError: () => {
                passwordInput.value?.focus()
            },

            onFinish: () => {
                form.reset()
            },
        }
    )
}
</script>

<template>
    <section>
        <header class="flex items-start gap-3">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 font-black text-red-600"
            >
                !
            </div>

            <div>
                <h2 class="text-base font-black text-slate-800">
                    حذف حساب کاربری
                </h2>

                <p class="mt-1 text-xs leading-6 text-slate-500">
                    با حذف حساب، اطلاعات مرتبط به‌صورت دائمی حذف می‌شود.
                </p>
            </div>
        </header>

        <div
            class="mt-6 rounded-xl border border-red-100 bg-red-50/50 p-4"
        >
            <p class="text-xs leading-7 text-red-700">
                این عملیات قابل بازگشت نیست. قبل از حذف حساب از اطلاعات
                موردنیاز خود نسخه پشتیبان تهیه کنید.
            </p>
        </div>

        <button
            type="button"
            class="mt-5 min-h-11 rounded-xl bg-red-600 px-6 text-sm font-extrabold text-white transition hover:bg-red-700"
            @click="openModal"
        >
            حذف حساب
        </button>

        <Modal
            :show="confirmingUserDeletion"
            @close="closeModal"
        >
            <div
                class="p-6"
                dir="rtl"
            >
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-xl font-black text-red-600"
                >
                    !
                </div>

                <h3 class="mt-5 text-lg font-black text-slate-900">
                    از حذف حساب مطمئن هستید؟
                </h3>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    این عملیات قابل بازگشت نیست. برای تأیید، رمز عبور خود
                    را وارد کنید.
                </p>

                <input
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-5 w-full"
                    placeholder="رمز عبور"
                    @keyup.enter="deleteUser"
                >

                <p
                    v-if="form.errors.password"
                    class="mt-2 text-xs font-bold text-red-600"
                >
                    {{ form.errors.password }}
                </p>

                <div class="mt-6 flex gap-3">
                    <button
                        type="button"
                        :disabled="form.processing"
                        class="min-h-11 flex-1 rounded-xl border border-slate-200 font-bold text-slate-600"
                        @click="closeModal"
                    >
                        انصراف
                    </button>

                    <button
                        type="button"
                        :disabled="form.processing"
                        class="min-h-11 flex-1 rounded-xl bg-red-600 font-bold text-white disabled:opacity-50"
                        @click="deleteUser"
                    >
                        {{
                            form.processing
                                ? 'در حال حذف...'
                                : 'حذف دائمی'
                        }}
                    </button>
                </div>
            </div>
        </Modal>
    </section>
</template>