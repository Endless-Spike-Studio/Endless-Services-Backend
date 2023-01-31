<script lang="ts" setup>
import {formatTime, guessServer, to_route, useProp} from "@/scripts/core/utils";
import {Head, router} from "@inertiajs/vue3";
import {App} from "@/types/backend";
import {PaginatedData} from "@/types/utils";

const links = useProp<PaginatedData<App.Models.AccountLink>>('links');
const page = ref();

nextTick(() => {
    router.reload({
        only: ['links']
    });
});

function handlePageUpdate(newPage: number) {
    router.reload({
        only: ['links'],
        data: {
            page: newPage
        }
    });
}
</script>

<template>
    <Head>
        <title>在线工具 - 关卡转移 - 转入 - 链接选择</title>
    </Head>

    <n-card title="链接选择">
        <template #header-extra>
            <n-button @click="to_route('gdcs.tools.account.link.index')">
                链接管理
            </n-button>
        </template>

        <n-space v-if="links && links.data?.length > 0" vertical>
            <n-list bordered>
                <n-list-item v-for="link in links.data">
                    <n-thing>
                        <template #header>
                            {{ link.target_name }}
                        </template>

                        <template #description>
                            <n-text :depth="3" class="text-sm">
                                {{ guessServer(link.server) }} [{{ link.target_account_id }}, {{ link.target_user_id }}]
                                <br>
                                链接于 {{ formatTime(link.created_at) }}
                            </n-text>
                        </template>
                    </n-thing>

                    <template #suffix>
                        <n-button @click="to_route('gdcs.tools.level.transfer.in', link.id)">
                            选择
                        </n-button>
                    </template>
                </n-list-item>
            </n-list>

            <n-pagination v-model:page="page" :page-count="links.last_page"
                          @update:page="handlePageUpdate"/>
        </n-space>

        <n-empty v-else/>
    </n-card>
</template>
