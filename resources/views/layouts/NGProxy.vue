<script lang="ts" setup>
import CommonLayout from "@/views/components/CommonLayout.vue";
import {h, provide, reactive, ref} from "vue";
import {NIcon} from "naive-ui";
import {ApiTwotone, HomeTwotone} from "@vicons/antd";
import {ExtraMenuOption} from "@/scripts/types/menu";
import Menus from "@/views/components/Menus.vue";

provide('product.name', 'NGProxy');

const options = {
    home: {
        label: '主页',
        key: 'home',
        route: 'ngproxy.home',
        icon: () => h(NIcon, {
            component: HomeTwotone
        })
    },
    api: {
        label: 'Api',
        key: 'api',
        url: 'https://docs.geometrydashchinese.com/NGProxy/api',
        icon: () => h(NIcon, {
            component: ApiTwotone
        })
    }
} as Record<string, ExtraMenuOption>;

const menu = reactive({
    active: ref('home'),
    options: {
        left: [
            options.home,
            options.api
        ],
        right: [],
        mobile: [
            options.home,
            options.api
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
