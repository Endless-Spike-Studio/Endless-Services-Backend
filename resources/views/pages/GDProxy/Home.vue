<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDProxy/Common.vue";
import {
    AndroidOutlined,
    AppleOutlined,
    FileZipTwotone,
    GithubOutlined,
    HeartTwotone,
    HomeTwotone,
    MailTwotone,
    UsergroupAddOutlined,
    WindowsOutlined
} from "@vicons/antd";
import {copy, isMobile} from "@/scripts/core/utils";
import {Box} from "@vicons/tabler";
import avatar_1 from "@/images/avatars/WOSHIZHAZHA120.png";
import Grid from "@/views/components/Grid.vue";
import {Head} from "@inertiajs/vue3";</script>

<template>
    <CommonLayout>
        <Head>
            <title>主页</title>
        </Head>

        <n-space vertical>
            <n-card title="下载">
                <Grid :cols="isMobile ? 2 : 4" class="[&>*]:mx-auto items-center">
                    <n-grid-item>
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.apk" tag="a" text>
                            <n-space vertical>
                                <n-icon :component="AndroidOutlined" :size="50"/>
                                <n-text class="text-current text-2xl">安卓</n-text>
                            </n-space>
                        </n-button>
                    </n-grid-item>

                    <n-grid-item v-if="!isMobile">
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.zip" tag="a" text>
                            <n-space vertical>
                                <n-space justify="center">
                                    <n-icon :component="WindowsOutlined" :size="25"/>
                                    <n-icon :component="FileZipTwotone" :size="25"/>
                                </n-space>

                                <n-text class="text-2xl">Windows</n-text>
                                <n-text>[.zip] (带资源包)</n-text>
                                <n-text type="success">(推荐)</n-text>
                            </n-space>
                        </n-button>
                    </n-grid-item>

                    <n-grid-item v-if="!isMobile">
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDProxy.exe" tag="a" text>
                            <n-space vertical>
                                <n-space justify="center">
                                    <n-icon :component="WindowsOutlined" :size="25"/>
                                    <n-icon :component="Box" :size="25"/>
                                </n-space>

                                <n-text class="text-2xl">Windows</n-text>
                                <n-text>[.exe] (无资源包)</n-text>
                                <n-text type="warning">仅供替换, 单独下载无法运行</n-text>
                            </n-space>
                        </n-button>
                    </n-grid-item>

                    <n-grid-item>
                        <n-button disabled href="https://cdn.geometrydashchinese.com/client/GDProxy.ipa" tag="a"
                                  text type="warning">
                            <n-space vertical>
                                <n-icon :component="AppleOutlined" :size="50"/>
                                <n-text class="text-current text-2xl">苹果</n-text>
                            </n-space>
                        </n-button>
                    </n-grid-item>
                </Grid>

                <template #footer>
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

                        <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a">
                            <template #icon>
                                <n-icon :component="GithubOutlined"/>
                            </template>

                            Github
                        </n-button>
                    </n-space>
                </template>
            </n-card>

            <Grid :cols="isMobile ? 1 : 2" class="[&>*>*]:h-full">
                <n-grid-item>
                    <n-card class="h-full" title="这是什么">
                        GDProxy, 全称
                        <n-text type="info">Geometry Dash Proxy</n-text>
                        <br>
                        是由
                        <n-button href="https://zhazha120.cn" tag="a" text type="primary">渣渣120</n-button>
                        独立开发的 Geometry Dash 加速器
                        <br>
                        GDProxy 是开源的, 您可以在
                        <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a"
                                  text type="primary">
                            Github
                        </n-button>
                        上查看所有的源代码

                        <br><br>

                        如果您不想下载客户端, 您也可以使用
                        <n-text type="info">GDPS Switcher</n-text>
                        功能
                        <br>
                        只需将服务器地址设置为
                        <n-button text type="primary" @click="copy('https://dl.geometrydashchinese.com')">
                            https://dl.geometrydashchinese.com
                        </n-button>
                        就可以开始加速了
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="开发者">
                        <n-space>
                            <n-space class="text-center leading-none" vertical>
                                <n-image :src="avatar_1" :width="75"
                                         class="rounded-full transition duration-1000 ease-in-out hover:rotate-[360deg]"
                                         preview-disabled/>
                                <n-text class="font-bold text-2xl">渣渣120</n-text>
                            </n-space>

                            <Grid class="[&>*]:leading-none">
                                <n-grid-item>
                                    <n-space vertical>
                                        <n-text class="font-bold">QQ</n-text>
                                        <n-button href="https://wpa.qq.com/msgrd?uin=2331281251" tag="a"
                                                  text type="primary">
                                            2331281251
                                        </n-button>
                                    </n-space>
                                </n-grid-item>

                                <n-grid-item>
                                    <n-space vertical>
                                        <n-text class="font-bold">哔哩哔哩</n-text>
                                        <n-button href="https://space.bilibili.com/24267334" tag="a"
                                                  text type="primary">
                                            24267334
                                        </n-button>
                                    </n-space>
                                </n-grid-item>

                                <n-grid-item :span="2">
                                    <n-space class="mt-2.5">
                                        <n-button href="https://zhazha120.cn" tag="a" text>
                                            <template #icon>
                                                <n-icon :component="HomeTwotone"/>
                                            </template>
                                        </n-button>

                                        <n-button href="https://github.com/WOSHIZHAZHA120" tag="a" text>
                                            <template #icon>
                                                <n-icon :component="GithubOutlined"/>
                                            </template>
                                        </n-button>

                                        <n-button href="mailto://WOSHIZHAZHA120@qq.com" tag="a" text>
                                            <template #icon>
                                                <n-icon :component="MailTwotone"/>
                                            </template>
                                        </n-button>
                                    </n-space>
                                </n-grid-item>
                            </Grid>
                        </n-space>
                    </n-card>
                </n-grid-item>
            </Grid>
        </n-space>
    </CommonLayout>
</template>
