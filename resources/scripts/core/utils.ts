import {computed, ComputedRef} from "vue";
import {InertiaForm, usePage} from "@inertiajs/inertia-vue3";
import {RouteParam, RouteParamsWithQueryOverload} from "ziggy-js";
import {Inertia, VisitOptions} from "@inertiajs/inertia";
import route, {routes} from "@/scripts/core/route";
import {find, get} from "lodash-es";
import {FormItemRule} from "naive-ui";
import {useClipboard, useWindowSize, watchOnce} from "@vueuse/core";
import {useApiStore} from "@/scripts/core/stores";
import servers from "@/shared/servers.json";

export const isMobile = (() => {
    const _window = useWindowSize();

    return computed(() => {
        return _window.width.value <= 640;
    });
})();

export function useProp<T extends unknown>(key: string, defaultValue?: T): ComputedRef<T> {
    const $page = usePage();

    return computed(() => {
        return get($page.props.value, key, defaultValue) as T;
    });
}

export function to_route(name: string, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean, options?: VisitOptions) {
    const targetURL = route(name, params, absolute);

    if (new URL(location.href).hostname !== new URL(targetURL).hostname) {
        return open(targetURL);
    }

    Inertia.visit(targetURL, options);
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
                    if (value === '') {
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

export function copy(text: string) {
    const apiStore = useApiStore();

    useClipboard({
        source: ref(text),
        legacy: true
    }).copy();

    apiStore.$message.success('复制成功!');
}

export function useCurrentModule(extraUpdater?: () => string | undefined) {
    const reference = ref();

    function _update() {
        if (extraUpdater !== undefined) {
            const result = extraUpdater();

            if (result !== undefined) {
                reference.value = result;
                return;
            }
        }

        reference.value = route().current()
            ?.split('.')
            .at(1)
            ?.trim();
    }

    Inertia.on('finish', _update);
    watchOnce(routes, _update);

    return reference;
}

export function to_home(routeName: string) {
    if (route().current() === routeName) {
        return useApiStore().$message.info('别点了 你已经在首页了');
    }

    return to_route(routeName);
}
