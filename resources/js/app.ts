require('./bootstrap');

import { createApp, h } from 'vue';
import { Plugin } from 'vue'
import { createInertiaApp, InertiaApp, InertiaAppProps } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';

import SvwsUi from "./SVWS-Server/svws-ui-components";
import "./SVWS-Server/svws-ui-components/dist/style.css";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }: {el: Element, app: InertiaApp, props: InertiaAppProps, plugin: Plugin}): void | any {
        return createApp({ render: () => h(app, props) })
            .use(SvwsUi)
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: '#4B5563' });
