<script lang="ts" setup>
import CommonWrapper from "@/views/layouts/CommonWrapper.vue";
import {MenuOption} from "naive-ui";
import {isMobile, to_home, useCurrentModule, useProp} from "@/scripts/core/utils";
import {ArgumentsType} from "@vueuse/core";
import {first} from "lodash-es";
import {Moon, Sun} from "@vicons/tabler";
import {useAppStore} from "@/scripts/core/stores";
import {Laravel, Php} from "@vicons/fa";
import route from "ziggy-js";
import {CommitTwotone} from "@vicons/material";

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

const appStore = useAppStore();
const _i = useProp('754a08ddf8bcb1cf22f310f09206dd783d42f7dd');
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

            <n-layout-content class="sm:w-3/5 sm:mx-auto mx-2.5 mt-2.5 mb-44 sm:mb-20">
                <slot/>
            </n-layout-content>

            <n-layout-footer class="p-2.5" position="absolute">
                <slot name="footer">
                    <n-grid :cols="isMobile ? 1 : 3" :x-gap="10" :y-gap="10" class="[&>*]:mx-auto [&>*]:sm:!mx-0">
                        <n-grid-item>
                            <n-button text @click="appStore.switchTheme">
                                <template #icon>
                                    <n-icon :component="appStore.theme === 'dark' ? Moon : Sun"/>
                                </template>

                                <n-text class="text-current">{{ appStore.theme === 'dark' ? '深色' : '浅色' }}</n-text>
                            </n-button>
                        </n-grid-item>

                        <n-grid-item class="sm:text-center">
                            <n-text>&copy; 2022 - {{ new Date().getFullYear() }}</n-text>

                            <n-divider vertical/>

                            <n-button text @click="to_home(route_home)">
                                {{ name }}
                            </n-button>

                            <n-divider vertical/>

                            <n-button href="https://beian.miit.gov.cn" tag="a" text>吉ICP备18006293号</n-button>
                        </n-grid-item>

                        <n-grid-item>
                            <n-space justify="end">
                                <n-button href="https://php.net" tag="a" text>
                                    <template #icon>
                                        <n-icon :component="Php"/>
                                    </template>

                                    {{ _i['47425e4490d1548713efea3b8a6f5d778e4b1766'] }}
                                </n-button>

                                <n-button href="https://laravel.com" tag="a" text>
                                    <template #icon>
                                        <n-icon :component="Laravel"/>
                                    </template>

                                    {{ _i['7b937a43bb67901cc0cb207e6bcf13a606611cf7'] }}
                                </n-button>

                                <n-button :href="route('github')" tag="a" text>
                                    <template #icon>
                                        <n-icon :component="CommitTwotone"/>
                                    </template>

                                    {{ _i['6aef87d005f444c7023bc154db6ec02428cd43b7'] }}
                                </n-button>
                            </n-space>
                        </n-grid-item>
                    </n-grid>
                </slot>
            </n-layout-footer>
        </n-layout>
    </CommonWrapper>
</template>
