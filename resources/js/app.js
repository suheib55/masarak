import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
  faBook,
  faBullhorn,
  faLink,
  faCalculator,
  faComments,
  faCalendar,
  faCar,
  faFileAlt
} from '@fortawesome/free-solid-svg-icons'

library.add(faBook, faBullhorn, faLink, faCalculator, faComments, faCalendar, faCar, faFileAlt)

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)

        // تسجيل مكون الأيقونات
        app.component('font-awesome-icon', FontAwesomeIcon)

        app.mount(el)
    },
    progress: {
        color: '#4B5563',
    },
});
