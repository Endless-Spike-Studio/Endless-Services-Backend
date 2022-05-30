import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue3'
import {importPageComponent} from '@/scripts/vite/import-page-component'
import {createPinia} from 'pinia'
import {InertiaProgress} from '@inertiajs/progress'

if (import.meta.env.PROD && location.protocol === 'http:') {
    location.protocol = 'https:';
}

const components = import.meta.glob('../views/pages/**/*.vue');
const pinia = createPinia();

createInertiaApp({
    resolve: (name) => importPageComponent(name, components),
    setup({el, app, props, plugin}) {
        const _app = createApp({
            render: () => h(app, props)
        });

        _app.use(pinia);
        _app.use(plugin);
        _app.mount(el);
    },
}).then(() => {
    InertiaProgress.init();
});
