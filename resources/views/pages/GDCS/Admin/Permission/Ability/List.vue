<script lang="ts" setup>
import { GDCS } from '@/scripts/types/backend'
import { NButton, NCard, NDataTable, NSpace } from 'naive-ui'
import { formatTime, toRouteWithParams } from '@/scripts/helpers'
import { h } from 'vue'

defineProps<{
    abilities: GDCS.Ability[]
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
    title: '目标',
    key: 'entity',
    render: (ability: GDCS.Ability) => {
      if (!ability.entity_type) {
        return '未指定'
      }

      if (ability.entity_type === '*') {
        return '所有'
      }

      return `${ability.entity_type} #${ability.entity_id}`
    }
  },
  {
    title: '作用域',
    key: 'scope',
    render: (ability: GDCS.Ability) => ability.scope ?? '无'
  },
  {
    title: '创建时间',
    key: 'created_at',
    render: (ability: GDCS.Ability) => formatTime(ability.created_at, '未知')
  },
  {
    title: '更新时间',
    key: 'updated_at',
    render: (ability: GDCS.Ability) => formatTime(ability.updated_at, '未知')
  },
  {
    title: '操作',
    key: 'action',
    render: (ability: GDCS.Ability) => {
      return h(NSpace, null, () => [
        h(NButton, {
          onClick: () => toRouteWithParams('gdcs.admin.account.ability.info', [ability.id])
        }, () => '查看')
      ])
    }
  }
]
</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="能力管理">
        <n-data-table :columns="columns" :data="abilities"/>
    </n-card>
</template>
