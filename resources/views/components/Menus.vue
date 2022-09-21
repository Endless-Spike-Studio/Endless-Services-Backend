<script lang="ts" setup>
import {MenuOption, NImage} from "naive-ui";
import {isMobile} from "@/scripts/core/utils";
import {defineProps, h, inject, ref, watch} from "vue";
import Logo from "@/images/Logo.png";
import {products} from "@/scripts/core/client";
import {useAppStore} from "@/scripts/core/stores";
import {ExtraMenuOption, Menus} from "@/scripts/types/menu";
import {cloneDeep} from "lodash-es";

const props = defineProps<{
    active: string;
    options: Menus,
    onUpdate?: (key: string, option: ExtraMenuOption) => void;
}>();

const appStore = useAppStore();
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

const active = (() => {
    const reference = ref();

    function update() {
        reference.value = props.active;
    }

    update();
    watch(props, update);

    return reference;
})();

const processedOptions = (() => {
    const reference = ref();

    function update() {
        const productSwitcher = cloneDeep(productSwitcherOption);
        const items = cloneDeep(props.options);

        if (isMobile.value) {
            items.mobile.unshift(themeSwitcherOption);
            productSwitcher.children = items.mobile;
            items.mobile = [productSwitcher];
        } else {
            productSwitcher.children = products.filter(product => productName !== product.name)
                .map(product => {
                    return {
                        label: product.name,
                        key: product.name.toLowerCase(),
                        route: product.route
                    }
                });

            items.left.unshift(productSwitcher);
            items.right.unshift(themeSwitcherOption);
        }

        reference.value = items;
    }

    update();
    watch(props, update);

    return reference;
})();

function handleUpdate(key: string, option: ExtraMenuOption) {
    if (key === 'theme-switcher') {
        active.value = props.active;
        return appStore.toggleTheme();
    }

    active.value = key;
    if (option.onSelect !== undefined) {
        return option.onSelect();
    }

    return props.onUpdate?.(key, option);
}
</script>

<template>
    <n-space v-if="!isMobile" justify="space-between">
        <n-menu :options="processedOptions['left']" :value="active" mode="horizontal" @update:value="handleUpdate"/>
        <n-menu :options="processedOptions['right']" :value="active" mode="horizontal" @update:value="handleUpdate"/>
    </n-space>

    <n-menu v-else :options="processedOptions['mobile']" :value="active" @update:value="handleUpdate"/>
</template>
