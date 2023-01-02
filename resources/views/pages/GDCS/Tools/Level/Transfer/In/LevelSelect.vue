<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {InertiaForm, useForm} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import route from "@/scripts/core/route";
import {PaginatedData} from "@/types/backend";

const props = defineProps<{
    linkID: number;
    levels: PaginatedData<{
        id: number;
        name: string;
        description: string;
    }>
}>();

const forms = computed(() => {
    return props.levels.data.reduce(function (data, level) {
        data[level.id] = useForm({
            levelID: level.id
        });

        return data;
    }, {} as Record<number, unknown>);
});

const page = ref(props.levels.current_page + 1);

function handlePageUpdate(newPage: number) {
    Inertia.reload({
        data: {
            page: newPage - 1
        },
        only: ['levels']
    });
}

function transferIn(id: number) {
    (forms.value[id] as InertiaForm<{}>)?.post(route('gdcs.tools.level.transfer.in.api', props.linkID));
}
</script>

<template>
    <CommonLayout>
        <n-card title="关卡选择">
            <n-space v-if="levels && levels.data?.length > 0" vertical>
                <n-list bordered>
                    <n-list-item v-for="level in levels.data">
                        <n-thing>
                            <template #header>
                                {{ level.name }}
                            </template>

                            <template #description>
                                <n-text :depth="3" class="text-sm">
                                    ID: {{ level.id }}
                                    <br>
                                    简介: {{ level.description }}
                                </n-text>
                            </template>
                        </n-thing>

                        <template #suffix>
                            <n-button :disabled="forms[level.id].processing" :loading="forms[level.id].processing"
                                      @click="transferIn(level.id)">
                                转移
                            </n-button>
                        </template>
                    </n-list-item>
                </n-list>

                <n-pagination v-model:page="page" :page-count="levels.last_page" @update:page="handlePageUpdate"/>
            </n-space>

            <n-empty v-else/>
        </n-card>
    </CommonLayout>
</template>
