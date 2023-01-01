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

export function createRange(i: number) {
    const result = new Array<number>;

    for (let j = 0; j < i; j++) {
        result.push(j);
    }

    return result;
}

export function guessDifficultyNameFromStars(stars: number) {
    switch (stars) {
        case 1:
            return 'Auto';
        case 2:
            return 'Easy';
        case 3:
            return 'Normal';
        case 4:
        case 5:
            return 'Hard';
        case 6:
        case 7:
            return 'Harder';
        case 8:
        case 9:
            return 'Insane';
        case 10:
            return 'Demon';
        default:
            return 'N/A';
    }
}
