<script lang="ts" setup>
import CommonWrapper from "@/views/layouts/CommonWrapper.vue";
import {MenuOption} from "naive-ui";
import {isMobile, to_home, useCurrentModule} from "@/scripts/core/utils";
import {ArgumentsType} from "@vueuse/core";
import {first} from "lodash-es";

const props = defineProps<{
    name: string
    route_home: string
    menu: {
        activeStateExtraUpdater?: ArgumentsType<typeof useCurrentModule>[0]
        left?: () => MenuOption[]
        center?: () => MenuOption[]
        right?: () => MenuOption[]
    }
}>();

const active = useCurrentModule(props.menu.activeStateExtraUpdater);

function getMobileMenuOptions() {
    const left = props.menu.left?.() ?? [];

    return [
        {
            ...first(left),
            children: [
                ...left.slice(1),
                ...props.menu.center?.() ?? [],
                ...props.menu.right?.() ?? []
            ]
        }
    ];
}

function handleMenuSelect(_key: string, option: MenuOption) {
    (option.onSelect as () => unknown)?.();
}
</script>

<template>
    <CommonWrapper>
        <n-layout class="h-full">
            <n-layout-header>
                <slot name="header">
                    <n-el v-if="isMobile">
                        <n-menu v-model:value="active" :options="getMobileMenuOptions()" mode="vertical"
                                @update:value="handleMenuSelect"/>
                    </n-el>

                    <n-space v-else justify="space-between">
                        <n-menu v-model:value="active" :options="menu.left?.() ?? []" mode="horizontal"
                                @update:value="handleMenuSelect"/>

                        <n-menu v-model:value="active" :options="menu.center?.() ?? []" mode="horizontal"
                                @update:value="handleMenuSelect"/>

                        <n-menu v-model:value="active" :options="menu.right?.() ?? []" class="min-w-[100px]"
                                mode="horizontal"
                                @update:value="handleMenuSelect"/>
                    </n-space>
                </slot>
            </n-layout-header>

            <n-layout-content class="lg:w-3/5 lg:mx-auto mx-2.5 mt-2.5 mb-16">
                <slot/>
            </n-layout-content>

            <n-layout-footer class="p-2.5 text-center" position="absolute">
                <slot name="footer">
                    <n-text>&copy; 2022 - {{ new Date().getFullYear() }}</n-text>

                    <n-divider vertical/>

                    <n-button text @click="to_home(route_home)">
                        {{ name }}
                    </n-button>

                    <n-divider vertical/>

                    <n-button href="https://beian.miit.gov.cn" tag="a" text>吉ICP备18006293号</n-button>
                </slot>
            </n-layout-footer>
        </n-layout>
    </CommonWrapper>
</template>
