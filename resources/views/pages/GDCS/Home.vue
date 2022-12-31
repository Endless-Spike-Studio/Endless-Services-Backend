<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {
    AndroidOutlined,
    AppleOutlined,
    GithubOutlined,
    HeartTwotone,
    UsergroupAddOutlined,
    WindowsOutlined
} from "@vicons/antd";
import {useWindowSize} from "@vueuse/core";
import {to_route} from "@/scripts/core/utils";
import {DropdownOption} from "naive-ui";
import {useApiStore} from "@/scripts/core/stores";

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

const {width} = useWindowSize();
const windowsDownloadDropdown = reactive({
    options: [
        {
            label: '无资源包 (.exe)',
            key: 'exe',
            onSelect() {
                open('https://cdn.geometrydashchinese.com/client/GDCS.exe');
            }
        },
        {
            label: '带资源包 (.zip)',
            key: 'zip',
            onSelect() {
                open('https://cdn.geometrydashchinese.com/client/GDCS.zip');
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
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-space vertical>
                <n-space justify="center">
                    <n-button href="https://cdn.geometrydashchinese.com/client/GDCS.apk" tag="a">
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

                    <n-button href="https://cdn.geometrydashchinese.com/client/GDCS.ipa"
                              @click="handleAppleDownload()">
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

            <n-card class="text-center" title="到目前, GDCS 拥有了">
                <n-grid :x-gap="10" :y-gap="10" cols="3 768:9">
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
                </n-grid>
            </n-card>

            <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
                <n-grid-item>
                    <n-card class="h-full" title="这是什么">
                        GDCS,全称
                        <n-text type="info">Geometry Dash Chinese</n-text>
                        <br>
                        是由
                        <n-button href="https://zhazha120.cn" tag="a" text type="primary">渣渣120</n-button>
                        独立开发的 Geometry Dash 私服
                        <br>
                        GDCS 是开源的,您可以在
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
                        <n-text type="info">https://gf.geometrydashchinese.com</n-text>
                        就可以开始游玩了
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="管理团队">
                        <n-grid :x-gap="10" :y-gap="10" cols="1 384:2">
                            <n-grid-item>
                                <n-descriptions :columns="width < 768 ? 1 : 2">
                                    <template #header>
                                        <n-text type="info">渣渣120</n-text>
                                        <n-text :depth="3" class="text-sm ml-1">服主</n-text>
                                    </template>

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

                                    <n-descriptions-item label="职责">
                                        开发 | 运维 | 管理
                                    </n-descriptions-item>
                                </n-descriptions>
                            </n-grid-item>

                            <n-grid-item>
                                <n-descriptions :columns="width < 768 ? 1 : 2">
                                    <template #header>
                                        <n-text type="info">xyzlol</n-text>
                                        <n-text :depth="3" class="text-sm ml-1">副服主</n-text>
                                    </template>

                                    <n-descriptions-item label="QQ">
                                        <n-button href="https://wpa.qq.com/msgrd?uin=1292866784" tag="a" text
                                                  type="primary">1292866784
                                        </n-button>
                                    </n-descriptions-item>

                                    <n-descriptions-item label="哔哩哔哩">
                                        <n-button href="https://space.bilibili.com/93653653" tag="a" text
                                                  type="primary">93653653
                                        </n-button>
                                    </n-descriptions-item>

                                    <n-descriptions-item label="职责">
                                        运维 | 管理 | 规则制定
                                    </n-descriptions-item>
                                </n-descriptions>
                            </n-grid-item>
                        </n-grid>
                    </n-card>
                </n-grid-item>
            </n-grid>

            <n-divider>特 性</n-divider>

            <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
                <n-grid-item>
                    <n-card class="h-full" title="开源可靠">
                        您可以在
                        <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a"
                                  text type="primary">
                            Github
                        </n-button>
                        上查看所有的源代码
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="国内服务">
                        众所周知, 由于
                        <n-text type="info">官方服务器位于国外</n-text>
                        导致体验不是很友好（
                        <br>
                        GDCS 是
                        <n-text type="info">架设在国内网络上的服务器</n-text>
                        , 速度很快, 体验良好
                        <br>
                        <n-text :depth="3">但是请不要忘记官服, 记得常回去看看 (</n-text>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="自定义歌曲">
                        使用
                        <n-button text type="primary" @click="to_route('gdcs.tools.home')">在线工具</n-button>
                        以
                        <n-text type="info">外链, 文件等方式</n-text>
                        以创建自定义歌曲
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="自定义歌曲">
                        使用
                        <n-button text type="primary" @click="to_route('gdcs.tools.home')">在线工具</n-button>
                        可以将
                        <n-text type="info">您在其他服务器的关卡</n-text>
                        一键转移到当前服务器里
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card class="h-full" title="在线信息查看">
                        使用
                        <n-button text type="primary" @click="to_route('gdcs.dashboard.home')">Dashboard</n-button>
                        可以在网页中查看
                        <n-text type="info">账号, 关卡等</n-text>
                        的信息, 无需打开游戏
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="比赛系统">
                        使用
                        <n-button text type="primary" @click="to_route('gdcs.dashboard.home')">Dashboard</n-button>
                        可以在网页中
                        <n-text type="info">查看比赛信息, 参加及投关</n-text>
                        <br>
                        <n-text :depth="3">这不比之前在群里的办比赛好太多了 (</n-text>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
