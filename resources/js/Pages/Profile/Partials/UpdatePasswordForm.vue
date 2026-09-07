<script setup>
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const updatePassword = () => {
    form.put(
        route('password.update'),
        {
            preserveScroll: true,

            onSuccess: () => {
                form.reset()
            },

            onError: () => {
                if (form.errors.password) {
                    form.reset(
                        'password',
                        'password_confirmation'
                    )

                    passwordInput.value?.focus()
                }

                if (form.errors.current_password) {
                    form.reset('current_password')
                    currentPasswordInput.value?.focus()
                }
            },
        }
    )
}
</script>

<template>
    <section>
        <header class="flex items-start gap-3">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-50 text-xs font-black text-violet-600"
            >
                02
            </div>

            <div>
                <h2 class="text-base font-black text-slate-800">
                    تغییر رمز عبور
                </h2>

                <p class="mt-1 text-xs leading-6 text-slate-500">
                    برای امنیت بیشتر از یک رمز عبور قوی و متفاوت استفاده
                    کنید.
                </p>
            </div>
        </header>

        <form
            class="mt-6 grid gap-5 md:grid-cols-2"
            @submit.prevent="updatePassword"
        >
            <div class="md:col-span-2">
                <label
                    for="current_password"
                    class="mb-2 block text-sm font-bold text-slate-700"
                >
                    رمز عبور فعلی
                </label>

                <input
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    autocomplete="current-password"
                    class="w-full"
                    placeholder="رمز عبور فعلی"
                >

                <p
                    v-if="form.errors.current_password"
                    class="mt-2 text-xs font-bold text-red-600"
                >
                    {{ form.errors.current_password }}
                </p>
            </div>

            <div>
                <label
                    for="new_password"
                    class="mb-2 block text-sm font-bold text-slate-700"
                >
                    رمز عبور جدید
                </label>

                <input
                    id="new_password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    autocomplete="new-password"
                    class="w-full"
                    placeholder="حداقل ۸ کاراکتر"
                >

                <p
                    v-if="form.errors.password"
                    class="mt-2 text-xs font-bold text-red-600"
                >
                    {{ form.errors.password }}
                </p>
            </div>

            <div>
                <label
                    for="password_confirmation"
                    class="mb-2 block text-sm font-bold text-slate-700"
                >
                    تکرار رمز عبور جدید
                </label>

                <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    class="w-full"
                    placeholder="تکرار رمز عبور"
                >

                <p
                    v-if="form.errors.password_confirmation"
                    class="mt-2 text-xs font-bold text-red-600"
                >
                    {{ form.errors.password_confirmation }}
                </p>
            </div>

            <div
                class="flex flex-wrap items-center gap-4 md:col-span-2"
            >
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="min-h-11 rounded-xl bg-blue-600 px-6 text-sm font-extrabold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    {{
                        form.processing
                            ? 'در حال ذخیره...'
                            : 'تغییر رمز عبور'
                    }}
                </button>

                <Transition
                    enter-active-class="transition duration-200"
                    enter-from-class="opacity-0"
                    leave-active-class="transition duration-200"
                    leave-to-class="opacity-0"
                >
                    <span
                        v-if="form.recentlySuccessful"
                        class="text-sm font-bold text-emerald-600"
                    >
                        رمز عبور تغییر کرد.
                    </span>
                </Transition>
            </div>
        </form>
    </section>
</template>