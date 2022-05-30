<script setup lang="ts">
import {NButton, NCard, NDescriptions, NDescriptionsItem, NGrid, NGridItem} from "naive-ui";
import {isMobile, toURL} from "@/scripts/helpers";

defineProps({
    title: {
        type: String,
        required: true
    }
});
</script>

<template>
    <n-card :title="title">
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
            <n-grid-item>
                <n-descriptions :column="isMobile ? 1 : 3" :bordered="true" title="渣渣120">
                    <n-descriptions-item label="QQ">
                        <n-button text @click="toURL('https://wpa.qq.com/msgrd?uin=2331281251')">
                            2331281251
                        </n-button>
                    </n-descriptions-item>
                    <n-descriptions-item label="职责">
                        开发 | 运维 | 管理
                    </n-descriptions-item>
                    <n-descriptions-item label="哔哩哔哩">
                        <n-button text @click="toURL('https://space.bilibili.com/24267334')">
                            24267334
                        </n-button>
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>

            <n-grid-item>
                <n-descriptions :column="isMobile ? 1 : 3" :bordered="true" title="xyzlol">
                    <n-descriptions-item label="QQ">
                        <n-button text @click="toURL('https://wpa.qq.com/msgrd?uin=1292866784')">
                            1292866784
                        </n-button>
                    </n-descriptions-item>
                    <n-descriptions-item label="职责">
                        运维 | 管理 | 规则制定
                    </n-descriptions-item>
                    <n-descriptions-item label="哔哩哔哩">
                        <n-button text @click="toURL('https://space.bilibili.com/93653653')">
                            93653653
                        </n-button>
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>
        </n-grid>
    </n-card>
</template>
