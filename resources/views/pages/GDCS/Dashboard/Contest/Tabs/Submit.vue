<script lang="ts" setup>
import {Head, router, useForm} from "@inertiajs/vue3";
import {useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import LevelInfo from "@/views/components/Info/Level.vue";
import route from "ziggy-js";
import {PaginatedData} from "@/types/utils";

const $emits = defineEmits(['submitted']);

const page = ref();
const contest = useProp<App.Models.Contest>('contest');
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

const forms = computed(() => {
    return levels.value.data?.reduce(function (data, level) {
        data[level.id] = useForm({
            levelID: level.id
        });

        return data;
    }, {} as Record<number, ReturnType<typeof useForm<{}>>>)
});

function submit(id: number) {
    forms.value[id]?.post(route('gdcs.dashboard.contest.submit.api', contest.value.id), {
        onSuccess() {
            $emits('submitted');
        }
    });
}
</script>

<template>
    <Head>
        <title>比赛 - {{ contest.name }} - 投稿</title>
    </Head>

    <n-card>
        <n-space v-if="levels && levels.data?.length > 0" vertical>
            <n-list bordered>
                <n-list-item v-for="level in levels.data">
                    <LevelInfo :level="level"/>

                    <template #suffix>
                        <n-button :disabled="forms[level.id].processing" :loading="forms[level.id].processing"
                                  @click="submit(level.id)">
                            提交
                        </n-button>
                    </template>
                </n-list-item>
            </n-list>

            <n-pagination v-model:page="page" :page-count="levels.last_page"
                          @update:page="handlePageUpdate"/>
        </n-space>

        <n-empty v-else/>
    </n-card>
</template>
