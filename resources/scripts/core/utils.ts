import {computed, ComputedRef} from "vue";
import {InertiaForm, usePage} from "@inertiajs/inertia-vue3";
import {RouteParam, RouteParamsWithQueryOverload} from "ziggy-js";
import {Inertia, VisitOptions} from "@inertiajs/inertia";
import route from "@/scripts/core/route";
import {find, get} from "lodash-es";
import {servers} from "@/scripts/core/shared";
import {FormItemRule} from "naive-ui";

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

export function guessServer(address: string, defaultValue?: string) {
    return find(servers, {address})?.name ?? defaultValue ?? address;
}

export function createRules<T extends object>(form: InertiaForm<T>, items?: Record<keyof T, FormItemRule[]>) {
    return Object.keys(
        form.data()
    ).reduce((data, key) => {
        const _key = key as keyof T;

        data[_key] = [
            {
                required: true,
                validator(rule: FormItemRule, value: string) {
                    if (!value) {
                        return new Error(String(_key) + ` 不能为空`);
                    }

                    if (form.errors[_key]) {
                        return new Error(form.errors[_key]);
                    }

                    return true;
                }
            }
        ];

        if (items && items[_key]) {
            data[_key].push(...items[_key]);
        }

        return data;
    }, {} as Record<keyof T, FormItemRule[]>);
}
