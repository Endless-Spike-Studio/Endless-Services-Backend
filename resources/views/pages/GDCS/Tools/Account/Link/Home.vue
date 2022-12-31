<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";
import {formatTime, guessServer, to_route} from "@/scripts/core/utils";

const props = defineProps<{
    links: App.Models.AccountLink[]
}>();

const forms = props.links.reduce(function (data, link) {
    data[link.id] = useForm({});
    return data;
}, {} as Record<number, unknown>);
</script>

<template>
    <CommonLayout>
        <n-card title="账号链接">
            <template #header-extra>
                <n-button @click="to_route('gdcs.tools.account.link.create')">
                    创建新链接
                </n-button>
            </template>

            <n-list v-if="links.length > 0" bordered>
                <n-list-item v-for="link in links">
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
                        <n-button :disabled="forms[link.id].processing" :loading="forms[link.id].processing"
                                  type="error"
                                  @click="forms[link.id].delete(route('gdcs.tools.account.link.delete.api', link.id))">
                            解绑
                        </n-button>
                    </template>
                </n-list-item>
            </n-list>

            <n-empty v-else/>
        </n-card>
    </CommonLayout>
</template>
