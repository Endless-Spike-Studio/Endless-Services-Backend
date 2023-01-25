<script lang="ts" setup>
import {MenuOption, NIcon} from "naive-ui";
import {HomeTwotone} from "@vicons/antd";
import {to_home} from "@/scripts/core/utils";
import CommonLayout from "@/views/layouts/CommonLayout.vue";
import route from "@/scripts/core/route";

const route_home = 'ngproxy.home';

const menu = {
    activeStateExtraUpdater() {
        if (route().current() === 'ngproxy.info') {
            return 'home';
        }
    },
    left(): MenuOption[] {
        return [
            {
                title: 'NGProxy',
                key: 'logo',
                onSelect() {
                    to_home(route_home);
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
                    to_home(route_home);
                }
            }
        ];
    }
}
</script>

<template>
    <CommonLayout :menu="menu" :route_home="route_home" name="NGProxy">
        <slot/>
    </CommonLayout>
</template>
