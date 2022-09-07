<script lang="ts" setup>
import {MenuOption, NImage} from "naive-ui";
import {isMobile, visit} from "@/scripts/core/utils";
import {computed, defineProps, h, inject, ref, watch} from "vue";
import Logo from "@/images/Logo.png";
import {products} from "@/scripts/core/client";
import {useAppStore} from "@/scripts/core/stores";
import {ExtraMenuOption} from "@/scripts/types/menu";
import route from "@/scripts/core/route";

const props = defineProps<{
    active: string;
    options: {
        left: MenuOption[];
        right: MenuOption[];
        mobile: MenuOption[];
    },
    onUpdate?: (key: string, option: ExtraMenuOption) => void;
}>();

const appStore = useAppStore();
const active = (() => {
    const reference = ref();

    function update() {
        reference.value = props.active;
    }

    update();
    watch(props, update);

    return reference;
})();

const processedOptions = computed(() => {
    const menu = Object.assign({}, props.options);
    const productName = inject<string>('product.name', 'unknown');

    const productSwitcherOption = {
        label: productName,
        key: 'product-switcher',
        icon: () => h(NImage, {
            src: Logo,
            imgProps: {
                class: 'w-full'
            }
        })
    } as MenuOption;

    const themeSwitcherOption = {
        label: () => appStore.theme === 'light' ? '深色' : '浅色',
        key: 'theme-switcher'
    } as MenuOption;

    if (isMobile.value) {
        menu.mobile.unshift(themeSwitcherOption);
        productSwitcherOption.children = menu.mobile;
        menu.mobile = [productSwitcherOption];
    } else {
        productSwitcherOption.children = products.filter(product => productName !== product.name)
            .map(product => {
                return {
                    label: product.name,
                    key: product.name.toLowerCase(),
                    route: product.route
                }
            });

        menu.left.unshift(productSwitcherOption);
        menu.right.unshift(themeSwitcherOption);
    }

    return menu;
});

function handleUpdate(key: string, option: ExtraMenuOption) {
    if (key === 'theme-switcher') {
        active.value = props.active;
        return appStore.toggleTheme();
    }

    active.value = key;
    if (option.route !== undefined) {
        return visit(
            route(option.route)
        );
    }

    return props.onUpdate?.(key, option);
}
</script>

<template>
    <n-space v-if="!isMobile" justify="space-between">
        <n-menu :options="processedOptions.left" :value="active" mode="horizontal" @update:value="handleUpdate"/>
        <n-menu :options="processedOptions.right" :value="active" mode="horizontal" @update:value="handleUpdate"/>
    </n-space>

    <n-menu v-else :options="processedOptions.mobile" :value="active" @update:value="handleUpdate"/>
</template>
