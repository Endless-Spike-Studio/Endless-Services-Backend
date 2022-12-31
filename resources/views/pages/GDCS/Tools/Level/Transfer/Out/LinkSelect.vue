<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {formatTime, guessServer, to_route} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";

const props = defineProps<{
    levelID: number;
    links: App.Models.AccountLink[]
}>();

const password = ref();
const forms = computed(() => {
    return props.links.reduce(function (data, link) {
        data[link.id] = useForm({
            linkID: link.id,
            password: password.value
        });

        return data;
    }, {} as Record<number, unknown>);
});
</script>

<template>
    <CommonLayout>
        <n-card title="链接选择">
            <template #header-extra>
                <n-button @click="to_route('gdcs.tools.account.link.index')">
                    链接管理
                </n-button>
            </template>

            <n-space vertical>

            </n-space>

            <n-space v-if="links && links.length > 0" vertical>
                <n-input v-model:value="password" placeholder="密码" type="password"/>

                <n-list bordered>
                    <n-list-item v-for="link in links">
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
                            <n-button :disabled="!password || forms[link.id].processing"
                                      :loading="forms[link.id].processing"
                                      @click="forms[link.id].post(route('gdcs.tools.level.transfer.out.api', levelID))">
                                转移
                            </n-button>
                        </template>
                    </n-list-item>
                </n-list>
            </n-space>

            <n-empty v-else/>
        </n-card>
    </CommonLayout>
</template>
