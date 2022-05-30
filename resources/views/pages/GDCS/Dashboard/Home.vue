<script setup lang="ts">
import {NButton, NCard, NList, NListItem, NSpace, NStatistic, NTabPane, NTabs, NText, NThing} from "naive-ui";
import {formatTime, toRouteWithParams} from "@/scripts/helpers";

defineProps({
    todayRegisteredAccountCount: {
        type: Number,
        required: true
    },
    todayUploadedLevelCount: {
        type: Number,
        required: true
    },
    todayRatedLevelCount: {
        type: Number,
        required: true
    },
    recentRegisteredAccounts: {
        type: Array,
        required: true
    },
    leaderboards: {
        type: Array,
        required: true
    },
    recentUploadedLevels: {
        type: Array,
        required: true
    },
    recentRatedLevels: {
        type: Array,
        required: true
    },
    recentFeaturedLevels: {
        type: Array,
        required: true
    },
    recentEpicLevels: {
        type: Array,
        required: true
    },
});
</script>

<template layout="GDCS">
    <n-space vertical>
        <n-card class="lg:w-1/2 mx-auto" title="今日动态">
            <n-space justify="space-evenly">
                <n-statistic label="新增账号">
                    {{ todayRegisteredAccountCount }}
                </n-statistic>

                <n-statistic label="新增关卡">
                    {{ todayUploadedLevelCount }}
                </n-statistic>

                <n-statistic label="新增 Rated 关卡">
                    {{ todayRatedLevelCount }}
                </n-statistic>
            </n-space>
        </n-card>

        <n-space justify="space-evenly">
            <n-card class="w-[50rem]" title="账号">
                <n-tabs default-value="recent" justify-content="space-evenly" type="line">
                    <n-tab-pane name="recent" tab="最近注册">
                        <n-list>
                            <n-list-item v-for="account in recentRegisteredAccounts">
                                <n-thing>
                                    <template #header>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.account.info', account.id)"
                                                  text>
                                            {{ account.name }}
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3">注册时间: {{ formatTime(account.created_at, '未知') }}</n-text>
                                    </template>
                                </n-thing>

                                <template #suffix>
                                    <n-space vertical>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.account.info', account.id)">
                                            查看
                                        </n-button>
                                    </n-space>
                                </template>
                            </n-list-item>
                        </n-list>
                    </n-tab-pane>

                    <n-tab-pane name="leaderboard" tab="排行榜">
                        <n-list>
                            <n-list-item v-for="score in leaderboards">
                                <n-thing>
                                    <template #header>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.user.info', score.user.id)"
                                                  text>
                                            {{ score.user.name }}
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3">{{ score.stars }} Stars</n-text>
                                    </template>
                                </n-thing>

                                <template #suffix>
                                    <n-space vertical>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.user.info', score.user.id)">
                                            查看
                                        </n-button>
                                    </n-space>
                                </template>
                            </n-list-item>
                        </n-list>
                    </n-tab-pane>
                </n-tabs>
            </n-card>

            <n-card class="w-[50rem]" title="关卡">
                <n-tabs default-value="recent" justify-content="space-evenly" type="line">
                    <n-tab-pane name="recent" tab="最近上传">
                        <n-list>
                            <n-list-item v-for="level in recentUploadedLevels">
                                <n-thing>
                                    <template #header>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)"
                                                  text>
                                            {{ level.name }} [{{ level.id }}]
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3">
                                            <span>By </span>
                                            <n-button
                                                @click="toRouteWithParams('gdcs.dashboard.user.info', level.user.id)"
                                                text>
                                                {{ level.user.name }}
                                            </n-button>
                                            <span>, </span>
                                            <span>上传时间: {{ formatTime(level.created_at, '未知') }}</span>
                                        </n-text>
                                    </template>
                                </n-thing>

                                <template #suffix>
                                    <n-space vertical>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)">查看
                                        </n-button>
                                    </n-space>
                                </template>
                            </n-list-item>
                        </n-list>
                    </n-tab-pane>

                    <n-tab-pane name="rated" tab="最近 Rated">
                        <n-list>
                            <n-list-item v-for="level in recentRatedLevels">
                                <n-thing>
                                    <template #header>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)"
                                                  text>
                                            {{ level.name }} [{{ level.id }}]
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3">
                                            <span>By </span>
                                            <n-button
                                                @click="toRouteWithParams('gdcs.dashboard.user.info', level.user.id)"
                                                text>
                                                {{ level.user.name }}
                                            </n-button>
                                            <span>, </span>
                                            <span>上传时间: {{ formatTime(level.created_at, '未知') }}</span>
                                        </n-text>
                                    </template>
                                </n-thing>

                                <template #suffix>
                                    <n-space vertical>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)">查看
                                        </n-button>
                                    </n-space>
                                </template>
                            </n-list-item>
                        </n-list>
                    </n-tab-pane>

                    <n-tab-pane name="featured" tab="最近 Featured">
                        <n-list>
                            <n-list-item v-for="level in recentFeaturedLevels">
                                <n-thing>
                                    <template #header>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)"
                                                  text>
                                            {{ level.name }} [{{ level.id }}]
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3">
                                            <span>By </span>
                                            <n-button
                                                @click="toRouteWithParams('gdcs.dashboard.user.info', level.user.id)"
                                                text>
                                                {{ level.user.name }}
                                            </n-button>
                                            <span>, </span>
                                            <span>上传时间: {{ formatTime(level.created_at, '未知') }}</span>
                                        </n-text>
                                    </template>
                                </n-thing>

                                <template #suffix>
                                    <n-space vertical>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)">查看
                                        </n-button>
                                    </n-space>
                                </template>
                            </n-list-item>
                        </n-list>
                    </n-tab-pane>

                    <n-tab-pane name="epic" tab="最近 Epic">
                        <n-list>
                            <n-list-item v-for="level in recentEpicLevels">
                                <n-thing>
                                    <template #header>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)"
                                                  text>
                                            {{ level.name }} [{{ level.id }}]
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3">
                                            <span>By </span>
                                            <n-button
                                                @click="toRouteWithParams('gdcs.dashboard.user.info', level.user.id)"
                                                text>
                                                {{ level.user.name }}
                                            </n-button>
                                            <span>, </span>
                                            <span>上传时间: {{ formatTime(level.created_at, '未知') }}</span>
                                        </n-text>
                                    </template>
                                </n-thing>

                                <template #suffix>
                                    <n-space vertical>
                                        <n-button @click="toRouteWithParams('gdcs.dashboard.level.info', level.id)">查看
                                        </n-button>
                                    </n-space>
                                </template>
                            </n-list-item>
                        </n-list>
                    </n-tab-pane>
                </n-tabs>
            </n-card>
        </n-space>
    </n-space>
</template>
