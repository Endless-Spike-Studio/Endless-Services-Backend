<script lang="ts" setup>
import CommonLayout from "@/views/components/CommonLayout.vue";
import {computed, h, provide, reactive, ref} from "vue";
import {NIcon} from "naive-ui";
import {
    DashboardTwotone,
    HomeTwotone,
    LoginOutlined,
    LogoutOutlined,
    ProfileTwotone,
    ToolTwotone,
    UserOutlined
} from "@vicons/antd";
import route from "@/scripts/core/route";
import {ExtraMenuOption} from "@/scripts/types/menu";
import {useGeometryDashChineseServerStore} from "@/scripts/core/stores";
import Menus from "@/views/components/Menus.vue";
import event from "@/scripts/core/event";
import {Inertia} from "@inertiajs/inertia";

const GDCS = useGeometryDashChineseServerStore();
provide('product.name', 'GDCS');

const options = {
    home: {
        label: '主页',
        key: 'home',
        route: 'gdcs.home',
        icon: () => h(NIcon, {
            component: HomeTwotone
        })
    },
    dashboard: {
        label: 'Dashboard',
        key: 'dashboard',
        route: 'gdcs.dashboard.home',
        icon: () => h(NIcon, {
            component: DashboardTwotone
        })
    },
    tools: {
        label: '在线工具',
        key: 'tools',
        route: 'gdcs.tools.home',
        icon: () => h(NIcon, {
            component: ToolTwotone
        })
    },
    login: {
        label: '登录',
        key: 'auth',
        route: 'gdcs.auth.login',
        icon: () => h(NIcon, {
            component: LoginOutlined
        })
    },
    account: {
        label: () => GDCS.account.name,
        key: 'account',
        icon: () => h(NIcon, {
            component: UserOutlined
        }),
        children: [
            {
                label: '个人资料',
                key: 'account.profile',
                route: 'gdcs.dashboard.account.profile',
                icon: () => h(NIcon, {
                    component: ProfileTwotone
                })
            },
            {
                label: '登出',
                key: 'account.logout',
                route: 'gdcs.auth.logout.api',
                icon: () => h(NIcon, {
                    component: LogoutOutlined
                })
            }
        ] as ExtraMenuOption[]
    }
} as Record<string, ExtraMenuOption>;

const menu = reactive({
    active: (() => {
        const reference = ref<string | undefined>('unknown');

        function update() {
            const router = route();
            const currentRoute = router.current();

            if (currentRoute !== undefined) {
                reference.value = currentRoute.split('.').at(1) || 'unknown';
            }
        }

        update();
        event.once('routes.loaded', update);
        Inertia.on('finish', update);

        return reference;
    })(),
    options: computed(() => {
        const auth = computed(() => GDCS.logged ? options.account : options.login)

        return {
            left: [
                options.home,
                options.dashboard,
                options.tools
            ],
            right: [
                auth.value
            ],
            mobile: [
                options.home,
                options.dashboard,
                options.tools,
                auth.value
            ]
        };
    })
});
</script>

<template>
    <common-layout>
        <template #header>
            <menus :active="menu.active" :options="menu.options"/>
        </template>

        <slot/>
    </common-layout>
</template>
