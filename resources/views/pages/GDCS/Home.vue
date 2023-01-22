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
import {copy, isMobile, to_route} from "@/scripts/core/utils";
import {DropdownOption} from "naive-ui";
import {useApiStore} from "@/scripts/core/stores";
import avatar_1 from "@/images/avatars/WOSHIZHAZHA120.png";
import avatar_2 from "@/images/avatars/xyzlol.png";
import Person from "@/views/components/Person.vue";

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
            <n-card title="下载">
                <n-grid :cols="3">
                    <n-grid-item class="mx-auto">
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDCS.apk" tag="a" text>
                            <n-space vertical>
                                <n-icon :component="AndroidOutlined" :size="50"/>
                                <span class="text-2xl">安卓</span>
                            </n-space>
                        </n-button>
                    </n-grid-item>

                    <n-grid-item class="mx-auto">
                        <n-dropdown :options="windowsDownloadDropdown.options" trigger="click"
                                    @select="windowsDownloadDropdown.handleSelect">
                            <n-button text>
                                <n-space vertical>
                                    <n-icon :component="WindowsOutlined" :size="50"/>
                                    <span class="text-2xl">Windows</span>
                                </n-space>
                            </n-button>
                        </n-dropdown>
                    </n-grid-item>

                    <n-grid-item class="mx-auto">
                        <n-button href="https://cdn.geometrydashchinese.com/client/GDCS.ipa" text
                                  @click="handleAppleDownload">
                            <n-space vertical>
                                <n-icon :component="AppleOutlined" :size="50"/>
                                <span class="text-2xl">苹果</span>
                            </n-space>
                        </n-button>
                    </n-grid-item>
                </n-grid>

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
                <n-grid :x-gap="10" :y-gap="10" cols="3 640:9">
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

            <n-grid :x-gap="10" :y-gap="10" class="[&>*>*]:h-full" cols="1 640:2">
                <n-grid-item>
                    <n-card title="这是什么">
                        GDCS,全称 Geometry Dash Chinese
                        <br>

                        是
                        <n-button href="https://zhazha120.cn" tag="a" text type="primary">渣渣120</n-button>
                        独立开发的 Geometry Dash 私服
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
                        <n-grid :cols="isMobile ? 1 : 2" :x-gap="10" :y-gap="10">
                            <n-grid-item>
                                <Person :avatar="avatar_1"
                                        :bilibili_uid="26247334"
                                        :qq="2331281251"
                                        :works="['开发', '运维', '管理']"
                                        name="渣渣120"/>
                            </n-grid-item>

                            <n-grid-item v-if="isMobile">
                                <n-divider/>
                            </n-grid-item>

                            <n-grid-item>
                                <Person :avatar="avatar_2"
                                        :bilibili_uid="93653653"
                                        :qq="1292866784"
                                        :works="['运维', '管理', '规则制定']"
                                        name="xyzlol"/>
                            </n-grid-item>
                        </n-grid>
                    </n-card>
                </n-grid-item>
            </n-grid>

            <n-divider>特性</n-divider>

            <n-grid :cols="isMobile ? 1 : 3" :x-gap="10" :y-gap="10" class="[&>*>*]:h-full">
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
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
