<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {MenuOption, NButton, NCard, NIcon, NSpace} from "naive-ui";
import {FolderTwotone, LinkOutlined, RetweetOutlined, UserOutlined} from "@vicons/antd";
import {to_route} from "@/scripts/core/utils";
import {VNode} from "vue";
import {GatewayUserAccess} from "@vicons/carbon";
import {LibraryMusicTwotone, QueueMusicTwotone} from "@vicons/material";

interface Tool {
    name: string;
    icon: () => VNode;
    onSelect: () => unknown;
}

const menu = reactive({
    active: ref('account'),
    options: [
        {
            label: '账号',
            key: 'account',
            icon: () => h(NIcon, {
                component: UserOutlined
            }),
            tools: [
                {
                    name: '账号链接',
                    icon: () => h(NIcon, {
                        component: LinkOutlined
                    }),
                    onSelect() {
                        to_route('gdcs.tools.account.link.index')
                    }
                }
            ] as Tool[]
        },
        {
            label: '关卡',
            key: 'level',
            icon: () => h(NIcon, {
                component: FolderTwotone
            }),
            tools: [
                {
                    name: '关卡转移',
                    icon: () => h(NIcon, {
                        component: RetweetOutlined
                    }),
                    onSelect() {
                        to_route('gdcs.tools.level.transfer.home')
                    }
                },
                {
                    name: '临时关卡上传许可',
                    icon: () => h(NIcon, {
                        component: GatewayUserAccess
                    }),
                    onSelect() {
                        to_route('gdcs.tools.level.temp_upload_access.index')
                    }
                }
            ] as Tool[]
        },
        {
            label: '歌曲',
            key: 'song',
            icon: () => h(NIcon, {
                component: LibraryMusicTwotone
            }),
            tools: [
                {
                    name: '自定义歌曲',
                    icon: () => h(NIcon, {
                        component: QueueMusicTwotone
                    }),
                    onSelect() {
                        to_route('gdcs.tools.song.custom.index')
                    }
                }
            ] as Tool[]
        }
    ] as MenuOption[]
});
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-grid :x-gap="10" :y-gap="10" cols="1 640:4">
                <n-grid-item>
                    <n-card :content-style="{ padding: 0 }">
                        <n-menu v-model:value="menu.active" :options="menu.options" mode="vertical"/>
                    </n-card>
                </n-grid-item>

                <n-grid-item :span="3">
                    <n-card>
                        <n-tabs v-model:value="menu.active" :tab-style="{ display: 'none' }" animated pane-class="!p-0">
                            <n-tab-pane v-for="option in menu.options" :name="option.key">
                                <n-space>
                                    <n-button v-for="tool in option.tools" @click="tool.onSelect">
                                        <template #icon>
                                            <Component :is="tool.icon"/>
                                        </template>

                                        {{ tool.name }}
                                    </n-button>
                                </n-space>
                            </n-tab-pane>
                        </n-tabs>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
