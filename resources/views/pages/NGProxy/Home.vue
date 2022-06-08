<script lang="ts" setup>
import {
    NButton,
    NCard,
    NCode,
    NDescriptions,
    NDescriptionsItem,
    NDivider,
    NEllipsis,
    NInput,
    NSpace,
    NTabPane,
    NTabs,
    NText
} from "naive-ui";
import Banner from "@/images/NGProxy/Banner.png";
import {isMobile, toURL} from "@/scripts/helpers";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";
import {PropType, ref} from "vue";
import {Song} from "@/scripts/types/backend";
import CommonHome from "@/views/components/CommonHome.vue";

defineProps({
    song: {
        type: Object as PropType<Song>
    }
});

const API = {
    info: {
        "id": 1,
        "song_id": 114514,
        "name": "Im Bad",
        "artist_id": 10740,
        "artist_name": "explode-a-tom",
        "size": "0.18",
        "disabled": 0,
        "created_at": "2022-04-09T05:53:18.000000Z",
        "updated_at": "2022-04-09T05:53:18.000000Z",
        "download_url": "https://ng.geometrydashchinese.com/api/114514/download"
    },
    object: '1~|~114514~|~2~|~Im Bad~|~3~|~10740~|~4~|~explode-a-tom~|~5~|~0.18~|~10~|~https://ng.geometrydashchinese.com/api/114514/download'
}

const songID = ref();
const fetchSongForm = useForm({});
</script>

<template layout="NGProxy">
    <common-home :banner="Banner" group-name="NGProxy 讨论群" team-name="NGProxy 团队">
        <n-space vertical>
            <n-card class="lg:w-1/2 mx-auto" title="歌曲信息查询">
                <n-space justify="center">
                    <n-input v-model:value="songID" placeholder="歌曲ID"/>

                    <n-button :disabled="fetchSongForm.processing"
                              @click="fetchSongForm.get( route('ngproxy.info', { id: songID }) )">
                        查询
                    </n-button>
                </n-space>

                <div v-if="song">
                    <n-divider/>
                    <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                        <n-descriptions-item label="ID">
                            <n-text>{{ song.id }}</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="歌曲ID">
                            <n-text>{{ song.song_id }}</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="歌曲名">
                            <n-text>{{ song.name }}</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="歌手ID">
                            <n-text>{{ song.artist_id }}</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="歌手名">
                            <n-text>{{ song.artist_name }}</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="大小">
                            <n-text>{{ song.size }} MB</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="禁用">
                            <n-text>{{ song.disabled ? '是' : '否' }}</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="缓存时间">
                            <n-text>{{ new Date(song.created_at).toLocaleString() }}</n-text>
                        </n-descriptions-item>
                        <n-descriptions-item label="下载地址">
                            <n-button text @click="toURL(song.download_url)">
                                <n-ellipsis class="!max-w-[100px]">
                                    {{ song.download_url }}
                                </n-ellipsis>
                            </n-button>
                        </n-descriptions-item>
                    </n-descriptions>
                </div>
            </n-card>

            <n-card class="text-center lg:w-2/3 mx-auto pt-5" title="API">
                <n-tabs default-value="info" justify-content="space-evenly" type="line">
                    <n-tab-pane name="info" tab="Info">
                        <n-space vertical>
                            <n-code>GET /api/[歌曲ID]/info</n-code>

                            <n-card class="text-left">
                                <n-code>GET /api/114514/info</n-code>
                                <n-divider/>
                                <n-ellipsis>{{ API.info }}</n-ellipsis>
                            </n-card>
                        </n-space>
                    </n-tab-pane>

                    <n-tab-pane name="gd_object" tab="GD Object">
                        <n-space vertical>
                            <n-code>POST /getGJSongInfo.php</n-code>

                            <n-card class="text-left">
                                <n-space vertical>
                                    <n-code>
                                        POST /getGJSongInfo.php <br>
                                        Content-Type: application/x-www-form-urlencoded
                                    </n-code>

                                    <n-code>songID=114514&secret=Wmfd2893gb7</n-code>
                                </n-space>

                                <n-divider/>

                                <n-ellipsis>{{ API.object }}</n-ellipsis>
                            </n-card>
                        </n-space>
                    </n-tab-pane>

                    <n-tab-pane name="object" tab="Object">
                        <n-space vertical>
                            <n-code>GET /api/[歌曲ID]/object</n-code>

                            <n-card class="text-left">
                                <n-code>GET /api/114514/object</n-code>
                                <n-divider/>
                                <n-ellipsis>{{ API.object }}</n-ellipsis>
                            </n-card>
                        </n-space>
                    </n-tab-pane>
                </n-tabs>
            </n-card>
        </n-space>
    </common-home>
</template>
