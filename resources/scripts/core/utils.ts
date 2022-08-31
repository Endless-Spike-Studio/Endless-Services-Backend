import {computed, ComputedRef} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

export function useProp<TValue = unknown>(key: string, defaultValue?: any): ComputedRef<TValue> {
    return computed(() => usePage().props.value[key] || defaultValue);
}
