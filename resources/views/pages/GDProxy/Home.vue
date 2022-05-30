<script setup lang="ts">
import {NButton, NDivider, NDropdown, NIcon, NSpace} from "naive-ui";
import Banner from "@/images/GDProxy/Banner.png";
import CodeTime from "@/views/components/CodeTime.vue";
import HomeButtons from "@/views/components/HomeButtons.vue";
import {AndroidFilled, AppleFilled, WindowsFilled} from "@vicons/antd";
import {toURL} from "@/scripts/helpers";

const windowsDownloadOptions = [
    {
        label: '无资源包 (exe)',
        key: 'exe'
    },
    {
        label: '带资源包',
        key: 'zip'
    }
];

function handleWindowsDownload(suffix: string) {
    toURL('https://cdn.geometrydashchinese.com/client/GDProxy.' + suffix);
}
</script>

<template layout="GDProxy">
    <n-space vertical>
        <div class="mx-auto lg:w-1/2">
            <img :src="Banner" alt="Banner">
        </div>

        <div class="mx-auto py-5">
            <home-buttons join-group-button-text="加入 GDProxy 讨论群"/>
        </div>

        <div class="text-center lg:w-1/5 mx-auto">
            <n-divider>下载</n-divider>
            <n-space justify="center">
                <n-button @click="toURL('https://cdn.geometrydashchinese.com/client/GDProxy.apk')">
                    <template #icon>
                        <n-icon>
                            <android-filled/>
                        </n-icon>
                    </template>

                    Android
                </n-button>

                <n-dropdown :options="windowsDownloadOptions" trigger="click" @select="handleWindowsDownload">
                    <n-button>
                        <template #icon>
                            <n-icon>
                                <windows-filled/>
                            </n-icon>
                        </template>

                        Windows
                    </n-button>
                </n-dropdown>

                <n-button disabled @click="toURL('https://cdn.geometrydashchinese.com/client/GDProxy.ipa')">
                    <template #icon>
                        <n-icon>
                            <apple-filled/>
                        </n-icon>
                    </template>

                    Mac | IOS
                </n-button>
            </n-space>
        </div>

        <div class="text-center lg:w-1/5 mx-auto pt-5">
            <code-time/>
        </div>
    </n-space>
</template>
