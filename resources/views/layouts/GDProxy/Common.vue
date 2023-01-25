<script lang="ts" setup>
import {MenuOption, NIcon} from "naive-ui";
import {HomeTwotone} from "@vicons/antd";
import {to_home} from "@/scripts/core/utils";
import CommonLayout from "@/views/layouts/CommonLayout.vue";

const route_home = 'gdproxy.home';

const menu = {
    left(): MenuOption[] {
        return [
            {
                title: 'GDProxy',
                key: 'logo',
                onSelect() {
                    to_home(route_home)
                }
            }
        ];
    },
    center(): MenuOption[] {
        return [
            {
                title: '主页',
                key: 'home',
                icon: () => h(NIcon, {
                    component: HomeTwotone
                }),
                onSelect() {
                    to_home(route_home)
                }
            }
        ];
    }
}
</script>

<template>
    <CommonLayout :menu="menu" :route_home="route_home" name="GDProxy">
        <slot/>
    </CommonLayout>
</template>
