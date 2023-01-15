<script lang="ts" setup>
import LevelDifficulty from "@/views/components/LevelDifficulty.vue";
import {App} from "@/types/backend";
import {Base64} from "js-base64";
import {formatTime, to_route} from "@/scripts/core/utils";
import {defaultLevelDesc} from "@/scripts/core/shared";

defineProps<{
    level: App.Models.Level;
}>();
</script>

<template>
    <n-thing>
        <template #header>
            <n-space>
                <LevelDifficulty :rating="level.rating"/>

                <n-el class="leading-none">
                    <n-button text type="primary"
                              @click="to_route('gdcs.dashboard.level.info', level.id)">
                        {{ level.name }}
                    </n-button>

                    <br>

                    <n-text v-if="level.creator" :depth="3" class="text-sm">
                        By
                        <n-button v-if="level.creator.account" text type="primary"
                                  @click="to_route('gdcs.dashboard.account.info', level.creator.account.id)">
                            {{ level.creator.account.name }}
                        </n-button>

                        <n-text v-else-if="level.creator.name">
                            {{ level.creator.name }}
                        </n-text>

                        <n-text v-else>未知</n-text>
                    </n-text>
                </n-el>
            </n-space>
        </template>

        <template #header-extra>
            <n-text :depth="3">#{{ level.id }}</n-text>
        </template>

        <template #description>
            <n-el class="leading-none">
                <n-text :depth="3" class="text-sm">
                    {{ level.desc ? Base64.decode(level.desc) : defaultLevelDesc }}
                    <br><br>
                    发布于 {{ formatTime(level.created_at) }}

                    <n-el v-if="level.rating?.created_at">
                        Rate 时间: {{ formatTime(level.rating.created_at) }}
                    </n-el>
                </n-text>
            </n-el>
        </template>
    </n-thing>
</template>
