<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {useBackendStore} from "@/scripts/core/stores";
import {MenuOption, NIcon, NText} from "naive-ui";
import {InboxOutlined} from "@vicons/antd";
import DynamicTab from "@/views/pages/GDCS/Account/Tabs/Dynamic.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";
import {formatTime} from "@/scripts/core/utils";

const props = defineProps<{
    account: App.Models.Account;
    is_owner: boolean;
    statistic: {
        friends: number;
        levels: number;
        comments: number;
    }
}>();

const menu = reactive({
    active: ref('dynamic'),
    options: [
        {
            label: '动态',
            key: 'dynamic',
            icon: () => h(NIcon, {
                component: InboxOutlined
            }),
            render: () => h(DynamicTab)
        }
    ] as MenuOption[]
});

const resendVerificationEmailForm = useForm({});
const backendStore = useBackendStore();

function resendVerificationEmail() {
    resendVerificationEmailForm.post(
        route('gdcs.account.resendVerificationEmail.api')
    );
}
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-grid :x-gap="10" :y-gap="10" cols="1 768:4">
                <n-grid-item>
                    <n-card :content-style="{ padding: 0 }">
                        <n-menu v-model:value="menu.active" :options="menu.options" mode="vertical"/>
                    </n-card>
                </n-grid-item>

                <n-grid-item :span="2">
                    <n-tabs v-model:value="menu.active" :tab-style="{ display: 'none' }" animated pane-class="!p-0">
                        <n-tab-pane v-for="option in menu.options" :name="option.key">
                            <Component :is="option.render"/>
                        </n-tab-pane>
                    </n-tabs>
                </n-grid-item>

                <n-grid-item>
                    <n-space vertical>
                        <n-card :title="account.name" class="text-center">
                            <n-space vertical>
                                <n-space justify="center">
                                    <n-text>ID: {{ account.id }}</n-text>
                                    <n-text>UID: {{ account.user.id ?? '未知' }}</n-text>
                                    <n-text v-if="is_owner && account.user.uuid">UUID: {{ account.user.uuid }}</n-text>
                                    <n-text>注册时间: {{ formatTime(account.created_at) }}</n-text>
                                </n-space>

                                <n-divider v-if="account.user.score"/>

                                <n-grid v-if="account.user.score" :x-gap="10" :y-gap="10" cols="3">
                                    <n-grid-item>
                                        <n-statistic :value="statistic.friends.toString()" label="好友"/>
                                    </n-grid-item>

                                    <n-grid-item>
                                        <n-statistic :value="statistic.levels.toString()" label="关卡"/>
                                    </n-grid-item>

                                    <n-grid-item>
                                        <n-statistic :value="statistic.comments.toString()" label="评论"/>
                                    </n-grid-item>

                                    <n-grid-item>
                                        <n-statistic :value="account.user.score.stars.toString()" label="星星"/>
                                    </n-grid-item>

                                    <n-grid-item>
                                        <n-statistic :value="account.user.score.demons.toString()" label="恶魔"/>
                                    </n-grid-item>

                                    <n-grid-item>
                                        <n-statistic :value="account.user.score.creator_points.toString()">
                                            <template #label>
                                                <n-popover>
                                                    <template #trigger>
                                                        <n-text type="info">CP</n-text>
                                                    </template>

                                                    <n-text>Creator Points</n-text>
                                                </n-popover>
                                            </template>
                                        </n-statistic>
                                    </n-grid-item>
                                </n-grid>

                                <n-el v-if="is_owner">
                                    <n-divider/>

                                    <n-descriptions :columns="1" label-placement="left" size="small">
                                        <n-descriptions-item label="邮箱">
                                            <n-button :href="('mailto:' + account.email)" tag="a" text type="primary">
                                                {{ account.email }}
                                            </n-button>
                                        </n-descriptions-item>

                                        <n-descriptions-item v-if="account.user.udid" label="UDID">
                                            <n-ellipsis class="!max-w-[120px]">
                                                {{ account.user.udid }}
                                            </n-ellipsis>
                                        </n-descriptions-item>
                                    </n-descriptions>
                                </n-el>

                                <n-el v-if="is_owner && !account.email_verified_at">
                                    <n-divider/>
                                    <n-text type="error">你还没有验证你的邮箱</n-text>
                                </n-el>
                            </n-space>
                        </n-card>

                        <n-card v-if="is_owner && !account.email_verified_at">
                            <n-button :disabled="resendVerificationEmailForm.processing"
                                      :loading="resendVerificationEmailForm.processing"
                                      class="w-full text-center" @click="resendVerificationEmail">
                                重发验证邮件
                            </n-button>
                        </n-card>
                    </n-space>
                </n-grid-item>
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
