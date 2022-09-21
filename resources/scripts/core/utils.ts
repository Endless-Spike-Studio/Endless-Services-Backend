import {computed, ComputedRef, ref} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {get} from "lodash-es";
import route from "@/scripts/core/route";

export function useProp<TValue = unknown>(key: string, defaultValue?: any): ComputedRef<TValue> {
    return computed(() => {
        const $page = usePage();
        const props = $page.props.value;
        return get(props, key, defaultValue);
    });
}

export const isMobile = (() => {
    const reference = ref();

    function update() {
        reference.value = screen.width <= 768;
    }

    update();
    window.addEventListener('resize', update);

    return reference;
})();

export function visit(url: string) {
    if (url.match(location.host)) {
        return Inertia.visit(url);
    }

    location.replace(url);
}

export function visit_route(...args: unknown[]) {
    return visit(
        // @ts-ignore
        route(...args)
    );
}
