import "@/styles/main.scss";
import {createApp, DefineComponent, h} from "vue";
import {createInertiaApp} from "@inertiajs/inertia-vue3";
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {InertiaProgress} from "@inertiajs/progress";
import axios from "axios";
import {pinia} from "@/scripts/core/client";
import {useProp} from "@/scripts/core/utils";
import persist from "pinia-plugin-persist";

if (import.meta.env.PROD && location.protocol === 'http:') {
    location.protocol = 'https:';
}

createInertiaApp({
    resolve: (name: string) => resolvePageComponent(`/resources/views/Pages/${name}.vue`, import.meta.glob('@/views/Pages/**/*.vue') as Record<string, () => Promise<DefineComponent>>),
    setup({el, app, props, plugin}) {
        const instance = createApp({
            render: () => h(app, props)
        });

        instance.use(pinia);
        pinia.use(persist);

        instance.use(plugin);
        instance.mount(el);
    }
}).then(() => {
    axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': useProp<string>('csrf_token').value
    }

    InertiaProgress.init();
});
