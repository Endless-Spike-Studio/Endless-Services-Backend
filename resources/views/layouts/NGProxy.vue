<script lang="ts" setup>
import {
    NConfigProvider,
    NLayout,
    NLayoutContent,
    NLayoutFooter,
    NLayoutHeader,
    NMessageProvider,
    NSpace
} from "naive-ui";
import {computed, h} from "vue";
import {HomeTwotone} from "@vicons/antd";
import Logo from "@/images/Logo.png";
import BackendMessageReceiver from "@/views/components/BackendMessageReceiver.vue";
import LayoutHeader from "@/views/components/LayoutHeader.vue";
import LayoutFooter from "@/views/components/LayoutFooter.vue";
import {renderIcon, theme} from "@/scripts/helpers";

const options = {
    logo: {
        label: 'Newgrounds Proxy',
        key: 'ngproxy.home',
        icon: () => h('img', {
            style: {
                filter: 'hue-rotate(270deg)'
            },
            src: Logo
        })
    },
    home: {
        label: '主页',
        key: 'ngproxy.home',
        icon: renderIcon(HomeTwotone)
    }
};

const menu = computed(
    () => ({
        mobile: [
            {
                ...options.logo,
                children: [options.home]
            }
        ],
        left: [
            options.logo
        ],
        right: []
    })
);
</script>

<template>
    <n-config-provider :theme="theme" class="max-height">
        <n-message-provider>
            <backend-message-receiver/>

            <n-layout class="max-height">
                <n-layout-header>
                    <layout-header :menu="menu"/>
                </n-layout-header>

                <n-layout-content :content-style="{ padding: '24px' }">
                    <n-space vertical>
                        <slot/>
                    </n-space>
                </n-layout-content>

                <n-layout-footer class="lg:text-center p-5" position="absolute">
                    <layout-footer long-text="Newgrounds Proxy" short-text="NGProxy"/>
                </n-layout-footer>
            </n-layout>
        </n-message-provider>
    </n-config-provider>
</template>
