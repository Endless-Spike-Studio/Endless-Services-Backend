<script lang="ts" setup>
import CommonWrapper from "@/views/layouts/CommonWrapper.vue";
import {MenuOption, NIcon} from "naive-ui";
import {HomeTwotone} from "@vicons/antd";
import {to_route} from "@/scripts/core/utils";
import route from "@/scripts/core/route";
import {useApiStore, useBackendStore} from "@/scripts/core/stores";
import {useWindowSize} from "@vueuse/core";
import {ComputedRef} from "vue";
import {first} from "lodash-es";

const backendStore = useBackendStore();

function to_home(routeName = 'gdproxy.home') {
    const apiStore = useApiStore();

    menu.active = 'home';
    if (route().current(routeName)) {
        return apiStore.$message.info('别点了 你已经在首页了');
    }

    return to_route(routeName);
}

const menu = reactive({
    active: ref('home'),
    options: {
        left: [
            {
                title: 'GDProxy',
                key: 'logo',
                onSelect: to_home
            }
        ],
        center: [
            {
                title: '主页',
                key: 'home',
                icon: () => h(NIcon, {
                    component: HomeTwotone
                }),
                onSelect: to_home
            }
        ],
        mobile: computed(() => {
            let _ = false;

            return [
                {
                    ...first(menu.options.left),
                    children: [
                        ...menu.options.left.filter(() => {
                            if (_) {
                                return true;
                            }

                            _ = true;
                            return false;
                        }),
                        ...menu.options.center
                    ]
                }
            ];
        })
    } as Record<string, ComputedRef<MenuOption[]> | MenuOption[]>
});

function handleMenuSelect(_key: string, option: MenuOption) {
    (option.onSelect as () => unknown)?.();
}

const {width} = useWindowSize();
</script>

<template>
    <CommonWrapper>
        <n-layout class="h-full">
            <n-layout-header>
                <slot name="header">
                    <n-space v-if="width > 768" justify="space-between">
                        <n-menu v-model:value="menu.active" :options="menu.options.left" mode="horizontal"
                                @update:value="handleMenuSelect"/>

                        <n-menu v-model:value="menu.active" :options="menu.options.center" class="translate-x-[-50%]"
                                mode="horizontal"
                                @update:value="handleMenuSelect"/>

                        <n-el/>
                    </n-space>

                    <n-el v-else>
                        <n-menu v-model:value="menu.active" :options="menu.options.mobile" mode="vertical"
                                @update:value="handleMenuSelect"/>
                    </n-el>
                </slot>
            </n-layout-header>

            <n-layout-content class="lg:w-3/5 lg:mx-auto mx-2.5 mt-2.5 mb-16">
                <slot/>
            </n-layout-content>

            <n-layout-footer class="p-2.5 mx-auto text-center" position="absolute">
                <slot name="footer">
                    <n-text>&copy; 2022 - {{ new Date().getFullYear() }}</n-text>
                    <n-divider vertical/>
                    <n-button text @click="to_home">GDProxy</n-button>
                    <n-divider vertical/>
                    <n-button href="https://beian.miit.gov.cn" tag="a" text>吉ICP备18006293号</n-button>
                </slot>
            </n-layout-footer>
        </n-layout>
    </CommonWrapper>
</template>
