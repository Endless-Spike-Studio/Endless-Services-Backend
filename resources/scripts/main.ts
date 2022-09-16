import {createApp, h} from "vue";
import {createInertiaApp} from "@inertiajs/inertia-vue3";
import {importPageComponent} from "@/scripts/vite/import-page-component";
import {InertiaProgress} from "@inertiajs/progress";
import axios from "axios";
import {pages, pinia} from "@/scripts/core/client";
import {useProp} from "@/scripts/core/utils";
import persist from "pinia-plugin-persist";

if (import.meta.env.PROD && location.protocol === 'http:') {
    location.protocol = 'https:';
}

createInertiaApp({
    resolve: (name: string) => importPageComponent(name, pages),
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
    InertiaProgress.init();
    axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': useProp<string>('csrf_token').value
    }
});
