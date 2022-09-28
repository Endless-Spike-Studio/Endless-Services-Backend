<script lang="ts" setup>
import {App} from "@/scripts/types/backend";
import {DataTableColumn, NButton, NDataTable} from "naive-ui";
import Action from "./Action.vue";
import {isMobile, useProp, visit_route} from "@/scripts/core/utils";
import Create from "./Create.vue";

const props = defineProps<{
    offset: number;
    songs: App.Models.GDCS.CustomSong[];
}>();

const columns = [
    {
        title: 'ID',
        key: 'id',
        sorter: 'default'
    },
    {
        title: '上传者',
        key: 'account',
        render: (item: App.Models.GDCS.CustomSong) => {
            if (!item.account) {
                return '未知';
            }

            return h(NButton, {
                text: true,
                type: 'info',
                onClick: () => visit_route('gdcs.dashboard.info.account', {
                    account: item.account!.id
                })
            }, () => item.account!.name);
        }
    },
    {
        title: '歌曲ID',
        key: 'song_id',
        render: (item: App.Models.GDCS.CustomSong) => props.offset + item.id
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
        sorter: 'default',
        render: (item: App.Models.GDCS.CustomSong) => item.size + ' MB'
    },
    {
        title: '使用次数',
        key: 'used_count',
        sorter: 'default'
    },
    {
        title: '操作',
        key: 'action',
        render: (item: App.Models.GDCS.CustomSong) => h(Action, {item})
    }
] as DataTableColumn[];

const $dialog = useDialog();

function createSong() {
    const dialog = $dialog.create({
        title: '创建自定义歌曲',
        showIcon: false,
        content: () => h(Create)
    });

    const cancelWatch = watch(useProp<App.Models.GDCS.CustomSong[]>('songs'), (newSongs, oldSongs) => {
        if (newSongs.length > oldSongs.length) {
            cancelWatch();
            dialog.destroy();
        }
    });
}
</script>

<template layout="GDCS">
    <n-el class="lg:w-2/3 mx-auto">
        <n-card title="自定义歌曲">
            <template #header-extra>
                <n-button @click="createSong">创建</n-button>
            </template>

            <n-data-table :columns="columns" :data="songs" :max-height="500" :pagination="{ pageSize: 10 }"
                          :scroll-x="1000"
                          :virtual-scroll="isMobile"/>
        </n-card>
    </n-el>
</template>
