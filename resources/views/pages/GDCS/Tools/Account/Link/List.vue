<script lang="ts" setup>
import {NButton, NCard, NDataTable, NPopconfirm, NSpace} from 'naive-ui'
import {formatTime, toRoute} from '@/scripts/helpers'
import {GDCS} from '@/scripts/types/backend'
import {h, watch} from 'vue'
import {useForm} from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'
import servers from '@/scripts/enums/servers'
import {each, find} from 'lodash-es'
import {useGlobalStore} from '@/scripts/stores'

defineProps<{
    links: GDCS.AccountLink[]
}>()

const columns = [
    {
        title: 'ID',
        key: 'id'
    },
    {
        title: '服务器',
        key: 'server',
        render: (row: GDCS.AccountLink) => guessServerName(row.server)
    },
    {
        title: '链接账号',
        key: 'target',
        render: (row: GDCS.AccountLink) => `${row.target_name} [${row.target_account_id}, ${row.target_user_id}]`
    },
    {
        title: '链接时间',
        key: 'created_at',
        render: (row: GDCS.AccountLink) => formatTime(row.created_at, '未知')
    },
    {
        title: '操作',
        key: 'action',
        render: (row: GDCS.AccountLink) => {
            const form = useForm({})

            watch(form, newForm => {
                const globalStore = useGlobalStore()

                each(newForm.errors, (error, field) => {
                    globalStore.$message.error(`[${field}] ${error}`)
                })
            })

            return h(NPopconfirm, {
                onPositiveClick: () => form.delete(
                    route('gdcs.tools.account.link.delete.api', {
                        id: row.id
                    })
                )
            }, {
                default: () => '确认删除 ?',
                trigger: () => h(NButton, {
                    type: 'error',
                    disabled: form.processing
                }, {
                    default: () => '解绑'
                })
            })
        }
    }
]

function guessServerName(server: string) {
    const item = find(servers, {
        host: server
    })

    if (!item) {
        return server
    }

    return `${item.name} [${item.id}]`
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="账号链接">
        <n-space vertical>
            <n-button @click="toRoute('gdcs.tools.account.link.create')">创建新链接</n-button>
            <n-data-table :columns="columns" :data="links"/>
        </n-space>
    </n-card>
</template>
