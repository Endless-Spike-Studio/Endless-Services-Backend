<script lang="ts" setup>
import CommonLayout from "@/views/components/CommonLayout.vue";
import {computed, h, provide, reactive, ref} from "vue";
import {NIcon} from "naive-ui";
import {DashboardTwotone, HomeTwotone, ToolTwotone} from "@vicons/antd";
import route from "@/scripts/core/route";
import {ExtraMenuOptionMap} from "@/scripts/types/menu";
import {useAppStore, useGeometryDashChineseServerStore} from "@/scripts/core/stores";
import {User} from "@vicons/fa";
import {LogInTwotone} from "@vicons/material";
import Menus from "@/views/components/Menus.vue";
import event from "@/scripts/core/event";

const GDCS = useGeometryDashChineseServerStore();
const appStore = useAppStore();
provide('product.name', 'GDCS');

const options = reactive<ExtraMenuOptionMap>({
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
    auth: computed(() => {
        return {
            label: GDCS.logged ? GDCS.account.name : '登录',
            key: 'auth',
            route: GDCS.logged ? 'gdcs.dashboard.account.profile' : 'gdcs.auth.login',
            icon: () => h(NIcon, {
                component: GDCS.logged ? User : LogInTwotone
            })
        }
    }).value
});

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

        return reference;
    })(),
    options: {
        left: [
            options.home,
            options.dashboard,
            options.tools
        ],
        right: [
            options.auth
        ],
        mobile: [
            options.home,
            options.dashboard,
            options.tools,
            options.auth
        ]
    }
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
