import './bootstrap';
import './../css/app.css';
import "./SVWS-Server/svws-ui-components/dist/style.css";

import { createApp, h, Plugin } from 'vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createInertiaApp, InertiaApp, InertiaAppProps } from '@inertiajs/inertia-vue3';
import { createPinia } from 'pinia'
import SvwsUi from "./SVWS-Server/svws-ui-components";

const pinia = createPinia()
const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

import '../css/app.css';

if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
	document.documentElement.classList.add('dark', 'theme-dark')
} else {
	document.documentElement.classList.remove('dark', 'theme-dark')
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    // @ts-ignore
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, app, props, plugin}: { el: Element, app: InertiaApp, props: InertiaAppProps, plugin: Plugin }): void | any {
        return createApp({render: () => h(app, props)})
            // @ts-ignore
            .use(SvwsUi)
            .use(plugin)
            .use(createPinia())
            .mixin({ methods: {route}})
            .mount(el);
    },
});
