<script lang="ts" setup>
import {
    NAlert,
    NButton,
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NDivider,
    NGrid,
    NGridItem,
    NImage,
    NSpace
} from "naive-ui"
import {isMobile, toURL} from "@/scripts/helpers"

defineProps<{
    banner: string,
    groupName: string,
    teamName: string
}>()

const code_time_url = 'https://img.shields.io/endpoint?url=' + encodeURIComponent('https://codetime-api.datreks.com/badge/2270') + '&style=for-the-badge'
</script>

<template>
    <n-space vertical>
        <div v-if="banner" class="mx-auto lg:w-1/2">
            <n-image :img-props="{ class: 'w-full' }" :src="banner" alt="Banner"/>
        </div>

        <div class="mx-auto py-5">
            <n-space justify="center">
                <n-button type="tertiary" @click="toURL('https://afdian.net/@WOSHIZHAZHA120')">
                    支持一下 (赞助)
                </n-button>

                <n-button v-if="groupName" type="tertiary"
                          @click="toURL('https://qm.qq.com/cgi-bin/qm/qr?k=jQxB2uKVm30XzwuxsgrJwbKRErCkFAjw')">
                    加入 {{ groupName }}
                </n-button>
            </n-space>
        </div>

        <div class="text-center lg:w-1/5 mx-auto pt-5">
            <n-alert :show-icon="false" closable title="代码时间">
                <n-image :src="code_time_url"/>
            </n-alert>
        </div>

        <div class="text-center lg:w-2/3 mx-auto">
            <slot/>
        </div>

        <div v-if="teamName" class="lg:w-2/3 py-5 mx-auto">
            <n-card :title="teamName">
                <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
                    <n-grid-item>
                        <n-descriptions :bordered="true" :column="isMobile ? 1 : 3" title="渣渣120">
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

                    <n-grid-item v-if="isMobile">
                        <n-divider/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-descriptions :bordered="true" :column="isMobile ? 1 : 3" title="xyzlol">
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
        </div>
    </n-space>
</template>
