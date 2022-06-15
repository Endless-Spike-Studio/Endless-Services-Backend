<script lang="ts" setup>
import {computed, h} from "vue";
import {
    BookTwotone,
    DashboardTwotone,
    HomeTwotone,
    LoginOutlined,
    ProfileTwotone,
    ToolTwotone,
    UserOutlined
} from "@vicons/antd";
import Logo from "@/images/Logo.png";
import {getProp, renderIcon} from "@/scripts/helpers";
import {User} from "@/scripts/types/backend";
import CommonLayout from "@/views/components/CommonLayout.vue";
import {NImage} from "naive-ui";

const account = getProp<User>('gdcs.account');

const options = {
    logo: {
        label: 'Geometry Dash Chinese Server',
        key: 'gdcs.home',
        icon: () => h(NImage, {
            imgProps: {
                class: 'w-full'
            },
            src: Logo
        })
    },
    home: {
        label: '主页',
        key: 'gdcs.home',
        icon: renderIcon(HomeTwotone)
    },
    dashboard: {
        label: 'Dashboard',
        key: 'gdcs.dashboard.home',
        icon: renderIcon(DashboardTwotone)
    },
    tools: {
        label: '在线工具',
        key: 'gdcs.tools.home',
        icon: renderIcon(ToolTwotone)
    },
    docs: {
        label: '文档',
        key: 'gdcs.docs.home',
        icon: renderIcon(BookTwotone)
    },
    login: {
        label: '登录',
        key: 'gdcs.login',
        icon: renderIcon(LoginOutlined)
    },
    account: {
        label: () => account.value?.name,
        key: 'account',
        icon: renderIcon(UserOutlined),
        children: [
            {
                label: '账号资料',
                key: 'gdcs.account.profile',
                icon: renderIcon(ProfileTwotone)
            },
            {
                label: '登出',
                key: 'gdcs.account.logout.api',
                icon: renderIcon(LoginOutlined)
            }
        ]
    }
};

const auth = computed(() => account.value ? options.account : options.login);
const menu = computed(
    () => ({
        mobile: [
            {
                ...options.logo,
                children: [
                    options.home,
                    options.dashboard,
                    options.tools,
                    options.docs,
                    auth.value
                ]
            }
        ],
        left: [
            options.logo,
            options.dashboard,
            options.tools,
            options.docs
        ],
        right: [auth.value]
    })
);

const footer = {
    short: 'GDCS',
    long: 'Geometry Dash Chinese Server'
}
</script>

<template>
    <common-layout :footer="footer" :menu="menu">
        <slot/>
    </common-layout>
</template>
