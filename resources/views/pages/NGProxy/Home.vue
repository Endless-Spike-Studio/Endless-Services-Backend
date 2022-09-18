<script lang="ts" setup>
import {App} from "@/scripts/types/backend";
import {isMobile} from "@/scripts/core/utils";

const props = defineProps<{
    song?: App.Models.NGProxy.Song;
}>();

const songID = ref(props.song?.song_id);
const {decodeURIComponent} = window;
</script>

<template layout="NGProxy">
    <n-card class="mx-auto text-center lg:w-1/3" title="歌曲查询">
        <n-space justify="center">
            <n-input-number v-model:value="songID" :min="1" :step="1" placeholder="歌曲ID"/>

            <n-button :href="('/' + songID)" tag="a">
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
                    <n-button :href="song.download_url" tag="a" text>
                        <n-ellipsis class="!max-w-[100px]">
                            {{ song.download_url }}
                        </n-ellipsis>
                    </n-button>
                </n-descriptions-item>
            </n-descriptions>
        </n-el>
    </n-card>
</template>
