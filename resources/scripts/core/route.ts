import axios from "axios";
import route, {Config} from "ziggy-js";
import {ref} from "vue";

export const routes = ref<Config>({
    url: location.href,
    routes: {},
    defaults: {}
});

axios.get('/api/routes', {
    headers: {
        'X-Auth': '3a8fac65d497fd23b4038df87e5a597670d287cd'
    }
}).then(response => {
    routes.value = response.data;
});

export default (...args: unknown[]) => {
    args[3] = routes.value;

    // @ts-ignore
    return route(...args);
};
