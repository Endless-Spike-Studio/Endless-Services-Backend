<script lang="ts" setup>
import {Inertia} from "@inertiajs/inertia";
import {useProp} from "@/scripts/core/utils";
import {App, PaginatedData} from "@/types/backend";
import LevelInfo from "@/views/components/Info/Level.vue";
import {InertiaForm, useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";

const contest = useProp<App.Models.Contest>('contest');
const levels = useProp<PaginatedData<App.Models.Level>>('levels');
const page = ref();

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

const forms = computed(() => {
    return levels.value.data?.reduce(function (data, level) {
        data[level.id] = useForm({
            levelID: level.id
        });

        return data;
    }, {} as Record<number, unknown>)
});

function submit(id: number) {
    (forms.value[id] as InertiaForm<{}>)?.post(route('gdcs.dashboard.contest.submit.api', contest.value.id));
}
</script>

<template>
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
