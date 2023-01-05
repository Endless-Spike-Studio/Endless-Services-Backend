<script lang="ts" setup>
import CommonLayout from "@/views/layouts/NGProxy/Common.vue";
import {GithubOutlined, HeartTwotone, UsergroupAddOutlined} from "@vicons/antd";
import {useWindowSize} from "@vueuse/core";
import {useForm} from "@inertiajs/inertia-vue3";
import {App} from "@/types/backend";
import route from "@/scripts/core/route";

const props = defineProps<{
    song: App.Models.Song;
}>();

const songID = ref(props.song?.song_id);
const form = useForm({});

function submit() {
    form.get(route('ngproxy.info', songID.value));
}

const {width} = useWindowSize();
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-space vertical>
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
            </n-space>

            <n-card title="歌曲查询">
                <n-space vertical>
                    <n-space justify="center">
                        <n-input v-model:value="songID" placeholder="歌曲ID"/>

                        <n-button :disabled="form.processing" :loading="form.processing" @click="submit">
                            查询
                        </n-button>
                    </n-space>

                    <n-el v-if="song" class="lg:w-1/2 mx-auto text-center">
                        <n-descriptions :columns="width < 768 ? 1 : 3" bordered>
                            <n-descriptions-item label="歌曲名">
                                {{ song.name }}
                            </n-descriptions-item>

                            <n-descriptions-item label="歌手名">
                                {{ song.artist_name }}
                            </n-descriptions-item>

                            <n-descriptions-item label="大小">
                                {{ song.size }} MB
                            </n-descriptions-item>

                            <n-descriptions-item label="禁用">
                                {{ song.disabled ? '是' : '否' }}
                            </n-descriptions-item>

                            <n-descriptions-item label="下载地址">
                                <n-button :href="song.download_url" tag="a" text type="primary">
                                    {{ song.download_url }}
                                </n-button>
                            </n-descriptions-item>
                        </n-descriptions>
                    </n-el>
                </n-space>
            </n-card>

            <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
                <n-grid-item>
                    <n-card class="h-full" title="这是什么">
                        NGProxy,全称
                        <n-text type="info">Newgrounds Proxy</n-text>
                        <br>
                        是由
                        <n-button href="https://zhazha120.cn" tag="a" text type="primary">渣渣120</n-button>
                        独立开发的 Geometry Dash 歌曲下载加速服务
                        <br>
                        NGProxy 是开源的,您可以在
                        <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a"
                                  text type="primary">
                            Github
                        </n-button>
                        上查看所有的源代码
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card title="开发者">
                        <n-descriptions :columns="width < 768 ? 1 : 2">
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
