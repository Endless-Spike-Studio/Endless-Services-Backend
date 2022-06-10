<script lang="ts" setup>
import {computed, h} from "vue";
import {HomeTwotone} from "@vicons/antd";
import Logo from "@/images/Logo.png";
import {renderIcon} from "@/scripts/helpers";
import CommonLayout from "@/views/components/CommonLayout.vue";

const options = {
    logo: {
        label: 'Newgrounds Proxy',
        key: 'ngproxy.home',
        icon: () => h('img', {
            class: 'w-full hue-rotate-[270deg]',
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

const footer = {
    short: 'NGProxy',
    long: 'Newgrounds Proxy'
}
</script>

<template>
    <common-layout :footer="footer" :menu="menu">
        <slot/>
    </common-layout>
</template>
