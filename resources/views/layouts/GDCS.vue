<script lang="ts" setup>
import CommonLayout from "@/views/components/CommonLayout.vue";
import {computed, h, reactive, ref} from "vue";
import {NIcon} from "naive-ui";
import {DashboardTwotone, HomeTwotone, ToolTwotone} from "@vicons/antd";
import {isMobile, useProductMenuOption, visit} from "@/scripts/core/utils";
import route from "@/scripts/core/route";
import {ExtraMenuOption} from "@/scripts/types/menu";
import {useGeometryDashChineseServerStore} from "@/scripts/core/stores";
import {User} from "@vicons/fa";
import {LogInTwotone} from "@vicons/material";

const GDCS = useGeometryDashChineseServerStore();

const menu = reactive({
    active: ref('unknown'),
    onupdate: function (_key: string, option: ExtraMenuOption) {
        return visit(route(option.route));
    },
    leftOptions: computed(() => {
        const currentModule = route()
            .current()
            ?.split('.')
            .at(1);

        if (currentModule !== undefined) {
            menu.active = currentModule;
        }

        return useProductMenuOption('GDCS', [
            {
                label: '主页',
                key: 'home',
                route: 'gdcs.home',
                icon: () => h(NIcon, {
                    component: HomeTwotone
                })
            },
            {
                label: 'Dashboard',
                key: 'dashboard',
                route: 'gdcs.dashboard.home',
                icon: () => h(NIcon, {
                    component: DashboardTwotone
                })
            },
            {
                label: '在线工具',
                key: 'tools',
                route: 'gdcs.tools.home',
                icon: () => h(NIcon, {
                    component: ToolTwotone
                })
            }
        ]);
    }),
    rightOptions: computed(() => {
        return [
            {
                label: GDCS.logged ? GDCS.account.name : '登录',
                key: 'auth',
                route: GDCS.logged ? 'gdcs.account.profile' : 'gdcs.auth.login',
                icon: () => h(NIcon, {
                    component: GDCS.logged ? User : LogInTwotone
                })
            }
        ]
    })
});
</script>

<template>
    <common-layout>
        <template #header>
            <n-space v-if="!isMobile" justify="space-between">
                <n-menu v-model:value="menu.active" :options="menu.leftOptions.value" mode="horizontal"
                        @update:value="menu.onupdate"/>

                <n-menu v-model:value="menu.active" :options="menu.rightOptions" mode="horizontal"
                        @update:value="menu.onupdate"/>
            </n-space>

            <n-menu v-else v-model:value="menu.active" :options="menu.leftOptions.value" @update:value="menu.onupdate"/>
        </template>

        <slot/>
    </common-layout>
</template>
