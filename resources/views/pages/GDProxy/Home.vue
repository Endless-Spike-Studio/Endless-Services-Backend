<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDProxy/Common.vue";
import {DropdownOption} from "naive-ui";
import {useApiStore} from "@/scripts/core/stores";
import {
    AndroidOutlined,
    AppleOutlined,
    GithubOutlined,
    HeartTwotone,
    UsergroupAddOutlined,
    WindowsOutlined
} from "@vicons/antd";
import {useWindowSize} from "@vueuse/core";

const windowsDownloadDropdown = reactive({
    options: [
        {
            label: '无资源包 (.exe)',
            key: 'exe',
            onSelect() {
                open('https://cdn.geometrydashchinese.com/client/GDProxy.exe');
            }
        },
        {
            label: '带资源包 (.zip)',
            key: 'zip',
            onSelect() {
                open('https://cdn.geometrydashchinese.com/client/GDProxy.zip');
            }
        }
    ] as DropdownOption[],
    handleSelect(_key: string, option: DropdownOption) {
        (option.onSelect as () => unknown)?.();
    }
});

function handleAppleDownload() {
    const apiStore = useApiStore();
    apiStore.$message.error('没有');
}

const {width} = useWindowSize();
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-space vertical>
                <n-space justify="center">
                    <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.apk" tag="a">
                        <template #icon>
                            <n-icon :component="AndroidOutlined"/>
                        </template>

                        Android
                    </n-button>

                    <n-dropdown :options="windowsDownloadDropdown.options" trigger="click"
                                @select="windowsDownloadDropdown.handleSelect">
                        <n-button>
                            <template #icon>
                                <n-icon :component="WindowsOutlined"/>
                            </template>

                            Windows
                        </n-button>
                    </n-dropdown>

                    <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.ipa"
                              @click="handleAppleDownload">
                        <template #icon>
                            <n-icon :component="AppleOutlined"/>
                        </template>

                        Apple
                    </n-button>
                </n-space>

                <n-space justify="center">
                    <n-button href="https://afdian.net/a/WOSHIZHAZHA120" tag="a">
                        <template #icon>
                            <n-icon :component="HeartTwotone"/>
                        </template>

                        支持我们
                    </n-button>

                    <n-button href="https://jq.qq.com/?k=1R3bJnPU" tag="a">
                        <template #icon>
                            <n-icon :component="UsergroupAddOutlined"/>
                        </template>

                        加入讨论群
                    </n-button>
                </n-space>

                <n-space justify="center">
                    <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a">
                        <template #icon>
                            <n-icon :component="GithubOutlined"/>
                        </template>

                        Github
                    </n-button>
                </n-space>
            </n-space>

            <n-grid :x-gap="10" :y-gap="10" cols="1 640:2">
                <n-grid-item>
                    <n-card class="h-full" title="这是什么">
                        GDProxy,全称
                        <n-text type="info">Geometry Dash Proxy</n-text>
                        <br>
                        是由
                        <n-button href="https://zhazha120.cn" tag="a" text type="primary">渣渣120</n-button>
                        独立开发的 Geometry Dash 加速器
                        <br>
                        GDProxy 是开源的,您可以在
                        <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a"
                                  text type="primary">
                            Github
                        </n-button>
                        上查看所有的源代码

                        <br><br>

                        如果您不想下载客户端,您也可以使用
                        <n-text type="info">GDPS Switcher</n-text>
                        功能
                        <br>
                        只需将服务器地址设置为
                        <n-text type="info">https://dl.geometrydashchinese.com</n-text>
                        就可以开始加速了
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="开发者">
                        <n-descriptions :columns="width < 640 ? 1 : 2">
                            <template #header>
                                <n-text type="info">渣渣120</n-text>
                            </template>

                            <n-descriptions-item label="个人主页">
                                <n-button href="https://zhazha120.cn" tag="a" text type="primary">
                                    zhazha120.cn
                                </n-button>
                            </n-descriptions-item>

                            <n-descriptions-item label="QQ">
                                <n-button href="https://wpa.qq.com/msgrd?uin=2331281251" tag="a" text
                                          type="primary">2331281251
                                </n-button>
                            </n-descriptions-item>

                            <n-descriptions-item label="哔哩哔哩">
                                <n-button href="https://space.bilibili.com/24267334" tag="a" text
                                          type="primary">24267334
                                </n-button>
                            </n-descriptions-item>

                            <n-descriptions-item label="Github">
                                <n-button href="https://github.com/WOSHIZHAZHA120" tag="a" text type="primary">
                                    WOSHIZHAZHA120
                                </n-button>
                            </n-descriptions-item>
                        </n-descriptions>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
