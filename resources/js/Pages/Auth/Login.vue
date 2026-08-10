<script setup>
import Checkbox from '@/Components/Checkbox.vue'
import GuestLayout from '@/Layouts/GuestLayout.vue'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

import {
    Head,
    useForm,
} from '@inertiajs/vue3'


defineProps({
    status: String,
})


const form = useForm({
    mobile: '',
    password: '',
    remember: false,
})


const submit = () => {
    form.post(
        route('login'),
        {
            onFinish: () => {
                form.reset(
                    'password'
                )
            },
        }
    )
}
</script>


<template>
    <GuestLayout>

        <Head title="ورود به CRM" />

        <div
            dir="rtl"
            class="
                w-full
                text-right
            "
        >

            <!-- Header -->
            <div
                class="
                    mb-6
                    text-center
                "
            >

                <h1
                    class="
                        text-2xl
                        font-bold
                        text-gray-900
                    "
                >
                    ورود به CRM
                </h1>

                <p
                    class="
                        mt-2
                        text-sm
                        text-gray-500
                    "
                >
                    برای ورود، شماره موبایل و رمز عبور خود را وارد کنید.
                </p>

            </div>


            <!-- Status -->
            <div
                v-if="status"
                class="
                    mb-4
                    rounded
                    bg-green-50
                    p-3
                    text-sm
                    font-medium
                    text-green-700
                "
            >
                {{ status }}
            </div>


            <!-- Form -->
            <form
                @submit.prevent="
                    submit
                "
            >

                <!-- Mobile -->
                <div>

                    <InputLabel
                        for="mobile"
                        value="شماره موبایل"
                    />

                    <TextInput
                        id="mobile"
                        v-model="
                            form.mobile
                        "
                        type="tel"
                        dir="ltr"
                        class="
                            mt-1
                            block
                            w-full
                            text-left
                        "
                        placeholder="09121234567"
                        required
                        autofocus
                        autocomplete="username"
                        inputmode="numeric"
                    />

                    <InputError
                        class="mt-2"
                        :message="
                            form.errors.mobile
                        "
                    />

                </div>


                <!-- Password -->
                <div
                    class="mt-4"
                >

                    <InputLabel
                        for="password"
                        value="رمز عبور"
                    />

                    <TextInput
                        id="password"
                        v-model="
                            form.password
                        "
                        type="password"
                        dir="ltr"
                        class="
                            mt-1
                            block
                            w-full
                            text-left
                        "
                        required
                        autocomplete="current-password"
                    />

                    <InputError
                        class="mt-2"
                        :message="
                            form.errors.password
                        "
                    />

                </div>


                <!-- Remember -->
                <div
                    class="
                        mt-4
                        block
                    "
                >

                    <label
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >

                        <Checkbox
                            name="remember"
                            v-model:checked="
                                form.remember
                            "
                        />

                        <span
                            class="
                                text-sm
                                text-gray-600
                            "
                        >
                            مرا به خاطر بسپار
                        </span>

                    </label>

                </div>


                <!-- Submit -->
                <div
                    class="
                        mt-6
                    "
                >

                    <PrimaryButton
                        class="
                            w-full
                            justify-center
                        "
                        :class="{
                            'opacity-25':
                                form.processing,
                        }"
                        :disabled="
                            form.processing
                        "
                    >
                        ورود به سیستم
                    </PrimaryButton>

                </div>

            </form>

        </div>

    </GuestLayout>
</template>