<script lang="ts" setup>
import {App} from "@/scripts/types/backend";
import {decode as DecodeBase64} from "js-base64";
import {ExtraMenuOption} from "@/scripts/types/menu";
import {UserOutlined, UserSwitchOutlined} from "@vicons/antd";
import {NIcon} from "naive-ui";
import {visit_route} from "@/scripts/core/utils";
import {useGeometryDashChineseServerStore} from "@/scripts/core/stores";

const GDCS = useGeometryDashChineseServerStore();
const props = defineProps<{
    account: App.Models.GDCS.Account;
    friends: App.Models.GDCS.Account[];
    comments_count: number;
    levels_count: number;
}>();

const aside = reactive({
    active: ref('profile'),
    options: [
        {
            label: '基本资料',
            key: 'profile',
            icon: () => h(NIcon, {
                component: UserOutlined
            })
        },
        {
            label: '好友',
            key: 'friends',
            icon: () => h(NIcon, {
                component: UserSwitchOutlined
            })
        }
    ] as ExtraMenuOption[]
});
</script>

<template layout="GDCS">
    <n-el class="lg:w-2/3 mx-auto">
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:4">
            <n-grid-item class="text-left">
                <n-card content-style="padding: 0;">
                    <n-menu v-model:value="aside.active" :options="aside.options"/>
                </n-card>
            </n-grid-item>

            <n-grid-item :span="2">
                <n-tabs v-model:value="aside.active" animated tab-style="display: none;">
                    <n-tab-pane name="profile">
                        <n-space vertical>
                            <n-card class="text-center" title="发布的评论">
                                <n-list bordered>
                                    <n-list-item v-if="account.comments.length <= 0">
                                        <n-empty/>
                                    </n-list-item>

                                    <n-list-item v-for="comment in account.comments" v-else>
                                        <n-space justify="space-between">
                                            <n-text>
                                                {{ DecodeBase64(comment.comment) }}
                                            </n-text>

                                            <n-text :depth="3">
                                                发布于 {{ new Date(comment.created_at).toLocaleString() ?? '未知' }}
                                            </n-text>
                                        </n-space>
                                    </n-list-item>
                                </n-list>
                            </n-card>

                            <n-card class="text-center" title="发布的关卡">
                                <n-list bordered>
                                    <n-list-item v-if="account.user.levels.length <= 0">
                                        <n-empty/>
                                    </n-list-item>

                                    <n-list-item v-for="level in account.user.levels" v-else class="text-left">
                                        <n-space justify="space-between">
                                            <n-text>
                                                {{ level.name }} [{{ level.id }}]
                                            </n-text>

                                            <n-text :depth="3">
                                                发布于 {{ new Date(level.created_at).toLocaleString() ?? '未知' }}
                                            </n-text>
                                        </n-space>
                                    </n-list-item>
                                </n-list>
                            </n-card>
                        </n-space>
                    </n-tab-pane>

                    <n-tab-pane name="friends">
                        <n-card class="text-center" title="好友">
                            <n-list bordered>
                                <n-list-item v-if="friends.length <= 0">
                                    <n-empty/>
                                </n-list-item>

                                <n-list-item v-for="friend in friends" v-else>
                                    <n-space justify="space-between">
                                        <n-button v-if="account.id === friend.account_id"
                                                  text type="primary"
                                                  @click="visit_route('gdcs.dashboard.info.account', friend.friend_account.id)">
                                            {{ friend.friend_account.name }} [{{ friend.friend_account.id }}]
                                        </n-button>

                                        <n-button v-else text
                                                  type="primary"
                                                  @click="visit_route('gdcs.dashboard.info.account', friend.account.id)">
                                            {{ friend.account.name }} [{{ friend.account.id }}]
                                        </n-button>

                                        <n-text :depth="3">
                                            添加于 {{ new Date(friend.created_at).toLocaleString() ?? '未知' }}
                                        </n-text>
                                    </n-space>
                                </n-list-item>
                            </n-list>
                        </n-card>
                    </n-tab-pane>
                </n-tabs>
            </n-grid-item>

            <n-grid-item>
                <n-card :title="account.name" class="text-center">
                    <n-space vertical>
                        <n-space justify="center">
                            <n-text>ID: {{ account.id }}</n-text>
                            <n-text>注册时间: {{ new Date(account.created_at).toLocaleString() ?? '未知' }}</n-text>
                        </n-space>

                        <n-divider/>

                        <n-space justify="space-evenly">
                            <n-statistic :value="friends.length" label="好友"/>
                            <n-statistic :value="comments_count" label="评论"/>
                            <n-statistic :value="levels_count" label="关卡"/>
                        </n-space>

                        <n-el v-if="GDCS.account.id === account.id">
                            <n-divider/>

                            <n-space justify="center">
                                <n-text>
                                    UUID: {{ account.user.uuid }}
                                </n-text>

                                <n-text>
                                    UDID:
                                    <n-ellipsis class="!max-w-[75%]">{{ account.user.udid }}</n-ellipsis>
                                </n-text>
                            </n-space>
                        </n-el>
                    </n-space>
                </n-card>
            </n-grid-item>
        </n-grid>
    </n-el>
</template>
