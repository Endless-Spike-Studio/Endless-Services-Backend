import {computed, ComputedRef} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {RouteParam, RouteParamsWithQueryOverload} from "ziggy-js";
import {Inertia, VisitOptions} from "@inertiajs/inertia";
import route from "@/scripts/core/route";
import {get} from "lodash-es";

export function useProp<T = unknown>(key: string, defaultValue?: T): ComputedRef<T> {
    const $page = usePage();

    return computed(() => {
        return get($page.props.value, key, defaultValue) as T;
    });
}

export function to_route(name: string, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean, options?: VisitOptions) {
    Inertia.visit(route(name, params, absolute), options);
}

export function formatTime(_: string, defaultValue = '未知') {
    return new Date(_).toLocaleString() ?? defaultValue;
}
