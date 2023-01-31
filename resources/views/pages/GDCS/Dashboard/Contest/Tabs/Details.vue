<script lang="ts" setup>
import {formatTime, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {contestRules} from "@/scripts/core/shared";
import {Head} from "@inertiajs/vue3";

const contest = useProp<App.Models.Contest>('contest');
</script>

<template>
    <Head>
        <title>比赛 - {{ contest.name }} - 详细信息</title>
    </Head>

    <n-card>
        <n-space vertical>
            <n-thing>
                <template #header>
                    {{ contest.name }}
                </template>

                <template #description>
                    <n-text :depth="3" class="text-sm whitespace-pre-wrap">
                        {{ contest.desc }}
                    </n-text>
                </template>

                <template #header-extra>
                    <n-text :depth="3">
                        #{{ contest.id }}
                    </n-text>
                </template>
            </n-thing>

            <n-text :depth="3" class="text-sm" type="info">
                从 {{ formatTime(contest.created_at) }} 到
                {{ contest.deadline_at ? formatTime(contest.deadline_at) : '?' }}
            </n-text>

            <n-space>
                <n-tag v-for="rule in JSON.parse(contest.rules) ?? []">
                    <n-popover v-if="contestRules[rule]">
                        <template #trigger>
                            <n-text type="success">{{ contestRules[rule] }}</n-text>
                        </template>

                        {{ rule }}
                    </n-popover>

                    <n-text v-else type="success">{{ rule }}</n-text>
                </n-tag>
            </n-space>
        </n-space>
    </n-card>
</template>
