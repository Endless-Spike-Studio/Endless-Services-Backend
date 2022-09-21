<script lang="ts" setup>
import {App} from "@/scripts/types/backend";
import {isMobile} from "@/scripts/core/utils";
import route from "@/scripts/core/route";
import {useForm} from "@inertiajs/inertia-vue3";

const props = defineProps<{
    song: App.Models.NGProxy.Song | null;
}>();

const songID = ref<number>();
const form = useForm({});
const {decodeURIComponent} = window;

if (props.song !== null) {
    songID.value = props.song.song_id;
}

function querySong() {
    if (songID.value === undefined || songID.value <= 0) {
        return false;
    }

    form.get(
        route('ngproxy.home', {
            id: songID.value
        })
    );
}
</script>

<template layout="NGProxy">
    <n-space class="lg:w-1/3 mx-auto" vertical>
        <n-card class="text-center" title="歌曲查询">
            <n-space justify="center">
                <n-input-number v-model:value="songID" :min="1" :step="1" placeholder="歌曲ID"/>

                <n-button :disabled="form.processing" @click="querySong">
                    查询
                </n-button>
            </n-space>

            <n-el v-if="song">
                <n-divider/>
                <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                    <n-descriptions-item label="ID">
                        {{ song.id }}
                    </n-descriptions-item>
                    <n-descriptions-item label="歌曲ID">
                        {{ song.song_id }}
                    </n-descriptions-item>
                    <n-descriptions-item label="歌曲名">
                        {{ song.name }}
                    </n-descriptions-item>
                    <n-descriptions-item label="歌手ID">
                        {{ song.artist_id }}
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
                    <n-descriptions-item label="缓存时间">
                        {{ new Date(song.created_at).toLocaleString() }}
                    </n-descriptions-item>
                    <n-descriptions-item label="更新时间">
                        {{ new Date(song.updated_at).toLocaleString() }}
                    </n-descriptions-item>
                    <n-descriptions-item label="原始下载地址">
                        <n-button :href="decodeURIComponent(song.original_download_url)" tag="a" text>
                            <n-ellipsis class="!max-w-[100px]">
                                {{ decodeURIComponent(song.original_download_url) }}
                            </n-ellipsis>
                        </n-button>
                    </n-descriptions-item>
                    <n-descriptions-item label="下载地址">
                        <n-button :disabled="!song.valid" :href="song.download_url" tag="a" text>
                            <n-ellipsis class="!max-w-[300px]">
                                {{ song.download_url }}
                            </n-ellipsis>
                        </n-button>
                    </n-descriptions-item>
                </n-descriptions>
            </n-el>
        </n-card>

        <n-card class="text-center" title="开发者">
            <n-thing title="渣渣120">
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
    </n-space>
</template>
