<script lang="ts" setup>
import {isMobile} from "@/scripts/core/utils";

defineProps<{
    count: {
        players: number;
        accounts: number;
        levels: number;
        comments: number;
    }
}>();
</script>

<template layout="GDCS">
    <n-space class="lg:w-1/2 mx-auto" vertical>
        <n-alert closable type="info">
            欢迎来到新的 GDCS 主页!
        </n-alert>

        <n-card>
            <template #header>
                <n-text>到目前, GDCS 拥有了</n-text>
            </template>

            <n-space justify="space-evenly">
                <n-statistic label="玩家">
                    {{ count.players }}
                </n-statistic>

                <n-statistic label="账号">
                    {{ count.accounts }}
                </n-statistic>

                <n-statistic label="关卡">
                    {{ count.levels }}
                </n-statistic>

                <n-statistic label="评论">
                    {{ count.comments }}
                </n-statistic>
            </n-space>
        </n-card>

        <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
            <n-grid-item>
                <n-card>
                    <template #header>
                        <n-text>这是什么</n-text>
                    </template>

                    <n-text>GDCS 是一个由</n-text>

                    <n-button class="mx-2" href="https://zhazha120.cn" tag="a" text type="primary">
                        渣渣120
                    </n-button>

                    <n-text>独立开发的 开源 GD 私服</n-text>

                    <br>

                    <n-text>仓库地址:&nbsp;</n-text>
                    <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a" text
                              type="primary">
                        Github
                    </n-button>

                    <template #footer>
                        <n-space>
                            <n-button href="https://qm.qq.com/cgi-bin/qm/qr?k=wtUcckkOelZWhD24Tg5HLLr3HNS4XjOv" tag="a">
                                加入讨论群
                            </n-button>

                            <n-button href="https://afdian.net/@WOSHIZHAZHA120" tag="a">
                                支持一下(赞助)
                            </n-button>
                        </n-space>
                    </template>
                </n-card>
            </n-grid-item>

            <n-grid-item>
                <n-card class="w-full">
                    <template #header>
                        <n-text>下载</n-text>
                    </template>

                    <n-text>或者你也可以使用</n-text>
                    <n-text type="info">&nbsp;GDPS Switcher&nbsp;</n-text>
                    <n-text>功能</n-text>

                    <br>

                    <n-text>只需将地址设为&nbsp;</n-text>
                    <n-text type="primary">https://gf.geometrydashchinese.com</n-text>
                    <n-text>&nbsp;就好了</n-text>

                    <template #footer>
                        <n-space>
                            <n-button href="https://cdn.geometrydashchinese.com/client/GDCN.apk" tag="a">
                                安卓
                            </n-button>

                            <n-button href="https://cdn.geometrydashchinese.com/client/GDCN.zip" tag="a">
                                Windows
                            </n-button>

                            <n-button href="https://cdn.geometrydashchinese.com/client/GDCN.exe" tag="a">
                                Windows (单exe)
                            </n-button>

                            <n-button disabled href="https://cdn.geometrydashchinese.com/client/GDCN.ipa" tag="a">
                                IOS 或 MacOS
                            </n-button>
                        </n-space>
                    </template>
                </n-card>
            </n-grid-item>
        </n-grid>

        <n-card>
            <template #header>
                <n-text class="block mx-auto text-center">GDCS 团队</n-text>
                <n-divider/>
            </template>

            <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
                <n-grid-item>
                    <n-descriptions :bordered="true" :column="isMobile ? 1 : 3" title="渣渣120">
                        <n-descriptions-item label="QQ">
                            <n-button href="https://wpa.qq.com/msgrd?uin=2331281251" tag="a" text>
                                2331281251
                            </n-button>
                        </n-descriptions-item>
                        <n-descriptions-item label="职责">
                            开发 | 运维 | 管理
                        </n-descriptions-item>
                        <n-descriptions-item label="哔哩哔哩">
                            <n-button href="https://space.bilibili.com/24267334" tag="a" text>
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
                            <n-button href="https://wpa.qq.com/msgrd?uin=1292866784" tag="a" text>
                                1292866784
                            </n-button>
                        </n-descriptions-item>
                        <n-descriptions-item label="职责">
                            运维 | 管理 | 规则制定
                        </n-descriptions-item>
                        <n-descriptions-item label="哔哩哔哩">
                            <n-button href="https://space.bilibili.com/93653653" tag="a" text>
                                93653653
                            </n-button>
                        </n-descriptions-item>
                    </n-descriptions>
                </n-grid-item>
            </n-grid>
        </n-card>
    </n-space>
</template>
