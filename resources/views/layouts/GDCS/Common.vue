<script lang="ts" setup>
import CommonWrapper from "@/views/layouts/CommonWrapper.vue";
import {MenuOption, NIcon, NImage} from "naive-ui";
import {DashboardTwotone, HomeTwotone, ProfileTwotone, ToolTwotone, UserOutlined} from "@vicons/antd";
import Logo from "@/images/logo.png";
import {LogInTwotone, LogOutTwotone} from "@vicons/material";
import {to_route} from "@/scripts/core/utils";
import route, {routes} from "@/scripts/core/route";
import {useApiStore, useBackendStore} from "@/scripts/core/stores";
import {useWindowSize, watchOnce} from "@vueuse/core";
import {Inertia} from "@inertiajs/inertia";
import {ComputedRef} from "vue";
import {first} from "lodash-es";

const backendStore = useBackendStore();

function to_home(routeName = 'gdcs.home') {
    const apiStore = useApiStore();

    menu.active = 'home';
    if (route().current(routeName)) {
        return apiStore.$message.error('别点了 你已经在首页了');
    }

    return to_route(routeName);
}

const menu = reactive({
    active: (() => {
        const reference = ref();

        function _update() {
            if (route().current('*.profile')) {
                return reference.value = 'profile';
            }

            reference.value = route().current()?.split('.').at(1)?.trim();
        }

        watchOnce(routes, _update);
        Inertia.on('finish', _update);

        return reference;
    })(),
    options: {
        left: [
            {
                title: 'GDCS',
                key: 'logo',
                icon: () => h(NImage, {
                    src: Logo,
                    imgProps: {
                        class: 'w-full'
                    },
                    class: 'rounded-sm',
                    previewDisabled: true
                }),
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
            },
            {
                title: 'Dashboard',
                key: 'dashboard',
                icon: () => h(NIcon, {
                    component: DashboardTwotone
                }),
                onSelect() {
                    return to_route('gdcs.dashboard.home');
                }
            },
            {
                title: '在线工具',
                key: 'tools',
                icon: () => h(NIcon, {
                    component: ToolTwotone
                }),
                onSelect() {
                    return to_route('gdcs.tools.home');
                }
            }
        ],
        right: computed(() => {
            const items = [];

            if (!backendStore.gdcs.account) {
                items.push({
                    title: '登录',
                    key: 'auth',
                    icon: () => h(NIcon, {
                        component: LogInTwotone
                    }),
                    onSelect() {
                        return to_route('gdcs.auth.login');
                    }
                });
            } else {
                items.push({
                    title: backendStore.gdcs.account.name,
                    key: 'auth',
                    icon: () => h(NIcon, {
                        component: UserOutlined
                    }),
                    children: [
                        {
                            title: '个人资料',
                            key: 'profile',
                            icon: () => h(NIcon, {
                                component: ProfileTwotone
                            }),
                            onSelect() {
                                return to_route('gdcs.dashboard.account.profile');
                            }
                        },
                        {
                            title: '登出',
                            key: 'logout',
                            icon: () => h(NIcon, {
                                component: LogOutTwotone
                            }),
                            onSelect() {
                                return Inertia.post(route('gdcs.auth.logout.api'));
                            }
                        }
                    ]
                });
            }

            return items;
        }),
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
                        ...menu.options.center,
                        ...menu.options.right
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

                        <n-menu v-model:value="menu.active" :options="menu.options.center" mode="horizontal"
                                @update:value="handleMenuSelect"/>

                        <n-menu v-model:value="menu.active" :options="menu.options.right" mode="horizontal"
                                @update:value="handleMenuSelect"/>
                    </n-space>

                    <n-el v-else>
                        <n-menu v-model:value="menu.active" :options="menu.options.mobile" mode="vertical"
                                @update:value="handleMenuSelect"/>
                    </n-el>
                </slot>
            </n-layout-header>

            <n-layout-content class="mb-16">
                <slot/>
            </n-layout-content>

            <n-layout-footer class="p-2.5 mx-auto text-center" position="absolute">
                <slot name="footer">
                    <n-text>&copy; 2022 - {{ new Date().getFullYear() }}</n-text>
                    <n-divider vertical/>
                    <n-button text @click="to_home()">GDCS</n-button>
                    <n-divider vertical/>
                    <n-button href="https://beian.miit.gov.cn" tag="a" text>吉ICP备18006293号</n-button>
                </slot>
            </n-layout-footer>
        </n-layout>
    </CommonWrapper>
</template>
