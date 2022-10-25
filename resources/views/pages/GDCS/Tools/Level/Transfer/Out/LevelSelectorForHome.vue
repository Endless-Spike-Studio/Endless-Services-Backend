<script lang="ts" setup>
import {App} from "@/scripts/types/backend";
import {DataTableColumn, NButton} from "naive-ui";
import {isMobile, useProp, visit_route} from "@/scripts/core/utils";
import {Base64} from "js-base64";

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
        render: (item: App.Models.GDCS.Level) => Base64.isValid(item.desc) ? Base64.decode(item.desc) : '(No description provided)'
    },
    {
        title: '操作',
        render: (item: App.Models.GDCS.Level) => h(NButton, {
            onClick: () => {
                visit_route('gdcs.tools.level.transfer.out.links.load', {
                    level: item.id
                })
            }
        }, () => '选择')
    }
] as DataTableColumn[];

const levels = useProp<App.Models.GDCS.AccountLink[]>('levels');
</script>

<template>
    <n-data-table :columns="levelColumns" :data="levels"
                  :max-height="isMobile ? 500 : undefined"
                  :pagination="{ pageSize: 10 }" :scroll-x="isMobile ? 1000 : undefined"
                  :virtual-scroll="isMobile"/>
</template>
