<script lang="ts" setup>
import {Head, router} from "@inertiajs/vue3";
import {to_route, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {Base64} from "js-base64";
import {PaginatedData} from "@/types/utils";

const levels = useProp<PaginatedData<App.Models.Level>>('levels');
const page = ref();

function handlePageUpdate(newPage: number) {
    router.reload({
        data: {
            page: newPage
        },
        only: ['levels']
    });
}

nextTick(() => {
    router.reload({
        only: ['levels'],
        onFinish() {
            page.value = levels.value?.current_page;
        }
    });
});
</script>

<template>
    <Head>
        <title>在线工具 - 关卡转移 - 转出 - 关卡选择</title>
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
                                简介: {{ Base64.decode(level.desc) }}
                            </n-text>
                        </template>
                    </n-thing>

                    <template #suffix>
                        <n-button @click="to_route('gdcs.tools.level.transfer.out', level.id)">
                            选择
                        </n-button>
                    </template>
                </n-list-item>
            </n-list>

            <n-pagination v-model:page="page" :page-count="levels.last_page" @update:page="handlePageUpdate"/>
        </n-space>

        <n-empty v-else/>
    </n-card>
</template>
