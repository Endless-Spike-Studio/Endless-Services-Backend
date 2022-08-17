<script lang="ts" setup>
import {NButton, NCard, NDescriptions, NDescriptionsItem, NSpace} from 'naive-ui'
import {formatTime, isMobile, toRouteWithParams} from '@/scripts/helpers'
import {GDCS} from '@/scripts/types/backend'

defineProps<{
    user: GDCS.User
}>()
</script>

<template layout="GDCS">
    <n-space vertical>
        <n-card class="lg:w-2/3 mx-auto" title="用户信息">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ user.id }}
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ user.name }}
                </n-descriptions-item>
                <n-descriptions-item v-if="user.account" label="账号">
                    <n-button text @click="toRouteWithParams('gdcs.dashboard.account.info', user.account.id)">
                        {{ user.account.name }} [{{ user.account.id }}]
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item label="创建时间">
                    {{ formatTime(user.created_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>

        <n-card v-if="user.score.id" class="lg:w-2/3 mx-auto" title="用户分数">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ user.score.id }}
                </n-descriptions-item>
                <n-descriptions-item label="星星">
                    {{ user.score.stars }}
                </n-descriptions-item>
                <n-descriptions-item label="恶魔">
                    {{ user.score.demons }}
                </n-descriptions-item>
                <n-descriptions-item label="创作积分 (Creator Points, CP)">
                    {{ user.score.creator_points }}
                </n-descriptions-item>
                <n-descriptions-item label="金币">
                    {{ user.score.coins }}
                </n-descriptions-item>
                <n-descriptions-item label="银币">
                    {{ user.score.user_coins }}
                </n-descriptions-item>
                <n-descriptions-item label="更新时间">
                    {{ formatTime(user.score.updated_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>
    </n-space>
</template>
