<script lang="ts" setup>
import CommonLayout from "@/views/components/CommonLayout.vue";
import {h, provide, reactive, ref} from "vue";
import {NIcon} from "naive-ui";
import {HomeTwotone} from "@vicons/antd";
import {ExtraMenuOption} from "@/scripts/types/menu";
import Menus from "@/views/components/Menus.vue";
import {visit_route} from "@/scripts/core/utils";

provide('product.name', 'GDProxy');

const options = {
    home: {
        label: '主页',
        key: 'home',
        onSelect: () => {
            visit_route('gdproxy.home')
        },
        icon: () => h(NIcon, {
            component: HomeTwotone
        })
    }
} as Record<string, ExtraMenuOption>;

const menu = reactive({
    active: ref('home'),
    options: {
        left: [
            options.home
        ],
        right: [],
        mobile: [
            options.home
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
