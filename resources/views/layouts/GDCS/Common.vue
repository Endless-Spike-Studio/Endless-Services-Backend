<script lang="ts" setup>
import {MenuOption, NIcon, NImage} from "naive-ui";
import {
    DashboardTwotone,
    HomeTwotone,
    LoginOutlined,
    LogoutOutlined,
    ProfileTwotone,
    ToolTwotone,
    UserOutlined
} from "@vicons/antd";
import Logo from "@/images/logo.png";
import {to_home, to_route} from "@/scripts/core/utils";
import route from "@/scripts/core/route";
import {useBackendStore} from "@/scripts/core/stores";
import {Inertia} from "@inertiajs/inertia";
import CommonLayout from "@/views/layouts/CommonLayout.vue";

const route_home = 'gdcs.home';

const menu = {
    activeStateExtraUpdater() {
        if (route().current('*.profile')) {
            return 'profile';
        }
    },
    left(): MenuOption[] {
        return [
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
                onSelect: () => to_home(route_home)
            }
        ];
    },
    center(): MenuOption[] {
        return [
            {
                title: '主页',
                key: 'home',
                icon: () => h(NIcon, {
                    component: HomeTwotone
                }),
                onSelect() {
                    to_home(route_home)
                }
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
        ];
    },
    right(): MenuOption[] {
        const backendStore = useBackendStore();

        return [
            backendStore.gdcs.account ? {
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
                            return to_route('gdcs.dashboard.account.info', backendStore.gdcs.account.id);
                        }
                    },
                    {
                        title: '登出',
                        key: 'logout',
                        icon: () => h(NIcon, {
                            component: LogoutOutlined
                        }),
                        onSelect() {
                            return Inertia.post(route('gdcs.auth.logout.api'));
                        }
                    }
                ]
            } : {
                title: '登录',
                key: 'auth',
                icon: () => h(NIcon, {
                    component: LoginOutlined
                }),
                onSelect() {
                    return to_route('gdcs.auth.login');
                }
            }
        ];
    }
};
</script>

<template>
    <CommonLayout :menu="menu" :route_home="route_home" name="GDCS">
        <slot/>
    </CommonLayout>
</template>
