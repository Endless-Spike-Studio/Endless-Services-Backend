<script lang="ts" setup>
import {isMobile, useProp} from "@/scripts/core/utils";
import {App, RemoteLevel} from "@/scripts/types/backend";
import {DataTableColumn} from "naive-ui";
import {Base64} from "js-base64";
import LevelSelectAction from "@/views/pages/GDCS/Tools/Level/Transfer/In/LevelSelectAction.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";

const props = defineProps<{
    linkID: number
}>();

const levelColumns = [
    {
        title: 'ID',
        key: 'id'
    },
    {
        title: '关卡名',
        key: 'name'
    },
    {
        title: '简介',
        render: (item: RemoteLevel) => Base64.isValid(item.desc) ? Base64.decode(item.desc) : '(No description provided)'
    },
    {
        title: '操作',
        render: (item: RemoteLevel) => {
            const form = useForm({
                linkID: props.linkID,
                levelID: -1
            });

            return h(LevelSelectAction, {
                item, form,
                onSelect: (item: RemoteLevel) => {
                    form.levelID = item.id;
                    form.post(route('gdcs.tools.level.transfer.in.api'));
                }
            });
        }
    }
] as DataTableColumn[];

const levels = useProp<App.Models.GDCS.Level[]>('levels');
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/2 mx-auto" title="关卡转入">
        <n-data-table :columns="levelColumns" :data="levels"
                      :max-height="isMobile ? 500 : undefined"
                      :pagination="{ pageSize: 10 }" :scroll-x="isMobile ? 1000 : undefined"
                      :virtual-scroll="isMobile"/>
    </n-card>
</template>
