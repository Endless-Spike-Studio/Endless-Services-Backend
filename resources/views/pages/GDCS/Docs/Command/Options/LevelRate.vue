<script lang="ts" setup>
import {NList, NListItem, NPopover, NSpace, NText, NThing} from "naive-ui";</script>

<template>
    <n-list bordered>
        <n-list-item>
            <n-thing>
                <template #header>
                    --featured-score=[ value ]
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
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    --demon-difficulty=[
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
                    恶魔难度
                </template>
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    -epic
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
            </n-thing>
        </n-list-item>

        <n-list-item>
            <n-thing>
                <template #header>
                    -silver-coin
                </template>

                <template #description>
                    设置关卡为银币
                </template>
            </n-thing>
        </n-list-item>
    </n-list>
</template>
