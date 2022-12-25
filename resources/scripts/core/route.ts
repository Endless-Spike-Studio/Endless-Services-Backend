import axios from 'axios'
import route, {Config, RouteParam, RouteParamsWithQueryOverload} from 'ziggy-js'
import {ref} from 'vue'

export const routes = ref({
    url: location.href,
    routes: {},
    defaults: {}
} as Config);

axios.get('/api/routes')
    .then(response => {
        routes.value = response.data;
    });

// @ts-ignore
export default (name?: string, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean) => route(name, params, absolute, routes.value);
