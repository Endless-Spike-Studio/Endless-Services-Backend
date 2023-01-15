<script lang="ts" setup>
import {Inertia} from "@inertiajs/inertia";
import {formatTime, to_route, useProp} from "@/scripts/core/utils";
import {App, PaginatedData} from "@/types/backend";
import {Base64} from "js-base64";
import LevelDifficulty from "@/views/components/LevelDifficulty.vue";
import {defaultLevelDesc} from "@/scripts/core/shared";

const page = ref();
const levels = useProp<PaginatedData<App.Models.Level>>('levels');

nextTick(() => {
    Inertia.reload({
        only: ['levels']
    });
});

function handlePageUpdate(newPage: number) {
    Inertia.reload({
        only: ['levels'],
        data: {
            page: newPage
        }
    });
}
</script>

<template>
    <n-card>
        <n-space v-if="levels && levels.data?.length > 0" vertical>
            <n-list bordered>
                <n-list-item v-for="level in levels.data">
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

                                    <n-text :depth="3" class="text-sm">
                                        {{ level.desc ? Base64.decode(level.desc) : defaultLevelDesc }}
                                    </n-text>
                                </n-el>
                            </n-space>
                        </template>

                        <template #header-extra>
                            <n-text :depth="3">#{{ level.id }}</n-text>
                        </template>

                        <template #description>
                            <n-text :depth="3" class="text-sm">
                                发布于 {{ formatTime(level.created_at) }}
                            </n-text>
                        </template>
                    </n-thing>
                </n-list-item>
            </n-list>

            <n-pagination v-model:page="page" :page-count="levels.last_page"
                          @update:page="handlePageUpdate"/>
        </n-space>

        <n-empty v-else/>
    </n-card>
</template>
