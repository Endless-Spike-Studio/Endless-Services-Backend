import {computed, ComputedRef, h, ref} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {MenuOption, NImage} from "naive-ui";
import {products} from "@/scripts/core/client";
import Logo from "@/images/Logo.png";
import {ExtraMenuOption} from "@/scripts/types/menu";
import {Inertia} from "@inertiajs/inertia";

export function useProp<TValue = unknown>(key: string, defaultValue?: any): ComputedRef<TValue> {
    return computed(() => usePage().props.value[key] || defaultValue);
}

export const isMobile = (function () {
    const reference = ref();

    function update() {
        reference.value = screen.width <= 768;
    }

    update();
    window.addEventListener('resize', update);

    return reference;
})();

export function useProductMenuOption(name: string, items: ExtraMenuOption[]) {
    return computed(() => {
        const result = new Array<MenuOption>();

        const item = {
            label: name,
            key: 'product',
            icon: () => h(NImage, {
                src: Logo,
                imgProps: {
                    class: 'w-full'
                }
            })
        } as MenuOption;

        if (!isMobile.value) {
            item.children = products.filter(product => product.name !== name)
                .map(product => {
                    return {
                        label: product.name,
                        key: product.name.toLowerCase(),
                        route: product.route
                    }
                });

            const processedItems = items.filter(item => {
                return item.mobileOnly !== true;
            });

            result.push(...processedItems);
        } else {
            item.children = items;
        }

        result.unshift(item);
        return result;
    });
}

export function visit(url: string) {
    if (url.match(location.host)) {
        return Inertia.visit(url);
    }

    location.href = url;
}
