<script lang="ts" setup>
import {Head, router} from "@inertiajs/vue3";
import {formatTime, to_route, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {LikeTwotone} from "@vicons/antd";
import {Base64} from "js-base64";
import {PaginatedData} from "@/types/utils";

const page = ref();
const level = useProp<App.Models.Level>('level');
const comments = useProp<PaginatedData<App.Models.LevelComment>>('comments');

nextTick(() => {
    router.reload({
        only: ['comments']
    });
});

function handlePageUpdate(newPage: number) {
    router.reload({
        only: ['comments'],
        data: {
            page: newPage
        }
    });
}
</script>

<template>
    <Head>
        <title>关卡 - {{ level.name }} - 评论</title>
    </Head>

    <n-card>
        <n-space v-if="comments && comments.data?.length > 0" vertical>
            <n-list bordered>
                <n-list-item v-for="comment in comments.data">
                    <n-thing>
                        <template #header>
                            <n-button text type="primary"
                                      @click="to_route('gdcs.dashboard.account.info', comment.account_id)">
                                {{ comment.account.name }}
                            </n-button>

                            <n-text v-if="comment.percent > 0" :depth="3" class="text-sm">
                                &nbsp;{{ comment.percent }}%
                            </n-text>
                        </template>

                        <template #header-extra>
                            <n-icon :component="LikeTwotone"/>
                            <n-text>&nbsp;{{ comment.likes }}</n-text>
                        </template>

                        <template #description>
                            <n-text :depth="3" class="text-sm">
                                评论于 {{ formatTime(comment.created_at) }}
                            </n-text>
                        </template>

                        {{ Base64.decode(comment.comment) }}
                    </n-thing>
                </n-list-item>
            </n-list>

            <n-pagination v-model:page="page" :page-count="comments.last_page"
                          @update:page="handlePageUpdate"/>
        </n-space>

        <n-empty v-else/>
    </n-card>
</template>
