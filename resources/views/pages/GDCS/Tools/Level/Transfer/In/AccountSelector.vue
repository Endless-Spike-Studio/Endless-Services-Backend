<script lang="ts" setup>
import {isMobile, useProp, visit_route} from "@/scripts/core/utils";
import {App} from "@/scripts/types/backend";
import {DataTableColumn, NButton} from "naive-ui";

const accountColumns = [
    {
        title: 'ID',
        key: 'id'
    },
    {
        title: '服务器',
        key: 'server'
    },
    {
        title: '绑定账号',
        render: (item: App.Models.GDCS.AccountLink) => `${item.target_name} [${item.target_account_id}, ${item.target_user_id}]`
    },
    {
        title: '操作',
        render: (item: App.Models.GDCS.AccountLink) => h(NButton, {
            onClick: () => {
                visit_route('gdcs.tools.level.transfer.in.levels.load', {
                    link: item.id
                })
            }
        }, () => '选择')
    }
] as DataTableColumn[];

const links = useProp<App.Models.GDCS.AccountLink[]>('links');
</script>

<template layout="GDCS">
    <n-data-table :columns="accountColumns" :data="links"
                  :max-height="isMobile ? 500 : undefined"
                  :pagination="{ pageSize: 10 }" :scroll-x="isMobile ? 1000 : undefined"
                  :virtual-scroll="isMobile"/>
</template>
