<script lang="ts" setup>
import {darkTheme, NButton, NIcon, NImage} from "naive-ui";
import {DashboardTwotone, HomeTwotone, LoginOutlined, ToolTwotone} from "@vicons/antd";
import {isMobile} from "@/scripts/core/utils";
import Logo from "@/images/Logo.png";
import {SelectableMenuOption} from "@/types/components/menu";
import SelectableMenu from "@/views/components/SelectableMenu.vue";
import {router} from "hybridly";
import {route, RouteName} from "hybridly/vue";

const menu = reactive({
    active: ref('home'),
    options: computed(() => {
        function createProduct(name: string, routeName?: RouteName) {
            routeName ??= name.toLowerCase() + '.home' as RouteName;

            const link = route(routeName);
            const disabled = ref(false);

            function _update() {
                disabled.value = new URL(link).hostname === location.hostname;
            }

            _update();
            registerHook('after', _update);

            return {
                label: name,
                key: 'product.' + name.toLowerCase(),
                disabled: disabled.value,
                onSelect: () => router.external(link)
            } as SelectableMenuOption;
        }

        return {
            logo: [
                {
                    label: 'GDCN',
                    key: 'logo',
                    icon: () => h(NImage, {
                        imgProps: {
                            class: 'w-full'
                        },
                        previewDisabled: true,
                        src: Logo
                    }),
                    children: [
                        createProduct('GDCS'),
                        createProduct('GDProxy'),
                        createProduct('NGProxy')
                    ]
                }
            ],
            navigation: [
                {
                    label: '主页',
                    key: 'home',
                    icon: () => h(NIcon, {
                        component: HomeTwotone
                    }),
                    onSelect: () => router.get(route('gdcs.home'))
                },
                {
                    label: 'Dashboard',
                    key: 'dashboard',
                    icon: () => h(NIcon, {
                        component: DashboardTwotone
                    })
                },
                {
                    label: '在线工具',
                    key: 'tools',
                    icon: () => h(NIcon, {
                        component: ToolTwotone
                    })
                }
            ],
            user: [
                {
                    label: '登录',
                    key: 'login',
                    icon: () => h(NIcon, {
                        component: LoginOutlined
                    })
                }
            ]
        } as Record<'logo' | 'navigation' | 'user', SelectableMenuOption[]>;
    })
});

const theme = ref(darkTheme);
</script>

<template>
    <n-config-provider :theme="theme" class="h-full">
        <n-layout class="h-full">
            <n-layout-header>
                <n-space v-if="isMobile" vertical>
                    <n-space justify="space-between">
                        <SelectableMenu :items="menu.options.logo" mode="horizontal"/>
                        <SelectableMenu v-model:value="menu.active" :items="menu.options.user" mode="horizontal"/>
                    </n-space>

                    <n-space justify="center">
                        <SelectableMenu v-model:value="menu.active" :items="menu.options.navigation" mode="horizontal"/>
                    </n-space>
                </n-space>

                <n-space v-else justify="space-evenly">
                    <SelectableMenu :items="menu.options.logo" mode="horizontal"/>
                    <SelectableMenu v-model:value="menu.active" :items="menu.options.navigation" mode="horizontal"/>
                    <SelectableMenu v-model:value="menu.active" :items="menu.options.user" mode="horizontal"/>
                </n-space>
            </n-layout-header>

            <n-layout-content class="p-2.5">
                <slot/>
            </n-layout-content>

            <n-layout-footer class="p-2.5 text-center" position="absolute">
                &copy; 2022 - {{ new Date().getFullYear() }}
                <n-divider vertical/>
                <n-button href="https://geometrydashchinese.com" tag="a" text>GDCN</n-button>
                <n-divider vertical/>
                <n-button href="https://beian.miit.gov.cn" tag="a" text>吉ICP备18006293号</n-button>
            </n-layout-footer>
        </n-layout>
    </n-config-provider>
</template>
