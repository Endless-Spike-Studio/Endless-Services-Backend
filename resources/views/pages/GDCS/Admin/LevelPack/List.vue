<script setup lang="ts">
import {NButton, NCard, NDataTable, NList, NListItem, NSpace} from "naive-ui";
import {formatTime, toRoute, toRouteWithParams} from "@/scripts/helpers";
import {Model} from "@/scripts/types/backend";
import {h} from "vue";

const columns = [
    {
        'title': '名称',
        'key': 'name'
    },
    {
        type: 'expand',
        renderExpand: (row: Model) => {
            const items = row.levels_info.map(level => {
                return h(NListItem, null, {
                    default: () => h(NButton, {
                        text: true,
                        onClick: () => toRouteWithParams('gdcs.dashboard.level.info', level.id)
                    }, () => `${level.name} [${level.id}]`),
                    suffix: () => h(NButton, {
                        onClick: () => toRouteWithParams('gdcs.dashboard.level.info', level.id)
                    }, () => '查看')
                })
            });

            return h(NList, null, () => items)
        }
    },
    {
        title: '星星',
        key: 'stars'
    },
    {
        title: '金币',
        key: 'coins'
    },
    {
        title: '难度',
        key: 'difficulty_name'
    },
    {
        title: '创建时间',
        key: 'created_at',
        render: (pack: Model) => formatTime(pack.created_at, '未知')
    }
];

defineProps({
    packs: {
        type: Array,
        required: true
    }
});
</script>

<template layout="GDCS">
    <n-card title="关卡包" class="lg:w-2/3 mx-auto">
        <n-space vertical>
            <n-button @click="toRoute('gdcs.admin.pack.create')">创建</n-button>
            <n-data-table :columns="columns" :data="packs"/>
        </n-space>
    </n-card>
</template>
