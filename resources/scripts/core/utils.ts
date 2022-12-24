import {computed, ComputedRef} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

export function useProp<T = unknown>(key: string, defaultValue?: T): ComputedRef<T> {
    const $page = usePage();

    return computed(() => {
        return ($page.props.value[key] ?? defaultValue) as T;
    });
}
