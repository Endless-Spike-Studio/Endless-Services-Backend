<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {Head, router, useForm} from "@inertiajs/vue3";
import {formatTime, guessServer, to_route} from "@/scripts/core/utils";
import route from "ziggy-js";
import {PaginatedData} from "@/types/utils";

const props = defineProps<{
    links: PaginatedData<App.Models.AccountLink>
}>();

const forms = computed(() => {
    return props.links?.data.reduce(function (data, link) {
        data[link.id] = useForm({});
        return data;
    }, {} as Record<number, ReturnType<typeof useForm<{}>>>);
});

const page = ref(props.links.current_page);

function handlePageUpdate(newPage: number) {
    router.reload({
        only: ['links'],
        data: {
            page: newPage
        }
    });
}

function deleteLink(id: number) {
    forms.value[id]?.delete(route('gdcs.tools.account.link.delete.api', id));
}
</script>

<template>
    <CommonLayout>
        <Head>
            <title>在线工具 - 账号链接</title>
        </Head>

        <n-card title="账号链接">
            <template #header-extra>
                <n-button @click="to_route('gdcs.tools.account.link.create')">
                    创建新链接
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
                                    {{ guessServer(link.server) }} [{{ link.target_account_id }}, {{
                                        link.target_user_id
                                    }}]
                                    <br>
                                    链接于 {{ formatTime(link.created_at) }}
                                </n-text>
                            </template>
                        </n-thing>

                        <template #suffix>
                            <n-popconfirm @positive-click="deleteLink(link.id)">
                                <template #trigger>
                                    <n-button :disabled="forms[link.id].processing" :loading="forms[link.id].processing"
                                              type="error">
                                        解绑
                                    </n-button>
                                </template>

                                确定解绑吗
                            </n-popconfirm>
                        </template>
                    </n-list-item>
                </n-list>

                <n-pagination v-model:page="page" :page-count="links.last_page"
                              @update:page="handlePageUpdate"/>
            </n-space>

            <n-empty v-else/>
        </n-card>
    </CommonLayout>
</template>
