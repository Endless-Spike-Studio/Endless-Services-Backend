<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {formatTime, to_route} from "@/scripts/core/utils";
import {App, PaginatedData} from "@/types/backend";
import {Inertia} from "@inertiajs/inertia";
import route from "@/scripts/core/route";
import {InertiaForm, useForm} from "@inertiajs/inertia-vue3";

const props = defineProps<{
    offset: number;
    currentAccountID: number;
    songs: PaginatedData<App.Models.CustomSong>
}>();

const page = ref(props.songs.current_page);
const uploadedRouteName = 'gdcs.tools.song.custom.uploaded';

const forms = computed(() => {
    return props.songs.data.reduce(function (data, song) {
        data[song.id] = useForm({});
        return data;
    }, {} as Record<number, unknown>);
});

const currentRouteIsUploaded = computed(() => {
    return route().current() === uploadedRouteName;
});

function handlePageUpdate(newPage: number) {
    Inertia.reload({
        data: {
            page: newPage
        },
        only: ['songs']
    });
}

function deleteSong(id: number) {
    (forms.value[id] as InertiaForm<{}>)?.delete(route('gdcs.tools.song.custom.delete.api', id));
}
</script>

<template>
    <CommonLayout>
        <n-card title="自定义歌曲">
            <template #header-extra>
                <n-space>
                    <n-button v-if="currentRouteIsUploaded" type="primary"
                              @click="to_route('gdcs.tools.song.custom.index')">
                        返回
                    </n-button>

                    <n-button v-else @click="to_route(uploadedRouteName)">
                        你上传的
                    </n-button>

                    <n-button @click="to_route('gdcs.tools.song.custom.create')">创建新歌曲</n-button>
                </n-space>
            </template>

            <n-space v-if="songs && songs.data?.length > 0" vertical>
                <n-list bordered>
                    <n-list-item v-for="song in songs.data">
                        <n-thing>
                            <template #header>
                                {{ song.name }}
                            </template>

                            <template #description>
                                <n-text :depth="3" class="text-sm">
                                    ID: {{ offset + song.id }}
                                    <br>
                                    歌手: {{ song.artist_name }}
                                    <br>
                                    大小: {{ song.size }} MB
                                    <br>
                                    <n-button v-if="song.account" text
                                              type="primary"
                                              @click="to_route('gdcs.dashboard.account.info', song.account.id)">
                                        {{ song.account.name }}
                                    </n-button>
                                    <span v-else>未知</span>
                                    上传于 {{ formatTime(song.created_at) }}
                                </n-text>
                            </template>
                        </n-thing>

                        <template #suffix>
                            <n-space>
                                <n-popconfirm v-if="song.account_id === currentAccountID"
                                              @positive-click="deleteSong(song.id)">
                                    <template #trigger>
                                        <n-button :disabled="forms[song.id].processing"
                                                  :loading="forms[song.id].processing" type="error">
                                            删除
                                        </n-button>
                                    </template>

                                    确定删除吗
                                </n-popconfirm>

                                <n-button :href="song.download_url" tag="a">试听</n-button>
                            </n-space>
                        </template>
                    </n-list-item>
                </n-list>

                <n-pagination v-model:page="page" :page-count="songs.last_page" @update:page="handlePageUpdate"/>
            </n-space>

            <n-empty v-else/>
        </n-card>
    </CommonLayout>
</template>
