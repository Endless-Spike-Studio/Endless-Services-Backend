<script lang="ts" setup>
import { GDCS } from '@/scripts/types/backend'
import { NButton, NCard, NDataTable, NSpace } from 'naive-ui'
import { formatTime, toRouteWithParams } from '@/scripts/helpers'
import { h } from 'vue'

defineProps<{
    roles: GDCS.Role[]
}>()

const columns = [
  {
    title: 'ID',
    key: 'id'
  },
  {
    title: '名称',
    key: 'name'
  },
  {
    title: '描述',
    key: 'title'
  },
  {
    title: '作用域',
    key: 'scope',
    render: (role: GDCS.Role) => role.scope ?? '无'
  },
  {
    title: '创建时间',
    key: 'created_at',
    render: (role: GDCS.Role) => formatTime(role.created_at, '未知')
  },
  {
    title: '更新时间',
    key: 'updated_at',
    render: (role: GDCS.Role) => formatTime(role.updated_at, '未知')
  },
  {
    title: '操作',
    key: 'action',
    render: (role: GDCS.Role) => {
      return h(NSpace, null, () => [
        h(NButton, {
          onClick: () => toRouteWithParams('gdcs.admin.account.role.info', [role.id])
        }, () => '查看')
      ])
    }
  }
]
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/2 mx-auto" title="角色管理">
        <n-data-table :columns="columns" :data="roles"/>
    </n-card>
</template>
