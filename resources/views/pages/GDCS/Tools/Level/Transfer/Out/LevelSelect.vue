<script lang="ts" setup>
import {Inertia} from "@inertiajs/inertia";
import {to_route, useProp} from "@/scripts/core/utils";
import {App, PaginatedData} from "@/types/backend";
import {Base64} from "js-base64";

const levels = useProp<PaginatedData<App.Models.Level>>('levels');
const page = ref();

function handlePageUpdate(newPage: number) {
    Inertia.reload({
        data: {
            page: newPage
        },
        only: ['levels']
    });
}

nextTick(() => {
    Inertia.reload({
        only: ['levels'],
        onFinish() {
            page.value = levels.value?.current_page;
        }
    });
});
</script>

<template>
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
