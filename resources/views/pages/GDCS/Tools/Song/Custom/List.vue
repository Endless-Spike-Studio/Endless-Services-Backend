<script setup lang="ts">
import {NButton, NCard, NDataTable, NSpace} from "naive-ui";
import {getProp, toRoute, toURL} from "@/scripts/helpers";
import {CustomSong, User} from "@/scripts/types/backend";
import {h} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

const account = getProp<User>('gdcs.account');
const props = defineProps({
    songs: {
        type: Array,
        required: true
    },
    customSongOffset: {
        type: Number,
        required: true
    }
});

const columns = [
    {
        title: 'ID',
        key: 'id'
    },
    {
        title: '歌曲ID',
        key: 'song_id',
        render: (row: CustomSong) => row.id + props.customSongOffset
    },
    {
        title: '上传者',
        key: 'uploader',
        render: (row: CustomSong) => row.account?.name
    },
    {
        title: '歌曲名',
        key: 'name'
    },
    {
        title: '歌手名',
        key: 'artist_name'
    },
    {
        title: '大小',
        key: 'size',
        render: (row: CustomSong) => row.size + ' MB'
    },
    {
        title: '操作',
        key: 'action',
        render: (row: CustomSong) => {
            const form = useForm({});

            return h(NSpace, null, () => [
                h(NButton, {
                    disabled: form.processing,
                    onClick: () => toURL(row.download_url)
                }, {
                    default: () => '试听'
                }),
                h(NButton, {
                    disabled: row.account?.id !== account.value.id || form.processing,
                    onClick: () => form.delete(
                        route('gdcs.tools.song.custom.delete.api', {
                            id: row.id
                        })
                    )
                }, {
                    default: () => '删除'
                })
            ]);
        }
    }
]
</script>

<template layout="GDCS">
    <n-card title="自定义歌曲" class="lg:w-2/3 mx-auto">
        <n-space vertical>
            <n-space>
                <n-button @click="toRoute('gdcs.tools.song.custom.create.link')">使用外链创建</n-button>
                <n-button @click="toRoute('gdcs.tools.song.custom.create.netease')">使用网易云音乐创建</n-button>
                <n-button disabled @click="toRoute('gdcs.tools.song.custom.create.file')">使用文件创建</n-button>
            </n-space>

            <n-data-table :columns="columns" :data="songs"/>
        </n-space>
    </n-card>
</template>
