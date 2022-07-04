<script lang="ts" setup>
import {NButton, NCard, NDataTable, NPopconfirm, NSpace} from "naive-ui"
import {getProp, toRoute, toURL} from "@/scripts/helpers"
import {GDCS, User} from "@/scripts/types/backend"
import {h} from "vue"
import {useForm} from "@inertiajs/inertia-vue3"
import route from "@/scripts/route"

const account = getProp<User>('gdcs.account')
const props = defineProps<{
    songs: GDCS.Song[],
    customSongOffset: number
}>()

const columns = [
    {
        title: 'ID',
        key: 'id'
    },
    {
        title: '歌曲ID',
        key: 'song_id',
        render: (row: GDCS.CustomSong) => row.id + props.customSongOffset
    },
    {
        title: '上传者',
        key: 'uploader',
        render: (row: GDCS.CustomSong) => row.account?.name
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
        render: (row: GDCS.CustomSong) => row.size + ' MB'
    },
    {
        title: '操作',
        key: 'action',
        render: (row: GDCS.CustomSong) => {
            const form = useForm({})

            return h(NSpace, null, () => [
                h(NButton, {
                    disabled: form.processing,
                    onClick: () => toURL(row.download_url)
                }, {
                    default: () => '试听'
                }),
                h(NPopconfirm, {
                    onPositiveClick: () => form.delete(
                        route('gdcs.tools.song.custom.delete.api', {
                            id: row.id
                        })
                    )
                }, {
                    default: () => '确认删除 ?',
                    trigger: () => h(NButton, {
                        type: 'error',
                        disabled: row.account?.id !== account.value.id || form.processing
                    }, {
                        default: () => '删除'
                    })
                })
            ])
        }
    }
]
</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="自定义歌曲">
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
