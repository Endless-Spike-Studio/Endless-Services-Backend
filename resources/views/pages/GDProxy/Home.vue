<script lang="ts" setup>
import {isMobile} from "@/scripts/core/utils";

defineProps<{
    count: {
        upload: number;
        download: number;
    }
}>();

function convertByte(value: number) {
    const units = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

    let times = 0;
    while (value >= 1024) {
        value /= 1024;
        times++;
    }

    return value.toFixed(value < 10 && times > 0 ? 1 : 0) + ' ' + units[times];
}
</script>

<template layout="GDProxy">
    <n-space class="lg:w-1/2 mx-auto" vertical>
        <n-card>
            <template #header>
                <n-text>到目前, GDProxy 处理了</n-text>
            </template>

            <n-space justify="space-evenly">
                <n-statistic label="上传数据">
                    {{ convertByte(count.upload) }}
                </n-statistic>

                <n-statistic label="下载数据">
                    {{ convertByte(count.download) }}
                </n-statistic>
            </n-space>
        </n-card>

        <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
            <n-grid-item>
                <n-card class="text-center">
                    <n-thing>
                        <template #header>
                            <n-h2 class="inline">渣渣120</n-h2>
                            <n-text :depth="3" class="text-lg ml-2.5">开发者</n-text>
                        </template>
                        <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                            <n-descriptions-item label="个人主页">
                                <n-button href="https://zhazha120.cn" tag="a" text>
                                    https://zhazha120.cn
                                </n-button>
                            </n-descriptions-item>
                            <n-descriptions-item label="QQ">
                                <n-button href="https://wpa.qq.com/msgrd?uin=2331281251" tag="a" text>
                                    2331281251
                                </n-button>
                            </n-descriptions-item>
                            <n-descriptions-item label="哔哩哔哩">
                                <n-button href="https://space.bilibili.com/24267334" tag="a" text>
                                    24267334
                                </n-button>
                            </n-descriptions-item>
                        </n-descriptions>
                    </n-thing>
                </n-card>
            </n-grid-item>

            <n-grid-item>
                <n-card class="w-full" title="下载">
                    <n-text>或者你也可以使用</n-text>
                    <n-text type="info">&nbsp;GDPS Switcher&nbsp;</n-text>
                    <n-text>功能</n-text>

                    <br>

                    <n-text>只需将地址设为&nbsp;</n-text>
                    <n-text type="info">https://dl.geometrydashchinese.com</n-text>
                    <n-text>&nbsp;就好了</n-text>

                    <template #footer>
                        <n-space>
                            <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.apk" tag="a">
                                安卓
                            </n-button>

                            <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.zip" tag="a">
                                Windows
                            </n-button>

                            <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.exe" tag="a">
                                Windows (单exe)
                            </n-button>

                            <n-button disabled href="https://cdn.geometrydashchinese.com/client/GDProxy.ipa" tag="a">
                                IOS 或 MacOS
                            </n-button>
                        </n-space>
                    </template>
                </n-card>
            </n-grid-item>
        </n-grid>
    </n-space>
</template>
