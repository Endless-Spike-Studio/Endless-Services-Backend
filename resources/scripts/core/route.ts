import axios from "axios";
import route, {Config, RouteParamsWithQueryOverload} from "ziggy-js";
import {ref} from "vue";
import event from "@/scripts/core/event";

export const routes = ref<Config>({
    url: location.href,
    routes: {},
    defaults: {}
});

axios.get('/api/routes')
    .then(response => {
        routes.value = response.data;
        event.emit('routes.loaded');
    });

// @ts-ignore
export default (name?: string, params?: RouteParamsWithQueryOverload, absolute?: boolean) => route(name, params, absolute, routes.value);
