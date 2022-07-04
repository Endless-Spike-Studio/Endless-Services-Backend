<script setup lang="ts">
import {
    NButton,
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NEmpty,
    NList,
    NListItem,
    NSpace,
    NText,
    NThing
} from "naive-ui"
import {formatTime, isMobile, toRouteWithParams} from "@/scripts/helpers"
import {Base64} from "js-base64"

defineProps<{
    account: GDCS.Account
}>()
</script>

<template layout="GDCS">
    <n-space vertical>
        <n-card class="lg:w-2/3 mx-auto" title="账号信息">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ account.id }}
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ account.name }}
                </n-descriptions-item>
                <n-descriptions-item v-if="account.user.uuid" label="用户">
                    <n-button @click="toRouteWithParams('gdcs.dashboard.user.info', account.user.id)" text>
                        {{ account.user.name }} [{{ account.user.id }}]
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item label="注册时间">
                    {{ formatTime(account.created_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>

        <n-card class="lg:w-2/3 mx-auto" title="账号评论">
            <n-list>
                <n-list-item v-for="comment in account.comments">
                    <n-thing>
                        <template #header>
                            <n-button @click="toRouteWithParams('gdcs.dashboard.account.info', account.id)" text>
                                {{ account.name }}:
                            </n-button>

                            {{ Base64.decode(comment.comment) }}
                        </template>

                        <template #description>
                            <n-text :depth="3">{{ formatTime(comment.created_at, '未知') }}</n-text>
                            <span>, </span>
                            <n-text :depth="3">{{ comment.likes }} Likes</n-text>
                        </template>
                    </n-thing>
                </n-list-item>
            </n-list>

            <n-empty v-if="account.comments.length <= 0"/>
        </n-card>
    </n-space>
</template>
