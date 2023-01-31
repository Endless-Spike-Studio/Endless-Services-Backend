import {ArgumentsType, useClipboard, useWindowSize} from "@vueuse/core";
import {computed} from "vue";
import {router, useForm, usePage} from "@inertiajs/vue3";
import route, {RouteParam, RouteParamsWithQueryOverload} from "ziggy-js";
import {get} from "lodash-es";
import {FormItemRule} from "naive-ui";
import {useApiStore} from "@/scripts/core/stores";
import servers from "@/shared/servers.json";

export const isMobile = (() => {
    const _window = useWindowSize();

    return computed(() => {
        return _window.width.value <= 640;
    });
})();

export function useProp<T extends unknown>(key: string, defaultValue?: T) {
    return computed(() => {
        const $page = usePage();
        return get($page.props, key, defaultValue) as T;
    });
}

export function to_route(name: string, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean, options?: ArgumentsType<typeof router['visit']>[1]) {
    const targetURL = route(name, params, absolute);

    if (new URL(location.href).hostname !== new URL(targetURL).hostname) {
        return open(targetURL);
    }

    router.visit(targetURL, options);
}

export function formatTime(_: string, defaultValue = '未知') {
    return new Date(_).toLocaleString() ?? defaultValue;
}

export function guessServer(address: string, defaultValue?: string) {
    return servers.find(_ => address === _.address)?.name ?? defaultValue ?? address;
}

export function createRules<T extends Record<string, unknown>>(form: ReturnType<typeof useForm<T>>, items?: Record<keyof T, FormItemRule[]>) {
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

    router.on('finish', _update);
    _update();

    return reference;
}

export function to_home(routeName: string) {
    if (route().current() === routeName) {
        return useApiStore().$message.info('别点了 你已经在首页了');
    }

    return to_route(routeName);
}
