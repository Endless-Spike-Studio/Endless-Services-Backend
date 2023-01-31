import "@/styles/main.scss";
import {createApp, DefineComponent, h} from "vue";
import {createInertiaApp} from "@inertiajs/vue3";
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import axios from "axios";
import {pinia} from "@/scripts/core/client";
import {useProp} from "@/scripts/core/utils";
import products from "@/shared/products.json";

createInertiaApp({
    resolve: (name: string) => {
        const pages = import.meta.glob<DefineComponent>('@/views/pages/**/*.vue');
        return resolvePageComponent(`/resources/views/pages/${name}.vue`, pages);
    },
    setup({el, App, props, plugin}) {
        const instance = createApp({
            render: () => h(App, props)
        });

        instance.use(pinia);
        instance.use(plugin);

        instance.mount(el);
    },
    title: name => {
        const product = products.find(_ => location.hostname === _.domain) ?? products.find(_ => !_.domain)!;

        if (!name) {
            return product.name;
        }

        return `[${product.code}] ${name}`;
    }
}).then(() => {
    axios.defaults.headers.common = {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': useProp<string>('csrf_token').value
    }
});
