<script lang="ts" setup>
import {computed, h} from "vue";
import {DashboardTwotone, HomeTwotone, LoginOutlined, ProfileTwotone, ToolTwotone, UserOutlined} from "@vicons/antd";
import Logo from "@/images/Logo.png";
import {getProp, renderIcon} from "@/scripts/helpers";
import {User} from "@/scripts/types/backend";
import CommonLayout from "@/views/components/CommonLayout.vue";

const user = getProp<User>('gdcn.user');

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
    <common-layout :menu="menu">
        <slot/>
    </common-layout>
</template>
