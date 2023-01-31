<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {Head, router, useForm} from "@inertiajs/vue3";
import route from "ziggy-js";
import {PaginatedData} from "@/types/utils";
import {App} from "@/types/backend";

const props = defineProps<{
    link: App.Models.AccountLink;
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
    }, {} as Record<number, ReturnType<typeof useForm<{}>>>);
});

const page = ref(props.levels.current_page + 1);

function handlePageUpdate(newPage: number) {
    router.reload({
        data: {
            page: newPage - 1
        },
        only: ['levels']
    });
}

function transferIn(id: number) {
    forms.value[id]?.post(route('gdcs.tools.level.transfer.in.api', props.link.id));
}
</script>

<template>
    <CommonLayout>
        <Head>
            <title>在线工具 - 关卡转移 - 转入 - 链接选择 - {{ link.target_name }} - 关卡选择</title>
        </Head>

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
