<script lang="ts" setup>
import {toURL} from "@/scripts/helpers";
import {NButton, NList, NListItem, NPopover, NSpace, NText, NThing} from "naive-ui";</script>

<template>
    <n-list bordered>
        <n-list-item>
            <n-thing>
                <template #header>
                    as daily
                </template>

                <template #description>
                    将关卡设为下一个 Daily
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>MARK_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    as weekly
                </template>

                <template #description>
                    将关卡设为下一个 Weekly
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>MARK_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    name [ value ]
                </template>

                <template #description>
                    修改关卡名
                </template>

                <template #footer>
                    <n-text :depth="3">仅关卡本人可修改</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    desc [ value ]
                </template>

                <template #description>
                    修改关卡简介
                </template>

                <template #footer>
                    <n-text :depth="3">仅关卡本人可修改</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    password [
                    <n-popover>
                        <template #trigger>
                            <n-text type="info">value</n-text>
                        </template>

                        <n-space vertical>
                            <n-text type="error">
                                <n-text code>0</n-text>
                                或
                                <n-text code>no-copy</n-text>
                                为禁止 Copy
                            </n-text>

                            <n-text type="primary">
                                <n-text code>1</n-text>
                                或
                                <n-text code>free-copy</n-text>
                                为无需密码直接 Copy
                            </n-text>

                            <n-text type="info">
                                <n-text code>random</n-text>
                                为随机密码
                            </n-text>
                        </n-space>
                    </n-popover>
                    ]
                </template>

                <template #description>
                    修改关卡 Copy 密码
                </template>

                <template #footer>
                    <n-text :depth="3">仅关卡本人可修改</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    audio-track [
                    <n-popover>
                        <template #trigger>
                            <n-text type="info">value</n-text>
                        </template>

                        <n-space vertical>
                            <n-button text type="primary"
                                      @click="toURL('https://docs.gdprogra.me/#/reference?id=audio-track')">参照表
                            </n-button>

                            <n-text type="info">值输入的是 Server ID 哦</n-text>
                        </n-space>
                    </n-popover>
                    ]
                </template>

                <template #description>
                    修改关卡歌曲为官方歌曲
                </template>

                <template #footer>
                    <n-text :depth="3">仅关卡本人可修改</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    song [ value ]
                </template>

                <template #description>
                    修改关卡歌曲为 NewGrounds 的歌曲
                </template>

                <template #footer>
                    <n-text :depth="3">仅关卡本人可修改</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    difficulty [
                    <n-popover>
                        <template #trigger>
                            <n-text type="info">value</n-text>
                        </template>

                        <n-space>
                            <n-text code>auto</n-text>
                            或
                            <n-text code>easy</n-text>
                            或
                            <n-text code>normal</n-text>
                            或
                            <n-text code>hard</n-text>
                            或
                            <n-text code>harder</n-text>
                            或
                            <n-text code>insane</n-text>
                            或
                            <n-text code>demon</n-text>
                        </n-space>
                    </n-popover>
                    ]
                </template>

                <template #description>
                    修改关卡的脸
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>RATE_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    demon-difficulty [
                    <n-popover>
                        <template #trigger>
                            <n-text type="info">value</n-text>
                        </template>

                        <n-space>
                            <n-text code>easy</n-text>
                            或
                            <n-text code>medium</n-text>
                            或
                            <n-text code>hard</n-text>
                            或
                            <n-text code>insane</n-text>
                            或
                            <n-text code>extreme</n-text>
                        </n-space>
                    </n-popover>
                    ]
                </template>

                <template #description>
                    修改关卡恶魔的脸
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>RATE_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    stars [
                    <n-popover trigger="hover">
                        <template #trigger>
                            <n-text class="cursor-pointer" type="info">value</n-text>
                        </template>

                        该值必须为 1 到 10 之间
                    </n-popover>
                    ]
                </template>

                <template #description>
                    修改关卡的星星
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>RATE_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    featured-score [ value ]
                </template>

                <template #description>
                    特色分数, 大于 0 时将设置关卡为
                    <n-popover>
                        <template #trigger>
                            <n-text type="info">Featured</n-text>
                        </template>

                        金圈
                    </n-popover>
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>RATE_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    epic [
                    <n-popover trigger="hover">
                        <template #trigger>
                            <n-text class="cursor-pointer" type="info">status</n-text>
                        </template>

                        该值必须为

                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">真</n-text>
                            </template>

                            <n-space vertical>
                                <n-text code>true</n-text>
                                或
                                <n-text code>yes</n-text>
                                或
                                <n-text code>no</n-text>
                                或
                                <n-text code>1</n-text>
                            </n-space>
                        </n-popover>

                        或

                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">假</n-text>
                            </template>

                            <n-space vertical>
                                <n-text code>false</n-text>
                                或
                                <n-text code>no</n-text>
                                或
                                <n-text code>off</n-text>
                                或
                                <n-text code>0</n-text>
                            </n-space>
                        </n-popover>
                    </n-popover>
                    ]
                </template>

                <template #description>
                    设置关卡为
                    <n-popover>
                        <template #trigger>
                            <n-text type="info">Epic</n-text>
                        </template>

                        冒火
                    </n-popover>
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>RATE_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    silver-coin [
                    <n-popover trigger="hover">
                        <template #trigger>
                            <n-text class="cursor-pointer" type="info">status</n-text>
                        </template>

                        该值必须为

                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">真</n-text>
                            </template>

                            <n-space vertical>
                                <n-text code>true</n-text>
                                或
                                <n-text code>yes</n-text>
                                或
                                <n-text code>no</n-text>
                                或
                                <n-text code>1</n-text>
                            </n-space>
                        </n-popover>

                        或

                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">假</n-text>
                            </template>

                            <n-space vertical>
                                <n-text code>false</n-text>
                                或
                                <n-text code>no</n-text>
                                或
                                <n-text code>off</n-text>
                                或
                                <n-text code>0</n-text>
                            </n-space>
                        </n-popover>
                    </n-popover>
                    ]
                </template>

                <template #description>
                    设置关卡为银币
                </template>

                <template #footer>
                    <n-text :depth="3">需要权限:&nbsp;</n-text>
                    <n-text code>RATE_LEVEL</n-text>
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    unlisted [
                    <n-popover trigger="hover">
                        <template #trigger>
                            <n-text class="cursor-pointer" type="info">status</n-text>
                        </template>

                        该值必须为

                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">真</n-text>
                            </template>

                            <n-space vertical>
                                <n-text code>true</n-text>
                                或
                                <n-text code>yes</n-text>
                                或
                                <n-text code>no</n-text>
                                或
                                <n-text code>1</n-text>
                            </n-space>
                        </n-popover>

                        或

                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">假</n-text>
                            </template>

                            <n-space vertical>
                                <n-text code>false</n-text>
                                或
                                <n-text code>no</n-text>
                                或
                                <n-text code>off</n-text>
                                或
                                <n-text code>0</n-text>
                            </n-space>
                        </n-popover>
                    </n-popover>
                    ]
                </template>

                <template #description>
                    设置关卡是否不公开
                </template>

                <template #footer>
                    <n-text :depth="3">仅关卡本人可修改</n-text>
                </template>
            </n-thing>
        </n-list-item>
    </n-list>
</template>
