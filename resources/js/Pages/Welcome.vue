<script setup>
import { Head, Link } from '@inertiajs/vue3'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
})
</script>

<template>
    <Head title="CRM ایده‌پردازان" />

    <div
        class="relative min-h-screen overflow-hidden bg-slate-950 text-white"
        dir="rtl"
    >
        <!-- Background -->
        <div
            class="absolute -right-40 -top-40 h-[520px] w-[520px] rounded-full bg-blue-600/25 blur-3xl"
        ></div>

        <div
            class="absolute -bottom-40 -left-32 h-[520px] w-[520px] rounded-full bg-cyan-500/15 blur-3xl"
        ></div>

        <div
            class="absolute left-1/2 top-1/2 h-[400px] w-[400px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/10 blur-3xl"
        ></div>

        <div
            class="absolute inset-0 opacity-[0.035]"
            style="
                background-image:
                    linear-gradient(
                        to right,
                        white 1px,
                        transparent 1px
                    ),
                    linear-gradient(
                        to bottom,
                        white 1px,
                        transparent 1px
                    );
                background-size: 40px 40px;
            "
        ></div>

        <div
            class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col px-5 py-6 sm:px-8"
        >
            <!-- Header -->
            <header class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-600 font-black shadow-lg shadow-blue-950/40"
                    >
                        CRM
                    </div>

                    <div>
                        <strong class="block">
                            CRM ایده‌پردازان
                        </strong>

                        <span class="text-xs text-blue-100/60">
                            مدیریت ارتباط با مشتریان
                        </span>
                    </div>
                </div>

                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold transition hover:bg-blue-500 sm:px-5"
                >
                    ورود به داشبورد
                </Link>

                <Link
                    v-else-if="canLogin"
                    :href="route('login')"
                    class="rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-sm font-bold backdrop-blur transition hover:bg-white/10 sm:px-5"
                >
                    ورود به سامانه
                </Link>
            </header>

            <!-- Main -->
            <main
                class="grid flex-1 items-center gap-12 py-16 lg:grid-cols-2"
            >
                <!-- Hero -->
                <section>
                    <div
                        class="mb-5 inline-flex rounded-full border border-blue-400/20 bg-blue-500/10 px-4 py-2 text-xs font-bold text-blue-200"
                    >
                        سامانه داخلی مدیریت فروش و مشتری
                    </div>

                    <h1
                        class="max-w-2xl text-4xl font-black leading-[1.45] sm:text-5xl lg:text-6xl"
                    >
                        همه ارتباطات مشتری،

                        <span class="block text-blue-400">
                            در یک فضای یکپارچه
                        </span>
                    </h1>

                    <p
                        class="mt-6 max-w-xl text-base leading-9 text-slate-300"
                    >
                        مدیریت مخاطبین، تماس‌ها، پیگیری‌ها، پیامک‌ها و
                        سفارش‌ها؛ سریع، دقیق و قابل گزارش‌گیری.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        <Link
                            v-if="canLogin"
                            :href="
                                $page.props.auth.user
                                    ? route('dashboard')
                                    : route('login')
                            "
                            class="flex min-h-[52px] items-center rounded-xl bg-blue-600 px-7 font-extrabold shadow-xl shadow-blue-950/30 transition hover:-translate-y-0.5 hover:bg-blue-500"
                        >
                            {{
                                $page.props.auth.user
                                    ? 'مشاهده داشبورد'
                                    : 'ورود به حساب کاربری'
                            }}
                        </Link>
                    </div>
                </section>

                <!-- Features -->
                <section
                    class="rounded-[28px] border border-white/10 bg-white/5 p-5 shadow-2xl shadow-black/20 backdrop-blur-xl sm:p-7"
                >
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div
                            class="rounded-2xl border border-white/10 bg-slate-900/40 p-5"
                        >
                            <div
                                class="mb-4 h-2 w-10 rounded-full bg-blue-500"
                            ></div>

                            <strong>
                                مخاطبین
                            </strong>

                            <p
                                class="mt-2 text-xs leading-6 text-slate-400"
                            >
                                مدیریت کامل اطلاعات مشتری و کسب‌وکار
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-white/10 bg-slate-900/40 p-5"
                        >
                            <div
                                class="mb-4 h-2 w-10 rounded-full bg-violet-500"
                            ></div>

                            <strong>
                                پیگیری‌ها
                            </strong>

                            <p
                                class="mt-2 text-xs leading-6 text-slate-400"
                            >
                                یادآوری و مدیریت تماس‌های بعدی
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-white/10 bg-slate-900/40 p-5"
                        >
                            <div
                                class="mb-4 h-2 w-10 rounded-full bg-emerald-500"
                            ></div>

                            <strong>
                                پیامک
                            </strong>

                            <p
                                class="mt-2 text-xs leading-6 text-slate-400"
                            >
                                ارسال سریع متن‌های آماده به مشتری
                            </p>
                        </div>

                        <div
                            class="rounded-2xl border border-white/10 bg-slate-900/40 p-5"
                        >
                            <div
                                class="mb-4 h-2 w-10 rounded-full bg-amber-500"
                            ></div>

                            <strong>
                                گزارش‌ها
                            </strong>

                            <p
                                class="mt-2 text-xs leading-6 text-slate-400"
                            >
                                بررسی دقیق عملکرد تیم فروش
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-4 rounded-2xl border border-blue-400/15 bg-blue-500/10 p-5"
                    >
                        <div
                            class="flex items-center justify-between gap-4"
                        >
                            <div>
                                <strong class="text-blue-100">
                                    مدیریت متمرکز
                                </strong>

                                <p
                                    class="mt-2 text-xs leading-6 text-blue-100/60"
                                >
                                    تمام سوابق مشتری در یک پرونده واحد
                                </p>
                            </div>

                            <div
                                class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-500/20 text-blue-300"
                            >
                                <svg
                                    class="h-6 w-6"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M4 19.5V9.75A2.75 2.75 0 0 1 6.75 7H17.25A2.75 2.75 0 0 1 20 9.75V19.5M8 7V5.75A1.75 1.75 0 0 1 9.75 4H14.25A1.75 1.75 0 0 1 16 5.75V7M3 19.5H21"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>
                </section>
            </main>

            <!-- Footer -->
            <footer
                class="border-t border-white/10 py-5 text-center text-xs text-slate-500"
            >
                CRM ایده‌پردازان — سامانه مدیریت ارتباط با مشتری
            </footer>
        </div>
    </div>
</template>