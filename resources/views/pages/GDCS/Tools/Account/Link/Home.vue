<script lang="ts" setup>
import {App} from "@/scripts/types/backend";
import {DataTableColumn} from "naive-ui";
import Action from "./Action.vue";
import Create from "./Create.vue";

defineProps<{
    links: App.Models.GDCS.AccountLink[]
}>();

const columns = [
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
        key: 'target_account',
        render: (item: App.Models.GDCS.AccountLink) => `${item.target_name} [${item.target_account_id}, ${item.target_user_id}]`
    },
    {
        title: '操作',
        key: 'action',
        render: (item: App.Models.GDCS.AccountLink) => h(Action, {item})
    }
] as DataTableColumn[];

const $dialog = useDialog();

function createLink() {
    const dialog = $dialog.create({
        title: '创建账号链接',
        showIcon: false,
        content: () => h(Create)
    });
}
</script>

<template layout="GDCS">
    <n-el class="lg:w-1/2 mx-auto">
        <n-card title="账号链接">
            <template #header-extra>
                <n-button class="float-right" @click="createLink">创建</n-button>
            </template>

            <n-data-table :columns="columns" :data="links"/>
        </n-card>
    </n-el>
</template>
