<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {MenuOption, NIcon} from "naive-ui";
import {FileTwotone, LinkOutlined, PlaySquareTwotone} from "@vicons/antd";
import CreateUsingLink from "@/views/pages/GDCS/Tools/Song/Custom/Create/Link.vue";
import CreateUsingFile from "@/views/pages/GDCS/Tools/Song/Custom/Create/File.vue";
import CreateUsingNetease from "@/views/pages/GDCS/Tools/Song/Custom/Create/Netease.vue";

const menu = reactive({
    active: ref('link'),
    options: [
        {
            label: '外链',
            key: 'link',
            icon: () => h(NIcon, {
                component: LinkOutlined
            }),
            render: () => h(CreateUsingLink)
        },
        {
            label: '文件',
            key: 'file',
            icon: () => h(NIcon, {
                component: FileTwotone
            }),
            render: () => h(CreateUsingFile)
        },
        {
            label: '网易云音乐',
            key: 'netease',
            icon: () => h(NIcon, {
                component: PlaySquareTwotone
            }),
            render: () => h(CreateUsingNetease)
        }
    ] as MenuOption[]
})
</script>

<template>
    <CommonLayout>
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:4">
            <n-grid-item>
                <n-card :content-style="{ padding: 0 }">
                    <n-menu v-model:value="menu.active" :options="menu.options" mode="vertical"/>
                </n-card>
            </n-grid-item>

            <n-grid-item :span="3">
                <n-tabs v-model:value="menu.active" :tab-style="{ display: 'none' }" animated pane-class="!p-0">
                    <n-tab-pane v-for="option in menu.options" :name="option.key">
                        <Component :is="option.render"/>
                    </n-tab-pane>
                </n-tabs>
            </n-grid-item>
        </n-grid>
    </CommonLayout>
</template>
