<script lang="ts" setup>
import { NButton, NCard, NDataTable, NPopconfirm } from 'naive-ui'
import { formatTime } from '@/scripts/helpers'
import { useForm } from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'
import { GDCS } from '@/scripts/types/backend'
import { watch } from 'vue'
import { useGlobalStore } from '@/scripts/stores'
import { each } from 'lodash-es'

defineProps<{
    logs: GDCS.AccountFailedLog[]
}>()

const columns = [
  {
    title: 'ID',
    key: 'id'
  },
  {
    title: '内容',
    key: 'content'
  },
  {
    title: 'IP',
    key: 'ip'
  },
  {
    title: '操作时间',
    key: 'created_at',
    render: (row: GDCS.AccountFailedLog) => formatTime(row.created_at, '未知')
  }
]

const clearForm = useForm({})
watch(clearForm, newForm => {
  const globalStore = useGlobalStore()

  each(newForm.errors, (error, field) => {
    globalStore.$message.error(`[${field}] ${error}`)
  })
})

function clear () {
  clearForm.delete(
    route('gdcs.account.failed-log.clear.api')
  )
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/2 mx-auto" title="失败日志">
        <n-data-table :columns="columns" :data="logs"/>

        <template #footer>
            <n-popconfirm @positive-click="clear">
                确认清空 ?

                <template #trigger>
                    <n-button :disabled="logs.length <= 0 || clearForm.processing" type="error">清空</n-button>
                </template>
            </n-popconfirm>
        </template>
    </n-card>
</template>
