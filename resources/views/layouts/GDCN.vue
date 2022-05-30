<script lang="ts" setup>
import {
    NConfigProvider,
    NDialogProvider,
    NLayout,
    NLayoutContent,
    NLayoutFooter,
    NLayoutHeader,
    NMessageProvider,
    NSpace
} from "naive-ui";
import {computed, h} from "vue";
import {DashboardTwotone, HomeTwotone, LoginOutlined, ProfileTwotone, ToolTwotone, UserOutlined} from "@vicons/antd";
import Logo from "@/images/Logo.png";
import {getProp, renderIcon, theme} from "@/scripts/helpers";
import BackendMessageReceiver from "@/views/components/BackendMessageReceiver.vue";
import LayoutHeader from "@/views/components/LayoutHeader.vue";
import LayoutFooter from "@/views/components/LayoutFooter.vue";
import {User} from "@/scripts/types/backend";

const user = getProp<User>('user');

const options = {
    logo: {
        label: 'Geometry Dash Chinese',
        key: 'home',
        icon: () => h('img', {src: Logo})
    },
    home: {
        label: '主页',
        key: 'home',
        icon: renderIcon(HomeTwotone)
    },
    dashboard: {
        label: 'Dashboard',
        key: 'dashboard.home',
        icon: renderIcon(DashboardTwotone)
    },
    tools: {
        label: 'Tools',
        key: 'tools.home',
        icon: renderIcon(ToolTwotone)
    },
    login: {
        label: '登录',
        key: 'login',
        icon: renderIcon(LoginOutlined)
    },
    user: {
        label: () => user.value.name,
        key: 'user',
        icon: renderIcon(UserOutlined),
        children: [
            {
                label: '账号资料',
                key: 'user.profile',
                icon: renderIcon(ProfileTwotone)
            },
            {
                label: '登出',
                key: 'user.logout.api',
                icon: renderIcon(LoginOutlined)
            }
        ]
    }
};

const auth = computed(() => user.value ? options.user : options.login);
const menu = computed(
    () => ({
        mobile: [
            {
                ...options.logo,
                children: [
                    options.home,
                    options.dashboard,
                    options.tools,
                    auth.value
                ]
            }
        ],
        left: [
            options.logo,
            options.dashboard,
            options.tools
        ],
        right: [auth.value]
    })
);
</script>

<template>
    <n-config-provider :theme="theme" class="max-height">
        <n-dialog-provider>
            <n-message-provider>
                <backend-message-receiver/>

                <n-layout class="max-height">
                    <n-layout-header>
                        <layout-header v-bind:menu="menu"/>
                    </n-layout-header>

                    <n-layout-content :content-style="{ padding: '24px' }">
                        <n-space vertical>
                            <slot/>
                        </n-space>
                    </n-layout-content>

                    <n-layout-footer class="lg:text-center p-5" position="absolute">
                        <layout-footer long-text="Geometry Dash Chinese" short-text="GDCN"/>
                    </n-layout-footer>
                </n-layout>
            </n-message-provider>
        </n-dialog-provider>
    </n-config-provider>
</template>
