<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3'

defineProps({
    mustVerifyEmail: Boolean,
    status: String,
})

const user = usePage().props.auth.user

const form = useForm({
    name: user.name,
    email: user.email,
})

const submit = () => {
    form.patch(
        route('profile.update'),
        {
            preserveScroll: true,
        }
    )
}
</script>

<template>
    <section>
        <header class="flex items-start gap-3">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-xs font-black text-blue-600"
            >
                01
            </div>

            <div>
                <h2 class="text-base font-black text-slate-800">
                    اطلاعات حساب
                </h2>

                <p class="mt-1 text-xs leading-6 text-slate-500">
                    نام و ایمیل حساب کاربری خود را ویرایش کنید.
                </p>
            </div>
        </header>

        <form
            class="mt-6 grid gap-5 md:grid-cols-2"
            @submit.prevent="submit"
        >
            <div>
                <label
                    for="profile_name"
                    class="mb-2 block text-sm font-bold text-slate-700"
                >
                    نام و نام خانوادگی
                </label>

                <input
                    id="profile_name"
                    v-model="form.name"
                    type="text"
                    required
                    autocomplete="name"
                    class="w-full"
                    placeholder="نام کامل"
                >

                <p
                    v-if="form.errors.name"
                    class="mt-2 text-xs font-bold text-red-600"
                >
                    {{ form.errors.name }}
                </p>
            </div>

            <div>
                <label
                    for="profile_email"
                    class="mb-2 block text-sm font-bold text-slate-700"
                >
                    ایمیل
                </label>

                <input
                    id="profile_email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    dir="ltr"
                    class="w-full text-left"
                    placeholder="user@example.com"
                >

                <p
                    v-if="form.errors.email"
                    class="mt-2 text-xs font-bold text-red-600"
                >
                    {{ form.errors.email }}
                </p>
            </div>

            <div
                v-if="
                    mustVerifyEmail
                    && user.email_verified_at === null
                "
                class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs leading-7 text-amber-800 md:col-span-2"
            >
                ایمیل شما هنوز تأیید نشده است.

                <Link
                    :href="route('verification.send')"
                    method="post"
                    as="button"
                    class="font-extrabold underline"
                >
                    ارسال مجدد لینک تأیید
                </Link>

                <div
                    v-if="status === 'verification-link-sent'"
                    class="mt-2 font-bold text-emerald-700"
                >
                    لینک تأیید جدید ارسال شد.
                </div>
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
                            : 'ذخیره اطلاعات'
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
                        اطلاعات ذخیره شد.
                    </span>
                </Transition>
            </div>
        </form>
    </section>
</template>