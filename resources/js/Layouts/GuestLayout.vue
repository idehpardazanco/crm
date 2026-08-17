<template>
    <div
        dir="rtl"
        class="relative min-h-screen overflow-hidden bg-slate-950"
    >
        <!-- Background -->
        <div class="absolute inset-0">
            <div
                class="absolute -right-32 -top-32 h-[420px] w-[420px] rounded-full bg-blue-600/25 blur-3xl"
            ></div>

            <div
                class="absolute -bottom-40 -left-32 h-[500px] w-[500px] rounded-full bg-cyan-500/15 blur-3xl"
            ></div>

            <div
                class="absolute left-1/2 top-1/2 h-[350px] w-[350px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/10 blur-3xl"
            ></div>

            <div
                class="absolute inset-0 opacity-[0.035]"
                style="
                    background-image:
                        linear-gradient(to right, white 1px, transparent 1px),
                        linear-gradient(to bottom, white 1px, transparent 1px);
                    background-size: 40px 40px;
                "
            ></div>
        </div>

        <!-- Content -->
        <div
            class="relative z-10 flex min-h-screen items-center justify-center px-4 py-8 sm:px-6"
        >
            <slot />
        </div>
    </div>
</template>