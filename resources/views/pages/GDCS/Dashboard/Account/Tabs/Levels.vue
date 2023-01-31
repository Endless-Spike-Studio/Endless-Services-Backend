<script lang="ts" setup>
import {Head, router} from "@inertiajs/vue3";
import {formatTime, to_route, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {Base64} from "js-base64";
import LevelDifficulty from "@/views/components/LevelDifficulty.vue";
import {defaultLevelDesc} from "@/scripts/core/shared";
import {PaginatedData} from "@/types/utils";

const page = ref();
const account = useProp<App.Models.Account>('account');
const levels = useProp<PaginatedData<App.Models.Level>>('levels');

nextTick(() => {
    router.reload({
        only: ['levels']
    });
});

function handlePageUpdate(newPage: number) {
    router.reload({
        only: ['levels'],
        data: {
            page: newPage
        }
    });
}
</script>

<template>
    <Head>
        <title>账号 - {{ account.name }} - 关卡</title>
    </Head>

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
