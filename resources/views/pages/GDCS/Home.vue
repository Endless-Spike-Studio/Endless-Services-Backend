<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import banner_f1 from "@/images/banner/f1.png";
import banner_f2 from "@/images/banner/f2.png";
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
import {copy, isMobile, to_route} from "@/scripts/core/utils";
import avatar_1 from "@/images/avatars/WOSHIZHAZHA120.png";
import avatar_2 from "@/images/avatars/xyzlol.png";
import {Box} from "@vicons/tabler";
import {Head} from "@inertiajs/vue3";
import Grid from "@/views/components/Grid.vue";

defineProps<{
    statistic: {
        players: number;
        accounts: number;
        moderators: number;
        levels: number;
        levelPacks: number;
        ratedLevels: number;
        comments: number;
        customSongs: number;
        contests: number;
    }
}>();

const windowsDownloadModalShow = ref(false);
</script>

<template>
    <CommonLayout>
        <Head>
            <title>主页</title>
        </Head>

        <n-space vertical>
            <n-space :vertical="isMobile" class="py-20" justify="center">
                <n-el class="sm:w-1/2 px-5 mx-auto">
                    <n-image :img-props="{ class: 'w-full' }" :src="banner_f1" preview-disabled/>
                </n-el>

                <n-el class="sm:w-1/2 px-10 mx-auto">
                    <n-image :img-props="{ class: 'w-full' }" :src="banner_f2" preview-disabled/>
                </n-el>
            </n-space>

            <n-card title="下载">
                <Grid :cols="3" class="[&>*]:mx-auto items-center">
                    <n-grid-item>
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDCS.apk" tag="a" text>
                            <n-space vertical>
                                <n-icon :component="AndroidOutlined" :size="50"/>
                                <n-text class="text-current text-2xl">安卓</n-text>
                            </n-space>
                        </n-button>
                    </n-grid-item>

                    <n-grid-item>
                        <n-button text @click="windowsDownloadModalShow = true">
                            <n-space vertical>
                                <n-icon :component="WindowsOutlined" :size="50"/>
                                <n-text class="text-2xl">Windows</n-text>
                            </n-space>
                        </n-button>
                    </n-grid-item>

                    <n-grid-item>
                        <n-button disabled href="https://cdn.geometrydashchinese.com/client/GDCS.ipa" tag="a"
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

            <n-card class="text-center" title="到目前, GDCS 拥有了">
                <Grid :cols="isMobile ? 3 : 9">
                    <n-grid-item>
                        <n-statistic :value="statistic.players.toString()" label="玩家"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.accounts.toString()" label="账号"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.moderators.toString()" label="Mod"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.levels.toString()" label="关卡"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.levelPacks.toString()" label="关卡包"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.ratedLevels.toString()" label="已评分关卡"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.comments.toString()" label="评论"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.customSongs.toString()" label="自定义歌曲"/>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.contests.toString()" label="比赛"/>
                    </n-grid-item>
                </Grid>
            </n-card>

            <Grid :cols="isMobile ? 1 : 2" class="[&>*>*]:h-full">
                <n-grid-item>
                    <n-card title="这是什么">
                        GDCS, 全称 Geometry Dash Chinese
                        <br>

                        是
                        <n-button href="https://zhazha120.cn" tag="a" text type="primary">渣渣120</n-button>
                        独立开发的 Geometry Dash 服务器后端
                        <br>

                        您可以在
                        <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a"
                                  text type="primary">
                            Github
                        </n-button>

                        上查看所有的源代码
                        <br><br>

                        如果您不想下载客户端, 您也可以使用 GDPS Switcher 功能
                        <br>

                        只需将服务器地址设置为
                        <n-button text type="primary" @click="copy('https://gf.geometrydashchinese.com')">
                            https://gf.geometrydashchinese.com
                        </n-button>
                        就可以开始游玩了
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="管理团队">
                        <Grid :cols="isMobile ? 1 : 2" class="[&>*]:mx-auto">
                            <n-grid-item>
                                <n-space>
                                    <n-space class="text-center leading-none" vertical>
                                        <n-image :src="avatar_1" :width="75"
                                                 class="rounded-full transition duration-1000 ease-in-out hover:rotate-[360deg]"
                                                 preview-disabled/>
                                        <n-text class="font-bold text-2xl">渣渣120</n-text>
                                        <n-text :depth="3">服主</n-text>
                                    </n-space>

                                    <Grid :cols="2" class="[&>*]:leading-none">
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
                                            <n-space vertical>
                                                <n-text class="font-bold">职责</n-text>

                                                <n-space size="small">
                                                    <n-tag>开发</n-tag>
                                                    <n-tag>运维</n-tag>
                                                    <n-tag>管理</n-tag>
                                                </n-space>
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
                            </n-grid-item>

                            <n-grid-item v-if="isMobile">
                                <n-divider class="!my-2.5"/>
                            </n-grid-item>

                            <n-grid-item>
                                <n-space>
                                    <n-space class="text-center leading-none" vertical>
                                        <n-image :src="avatar_2" :width="75"
                                                 class="rounded-full transition duration-1000 ease-in-out hover:rotate-[360deg]"
                                                 preview-disabled/>
                                        <n-text class="font-bold text-2xl">xyzlol</n-text>
                                        <n-text :depth="3">副服主</n-text>
                                    </n-space>

                                    <Grid :cols="2" class="[&>*]:leading-none">
                                        <n-grid-item>
                                            <n-space vertical>
                                                <n-text class="font-bold">QQ</n-text>
                                                <n-button href="https://wpa.qq.com/msgrd?uin=1292866784" tag="a"
                                                          text type="primary">
                                                    1292866784
                                                </n-button>
                                            </n-space>
                                        </n-grid-item>

                                        <n-grid-item>
                                            <n-space vertical>
                                                <n-text class="font-bold">哔哩哔哩</n-text>
                                                <n-button href="https://space.bilibili.com/93653653" tag="a"
                                                          text type="primary">
                                                    93653653
                                                </n-button>
                                            </n-space>
                                        </n-grid-item>

                                        <n-grid-item :span="2">
                                            <n-space vertical>
                                                <n-text class="font-bold">职责</n-text>

                                                <n-space size="small">
                                                    <n-tag>运维</n-tag>
                                                    <n-tag>管理</n-tag>
                                                    <n-tag>规则制定</n-tag>
                                                </n-space>
                                            </n-space>
                                        </n-grid-item>

                                        <n-grid-item :span="2">
                                            <n-space class="mt-2.5">
                                                <n-button href="mailto://1292866784@qq.com" tag="a" text>
                                                    <template #icon>
                                                        <n-icon :component="MailTwotone"/>
                                                    </template>
                                                </n-button>
                                            </n-space>
                                        </n-grid-item>
                                    </Grid>
                                </n-space>
                            </n-grid-item>
                        </Grid>
                    </n-card>
                </n-grid-item>
            </Grid>

            <n-divider>特性</n-divider>

            <Grid :cols="isMobile ? 1 : 3" class="[&>*>*]:h-full">
                <n-grid-item>
                    <n-card title="开源可靠">
                        <n-text :depth="3">
                            您可以在
                            <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a"
                                      text type="primary">
                                Github
                            </n-button>
                            上查看所有的源代码
                        </n-text>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="国内服务">
                        <n-text :depth="3">
                            GDCS 是架设在国内网络上的服务器
                        </n-text>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="自定义歌曲">
                        <n-text :depth="3">
                            可以使用
                            <n-button text type="primary" @click="to_route('gdcs.tools.home')">在线工具</n-button>
                            以外链, 文件等方式创建自定义歌曲
                        </n-text>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="关卡转移">
                        <n-text :depth="3">
                            使用
                            <n-button text type="primary" @click="to_route('gdcs.tools.home')">在线工具</n-button>
                            可以将您在其他服务器的关卡一键转移到当前服务器内
                        </n-text>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="在线信息查看">
                        <n-text :depth="3">
                            使用
                            <n-button text type="primary" @click="to_route('gdcs.dashboard.home')">Dashboard</n-button>
                            可以在网页中查看账号, 关卡等的信息, 无需打开游戏
                        </n-text>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="比赛系统">
                        <n-text :depth="3">
                            使用
                            <n-button text type="primary" @click="to_route('gdcs.dashboard.home')">Dashboard</n-button>
                            可以在网页中查看比赛信息以及投稿
                        </n-text>
                    </n-card>
                </n-grid-item>
            </Grid>
        </n-space>

        <n-modal v-model:show="windowsDownloadModalShow" class="sm:w-1/2 sm:mx-auto mx-2.5" preset="card"
                 title="请选择您要下载的内容">
            <n-el class="py-10">
                <Grid :cols="2" class="[&>*]:mx-auto">
                    <n-grid-item>
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDCS.zip" tag="a" text>
                            <n-space vertical>
                                <n-icon :component="FileZipTwotone" :size="50"/>
                                <n-text :depth="3">.zip</n-text>

                                <n-el class="leading-relaxed">
                                    <n-text class="text-current text-xl">带资源包</n-text>
                                    <br><br>
                                    <n-text type="success">(推荐)</n-text>
                                </n-el>
                            </n-space>
                        </n-button>
                    </n-grid-item>

                    <n-grid-item>
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDCS.exe" tag="a" text>
                            <n-space vertical>
                                <n-icon :component="Box" :size="50"/>
                                <n-text :depth="3">.exe</n-text>

                                <n-el class="leading-relaxed">
                                    <n-text class="text-current text-xl">无资源包</n-text>
                                    <br><br>
                                    <n-text type="warning">(仅供替换)</n-text>
                                    <br>
                                    <n-text type="error">(单独下载无法运行)</n-text>
                                </n-el>
                            </n-space>
                        </n-button>
                    </n-grid-item>
                </Grid>
            </n-el>
        </n-modal>
    </CommonLayout>
</template>
