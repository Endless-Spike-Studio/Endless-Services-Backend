import route, {Config} from "ziggy-js";
import axios from "axios";

export const routes = ref<Config>();

axios.get('/api/routes')
    .then(response => {
        routes.value = response.data;
    });

export default <
    args extends Parameters<typeof route>
>(name?: args[0], params?: args[1], absolute?: args[2]) => {
    // @ts-ignore
    return route(name, params, absolute, routes.value);
};
