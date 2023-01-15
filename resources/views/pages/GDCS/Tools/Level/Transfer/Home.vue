<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {NIcon} from "naive-ui";
import {LeftCircleOutlined, RightCircleOutlined} from "@vicons/antd";
import LinkSelectForLevelTransferIn from "@/views/pages/GDCS/Tools/Level/Transfer/In/LinkSelect.vue";
import LevelSelectForLevelTransferOut from "@/views/pages/GDCS/Tools/Level/Transfer/Out/LevelSelect.vue";

const menu = reactive({
    active: ref('in'),
    options: [
        {
            label: '转入',
            key: 'in',
            icon: () => h(NIcon, {
                component: RightCircleOutlined
            }),
            render: () => h(LinkSelectForLevelTransferIn)
        },
        {
            label: '转出',
            key: 'out',
            icon: () => h(NIcon, {
                component: LeftCircleOutlined
            }),
            render: () => h(LevelSelectForLevelTransferOut)
        }
    ]
});
</script>

<template>
    <CommonLayout>
        <n-grid :x-gap="10" :y-gap="10" cols="1 640:4">
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
