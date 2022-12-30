<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";
import {to_route} from "@/scripts/core/utils";

const props = defineProps<{
    links: App.Models.AccountLink[]
}>();

const showExtraRefs = props.links.reduce((data, link) => {
    data[link.id] = ref(false);
    return data;
}, {} as Record<number, unknown>);

const forms = props.links.reduce((data, link) => {
    data[link.id] = useForm({});
    return data;
}, {} as Record<number, unknown>);
</script>

<template>
    <CommonLayout>
        <n-card class="lg:w-3/5 lg:mx-auto mx-2.5 mt-2.5" title="账号链接">
            <template #header-extra>
                <n-button @click="to_route('gdcs.tools.account.link.create')">
                    创建新链接
                </n-button>
            </template>

            <n-list v-if="links.length > 0" bordered>
                <n-list-item v-for="link in links">
                    <n-thing>
                        <template #header>
                            <n-button text type="primary"
                                      @click="showExtraRefs[link.id].value = !showExtraRefs[link.id].value">
                                {{ link.target_name }}
                            </n-button>
                        </template>

                        <template #description>
                            <n-collapse-transition :show="showExtraRefs[link.id].value">
                                <n-popover>
                                    <template #trigger>
                                        <n-text type="info">{{ link.server }}</n-text>
                                    </template>

                                    链接的服务器
                                </n-popover>

                                <n-divider vertical/>

                                <n-popover>
                                    <template #trigger>
                                        <n-text type="info">{{ link.target_account_id }}</n-text>
                                    </template>

                                    链接的账号 ID
                                </n-popover>

                                <n-divider vertical/>

                                <n-popover>
                                    <template #trigger>
                                        <n-text type="info">{{ link.target_user_id }}</n-text>
                                    </template>

                                    链接的玩家 ID
                                </n-popover>
                            </n-collapse-transition>
                        </template>
                    </n-thing>

                    <template #suffix>
                        <n-space>
                            <n-button :disabled="forms[link.id].processing" :loading="forms[link.id].processing"
                                      type="error"
                                      @click="forms[link.id].delete(route('gdcs.tools.account.link.delete.api', link.id))">
                                解绑
                            </n-button>
                        </n-space>
                    </template>
                </n-list-item>
            </n-list>

            <n-empty v-else/>
        </n-card>
    </CommonLayout>
</template>
