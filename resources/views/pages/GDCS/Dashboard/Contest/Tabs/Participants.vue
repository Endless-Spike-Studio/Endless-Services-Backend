<script lang="ts" setup>
import {formatTime, to_route, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {Head, router} from "@inertiajs/vue3";
import LevelInfo from "@/views/components/Info/Level.vue";
import {PaginatedData} from "@/types/utils";

const page = ref();
const contest = useProp<App.Models.Contest>('contest');
const participants = useProp<PaginatedData<App.Models.ContestParticipant>>('participants');

nextTick(() => {
    router.reload({
        only: ['participants']
    });
});

function handlePageUpdate(newPage: number) {
    router.reload({
        only: ['participants'],
        data: {
            page: newPage
        }
    });
}
</script>

<template>
    <Head>
        <title>比赛 - {{ contest.name }} - 参与者</title>
    </Head>

    <n-card>
        <n-space v-if="participants && participants.data?.length > 0" vertical>
            <n-list bordered>
                <n-list-item v-for="participant in participants.data">
                    <LevelInfo :level="participant.level"/>

                    <n-el class="sm:text-right">
                        <n-button v-if="participant.account" text type="primary"
                                  @click="to_route('gdcs.dashboard.account.info', participant.account.id)">
                            {{ participant.account.name }}
                        </n-button>

                        投稿于 {{ formatTime(participant.created_at) }}
                    </n-el>
                </n-list-item>
            </n-list>

            <n-pagination v-model:page="page" :page-count="participants.last_page"
                          @update:page="handlePageUpdate"/>
        </n-space>

        <n-empty v-else/>
    </n-card>
</template>
