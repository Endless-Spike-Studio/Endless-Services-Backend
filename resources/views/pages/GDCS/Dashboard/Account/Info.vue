<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {useBackendStore} from "@/scripts/core/stores";
import {MenuOption, NIcon, NText} from "naive-ui";
import CommentsTab from "@/views/pages/GDCS/Dashboard/Account/Tabs/Comments.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";
import {formatTime} from "@/scripts/core/utils";
import {CommentRegular} from "@vicons/fa";
import {Box} from "@vicons/tabler";
import LevelsTab from "@/views/pages/GDCS/Dashboard/Account/Tabs/Levels.vue";
import {SettingTwotone} from "@vicons/antd";
import SettingsTab from "@/views/pages/GDCS/Dashboard/Account/Tabs/Settings.vue";

const props = defineProps<{
    account: App.Models.Account;
    statistic: {
        friends: number;
        levels: number;
        comments: number;
    }
}>();

const backendStore = useBackendStore();
const is_owner = backendStore.gdcs.account.id === props.account.id;

const menu = reactive({
    active: ref('comments'),
    options: [
        {
            label: '评论',
            key: 'comments',
            icon: () => h(NIcon, {
                component: CommentRegular
            }),
            render: () => h(CommentsTab)
        },
        {
            label: '关卡',
            key: 'levels',
            icon: () => h(NIcon, {
                component: Box
            }),
            render: () => h(LevelsTab)
        },
        {
            label: '管理',
            type: 'group',
            key: 'manage',
            show: is_owner,
            children: [
                {
                    label: '设置',
                    key: 'settings',
                    icon: () => h(NIcon, {
                        component: SettingTwotone
                    }),
                    render: () => h(SettingsTab, {
                        onSubmitted() {
                            menu.active = 'comments';
                        }
                    })
                }
            ] as MenuOption[]
        }
    ] as MenuOption[],
    items: computed(() => {
        const result = new Array<MenuOption>;

        menu.options.forEach(option => {
            if ('render' in option) {
                result.push(option as MenuOption);
            }

            if ('children' in option) {
                result.push(...option.children as MenuOption[]);
            }
        });

        return result;
    })
});

const resendVerificationEmailForm = useForm({});

function resendVerificationEmail() {
    resendVerificationEmailForm.post(
        route('gdcs.dashboard.account.resendVerificationEmail.api')
    );
}
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-grid :x-gap="10" :y-gap="10" cols="1 640:4">
                <n-grid-item>
                    <n-card :content-style="{ padding: 0 }">
                        <n-menu v-model:value="menu.active" :options="menu.options" mode="vertical"/>
                    </n-card>
                </n-grid-item>

                <n-grid-item :span="2">
                    <n-tabs v-model:value="menu.active" :tab-style="{ display: 'none' }" animated pane-class="!p-0">
                        <n-tab-pane v-for="option in menu.items" :name="option.key">
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
