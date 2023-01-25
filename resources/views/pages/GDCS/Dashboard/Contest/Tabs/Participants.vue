<script lang="ts" setup>
import {formatTime, to_route, useProp} from "@/scripts/core/utils";
import {App, PaginatedData} from "@/types/backend";
import {Inertia} from "@inertiajs/inertia";
import LevelInfo from "@/views/components/Info/Level.vue";

const participants = useProp<PaginatedData<App.Models.ContestParticipant>>('participants');
const page = ref();

nextTick(() => {
    Inertia.reload({
        only: ['participants']
    });
});

function handlePageUpdate(newPage: number) {
    Inertia.reload({
        only: ['participants'],
        data: {
            page: newPage
        }
    });
}
</script>

<template>
    <n-card>
        <n-space v-if="participants && participants.data?.length > 0" vertical>
            <n-list bordered>
                <n-list-item v-for="participant in participants.data">
                    <LevelInfo :level="participant.level"/>

                    <n-el class="lg:text-right">
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
