import axios from 'axios'
import route, {Config, RouteParamsWithQueryOverload} from 'ziggy-js'
import {ref} from 'vue'

export const routes = ref<Config>({
    url: location.href,
    routes: {},
    defaults: {}
})

axios.get('/api/routes')
    .then(response => {
        routes.value = response.data
    })

// @ts-ignore
export default (name?: string, params?: RouteParamsWithQueryOverload, absolute?: boolean) => route(name, params, absolute, routes.value)
