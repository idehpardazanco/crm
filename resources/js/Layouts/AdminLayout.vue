<template>
    <div class="flex h-screen bg-gray-100" dir="rtl">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white shadow p-4">

            <h1 class="font-bold mb-6">
                پنل مدیریت
            </h1>

            <nav class="space-y-2">

                <a href="/monitoring" class="block p-2 hover:bg-gray-100 rounded">
                    Monitoring
                </a>

                <a href="/settings/sms" class="block p-2 hover:bg-gray-100 rounded">
                    SMS Settings
                </a>

            </nav>

        </aside>

        <!-- MAIN -->
        <div class="flex-1 flex flex-col">

            <!-- HEADER -->
            <header class="bg-white shadow p-3 flex justify-between">

                <div>
                    Dashboard
                </div>

                <div>
                    User
                </div>

            </header>

            <!-- CONTENT -->
            <main class="p-4 overflow-auto flex-1">

                <slot />

            </main>

        </div>

    </div>
</template>