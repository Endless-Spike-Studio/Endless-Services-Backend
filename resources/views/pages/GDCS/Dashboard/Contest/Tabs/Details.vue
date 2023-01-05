<script lang="ts" setup>
import {useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {contestRules} from "@/scripts/core/shared";

const contest = useProp<App.Models.Contest>('contest');
</script>

<template>
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
