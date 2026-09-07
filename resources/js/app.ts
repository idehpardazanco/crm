import '../css/app.css'

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import {
    createApp,
    DefineComponent,
    h,
} from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'

import AdminLayout from './Layouts/AdminLayout.vue'

const appName =
    import.meta.env.VITE_APP_NAME
    || 'Laravel'

createInertiaApp({
    title: title =>
        `${title} - ${appName}`,

    resolve: async name => {
        const page: any =
            await resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob<DefineComponent>(
                    './Pages/**/*.vue'
                )
            )

        const crmPages = [
            'Dashboard',
            'Users/',
            'Contacts/',
            'FollowUps/',
            'Orders/',
            'Sms/',
            'Monitoring/',
            'Profile/',
        ]

        const useAdminLayout =
            crmPages.some(
                prefix =>
                    name === prefix
                    || name.startsWith(prefix)
            )

        if (useAdminLayout) {
            page.default.layout =
                page.default.layout
                || AdminLayout
        }

        return page
    },

    setup({
        el,
        App,
        props,
        plugin,
    }) {
        createApp({
            render: () =>
                h(App, props),
        })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },

    progress: {
        color: '#2563eb',
    },
})