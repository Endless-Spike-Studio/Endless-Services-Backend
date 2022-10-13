<script lang="ts" setup>
import {NIcon} from "naive-ui";
import {FolderOutlined, LinkOutlined, UserOutlined} from "@vicons/antd";
import {ExtraMenuOption} from "@/scripts/types/menu";
import {LibraryMusicRound, MusicNoteOutlined} from "@vicons/material";
import {SwitchHorizontal} from "@vicons/tabler";
import {GatewayUserAccess} from "@vicons/carbon";
import {visit_route} from "@/scripts/core/utils";

const aside = reactive({
    active: ref('account'),
    options: [
        {
            label: '账号相关',
            key: 'account',
            icon: () => h(NIcon, {
                component: UserOutlined
            })
        },
        {
            label: '关卡相关',
            key: 'level',
            icon: () => h(NIcon, {
                component: FolderOutlined
            })
        },
        {
            label: '歌曲相关',
            key: 'song',
            icon: () => h(NIcon, {
                component: MusicNoteOutlined
            })
        }
    ] as ExtraMenuOption[]
});
</script>

<template layout="GDCS">
    <n-el class="lg:w-1/2 mx-auto">
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:3">
            <n-grid-item class="text-left">
                <n-card content-style="padding: 0;">
                    <n-menu v-model:value="aside.active" :options="aside.options"/>
                </n-card>
            </n-grid-item>

            <n-grid-item :span="2">
                <n-tabs v-model:value="aside.active" animated tab-style="display: none;">
                    <n-tab-pane name="account">
                        <n-card>
                            <n-space>
                                <n-button @click="visit_route('gdcs.tools.account.link.home')">
                                    <template #icon>
                                        <n-icon :component="LinkOutlined"/>
                                    </template>

                                    <n-text>账号链接</n-text>
                                </n-button>
                            </n-space>
                        </n-card>
                    </n-tab-pane>

                    <n-tab-pane name="level">
                        <n-card>
                            <n-space>
                                <n-button @click="visit_route('gdcs.tools.level.transfer.home')">
                                    <template #icon>
                                        <n-icon :component="SwitchHorizontal"/>
                                    </template>

                                    <n-text>关卡转移</n-text>
                                </n-button>

                                <n-button>
                                    <template #icon>
                                        <n-icon :component="GatewayUserAccess"/>
                                    </template>

                                    <n-text>临时关卡上传许可</n-text>
                                </n-button>
                            </n-space>
                        </n-card>
                    </n-tab-pane>

                    <n-tab-pane name="song">
                        <n-card>
                            <n-space>
                                <n-button @click="visit_route('gdcs.tools.song.custom.home')">
                                    <template #icon>
                                        <n-icon :component="LibraryMusicRound"/>
                                    </template>

                                    <n-text>自定义歌曲</n-text>
                                </n-button>
                            </n-space>
                        </n-card>
                    </n-tab-pane>
                </n-tabs>
            </n-grid-item>
        </n-grid>
    </n-el>
</template>
