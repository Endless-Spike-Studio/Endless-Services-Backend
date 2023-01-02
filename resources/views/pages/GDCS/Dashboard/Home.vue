<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {formatTime, to_route} from "@/scripts/core/utils";
import {Base64} from "js-base64";
import LevelDifficulty from "@/views/components/LevelDifficulty.vue";
import {defaultLevelDesc} from "@/scripts/core/shared";

defineProps<{
    account: App.Models.Account;
    statistic: {
        comments: number;
        levels: number;
        likes: number;
    }
    latest: {
        accounts: App.Models.Account[];
        levels: App.Models.Level[];
        ratedLevels: App.Models.Level[];
    }
}>();
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-card :title="account.name">
                <template #header-extra>
                    <n-text :depth="3" class="text-sm">注册于 {{ formatTime(account.created_at) }}</n-text>
                </template>

                <n-grid :cols="3" :x-gap="10" :y-gap="10" class="text-center">
                    <n-grid-item>
                        <n-statistic :value="statistic.comments.toString()" label="评论了">
                            <template #suffix>次</template>
                        </n-statistic>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.levels.toString()" label="创作了">
                            <template #suffix>关</template>
                        </n-statistic>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.likes.toString()" label="收到了">
                            <template #suffix>赞</template>
                        </n-statistic>
                    </n-grid-item>
                </n-grid>
            </n-card>

            <n-grid :x-gap="10" :y-gap="10" cols="1 768:3">
                <n-grid-item>
                    <n-card class="h-full max-h-[50%] overflow-auto" title="新账号">
                        <n-list bordered>
                            <n-list-item v-for="account in latest.accounts">
                                <n-thing>
                                    <template #header>
                                        <n-button text type="primary"
                                                  @click="to_route('gdcs.account.info', account.id)">
                                            {{ account.name }}
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3" class="text-sm">
                                            注册于 {{ formatTime(account.created_at) }}
                                        </n-text>
                                    </template>
                                </n-thing>
                            </n-list-item>
                        </n-list>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card class="h-full max-h-[50%] overflow-auto" title="新关卡">
                        <n-list bordered>
                            <n-list-item v-for="level in latest.levels">
                                <n-thing>
                                    <template #header>
                                        <n-button text type="primary"
                                                  @click="to_route('gdcs.dashboard.level.info', level.id)">
                                            {{ level.name }} [{{ level.id }}]
                                        </n-button>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3" class="text-sm">
                                            简介:
                                            {{ level.desc ? Base64.decode(level.desc) : defaultLevelDesc }}
                                            <br>
                                            发布于 {{ formatTime(level.created_at) }}
                                        </n-text>
                                    </template>
                                </n-thing>
                            </n-list-item>
                        </n-list>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card class="h-full max-h-[50%] overflow-auto" title="新 Rated 关卡">
                        <n-list bordered>
                            <n-list-item v-for="level in latest.ratedLevels">
                                <n-thing>
                                    <template #header>
                                        <n-button text type="primary"
                                                  @click="to_route('gdcs.dashboard.level.info', level.id)">
                                            {{ level.name }} [{{ level.id }}]
                                        </n-button>
                                    </template>

                                    <template #header-extra>
                                        <LevelDifficulty :rating="level.rating" :size="30"/>
                                    </template>

                                    <template #description>
                                        <n-text :depth="3" class="text-sm">
                                            简介:
                                            {{ level.desc ? Base64.decode(level.desc) : defaultLevelDesc }}
                                            <br>
                                            发布于 {{ formatTime(level.created_at) }}
                                        </n-text>
                                    </template>
                                </n-thing>
                            </n-list-item>
                        </n-list>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
