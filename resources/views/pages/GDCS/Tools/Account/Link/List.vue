<script setup lang="ts">
import {NButton, NCard, NDataTable, NSpace} from "naive-ui";
import {formatTime, toRoute} from "@/scripts/helpers";
import {AccountLink} from "@/scripts/types/backend";
import {h} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

defineProps({
    links: {
        type: Array,
        required: true
    }
});

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
        title: '链接账号',
        key: 'target',
        render: (row: AccountLink) => `${row.target_name} [${row.target_account_id}, ${row.target_user_id}]`
    },
    {
        title: '链接时间',
        key: 'created_at',
        render: (row: AccountLink) => formatTime(row.created_at, '未知')
    },
    {
        title: '操作',
        key: 'action',
        render: (row: AccountLink) => {
            const form = useForm({});

            return h(NSpace, null, () => [
                h(NButton, {
                    disabled: form.processing,
                    onClick: () => form.delete(
                        route('gdcs.tools.account.link.delete.api', {
                            id: row.id
                        })
                    )
                }, {
                    default: () => '解绑'
                })
            ]);
        }
    }
]
</script>

<template layout="GDCS">
    <n-card title="账号链接" class="lg:w-2/3 mx-auto">
        <n-space vertical>
            <n-button @click="toRoute('gdcs.tools.account.link.create')">创建新链接</n-button>
            <n-data-table :columns="columns" :data="links"/>
        </n-space>
    </n-card>
</template>
