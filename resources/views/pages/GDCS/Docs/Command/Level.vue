<script lang="ts" setup>
import {NButton, NCard, NDescriptions, NDescriptionsItem, NGrid, NGridItem, NPopover, NText} from "naive-ui"
import {isMobile} from "@/scripts/helpers"
import {useGlobalStore} from "@/scripts/stores"
import {h} from "vue"

const globalStore = useGlobalStore()

async function renderOptions(type: string) {
    const component = await import('./Options/' + type + '.vue')

    globalStore.$dialog.info({
        title: '命令参数',
        showIcon: false,
        content: () => h(component.default)
    })
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="关卡命令">
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
            <n-grid-item>
                <n-descriptions :bordered="true" :column="isMobile ? 1 : 3" title="!test">
                    <n-descriptions-item label="用法">
                        !test
                    </n-descriptions-item>
                    <n-descriptions-item label="描述">
                        测试命令
                    </n-descriptions-item>
                    <n-descriptions-item label="返回值">
                        <n-text code>worked!</n-text>
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>

            <n-grid-item>
                <n-descriptions :bordered="true" :column="isMobile ? 1 : 3" title="!set">
                    <n-descriptions-item label="用法">
                        !set [
                        <n-button text type="primary" @click="renderOptions('LevelSet')">type</n-button>
                        ] [
                        <n-button text type="primary" @click="renderOptions('LevelSet')">value</n-button>
                        ]
                    </n-descriptions-item>
                    <n-descriptions-item label="描述">
                        调整关卡
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>

            <n-grid-item>
                <n-descriptions :bordered="true" :column="isMobile ? 1 :3" title="!transfer_to">
                    <n-descriptions-item label="用法">
                        !transfer_to [
                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">type</n-text>
                            </template>

                            <n-popover trigger="hover">
                                <template #trigger>
                                    <n-text code>account</n-text>
                                </template>

                                账号
                            </n-popover>

                            或

                            <n-popover trigger="hover">
                                <template #trigger>
                                    <n-text code>user</n-text>
                                </template>

                                用户
                            </n-popover>
                        </n-popover>
                        ] [ id ]
                    </n-descriptions-item>
                    <n-descriptions-item label="描述">
                        转移关卡给其他人
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>

            <n-grid-item>
                <n-descriptions :bordered="true" :column="isMobile ? 1 :3" title="!rate">
                    <n-descriptions-item label="用法">
                        !rate [
                        <n-popover trigger="hover">
                            <template #trigger>
                                <n-text class="cursor-pointer" type="info">stars</n-text>
                            </template>

                            该值必须为 1 到 10 之间
                        </n-popover>
                        ] [
                        <n-button text type="primary" @click="renderOptions('LevelRate')">options</n-button>
                        ]
                    </n-descriptions-item>
                    <n-descriptions-item label="描述">
                        Rate 关卡
                    </n-descriptions-item>
                    <n-descriptions-item label="权限">
                        <n-text code>RATE_LEVEL</n-text>
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>

            <n-grid-item>
                <n-descriptions :bordered="true" :column="isMobile ? 1 :3" title="!unRate">
                    <n-descriptions-item label="用法">
                        !unRate
                    </n-descriptions-item>
                    <n-descriptions-item label="描述">
                        取消 Rate 关卡
                    </n-descriptions-item>
                    <n-descriptions-item label="权限">
                        <n-text code>RATE_LEVEL</n-text>
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>
        </n-grid>
    </n-card>
</template>
