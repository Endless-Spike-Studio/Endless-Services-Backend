<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App, PaginatedData} from "@/types/backend";
import {formatTime, to_route} from "@/scripts/core/utils";
import {Base64} from "js-base64";
import LevelDifficulty from "@/views/components/LevelDifficulty.vue";
import {defaultLevelDesc} from "@/scripts/core/shared";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps<{
    account: App.Models.Account;
    statistic: {
        comments: number;
        levels: number;
        likes: number;
    }
    latest: {
        accounts: PaginatedData<App.Models.Account>;
        levels: PaginatedData<App.Models.Level>;
        ratedLevels: PaginatedData<App.Models.Level>;
    }
}>();

const pages = reactive({
    accounts: ref(props.latest.accounts.current_page),
    levels: ref(props.latest.levels.current_page),
    ratedLevels: ref(props.latest.ratedLevels.current_page)
});

function handlePageUpdate() {
    Inertia.reload({
        data: {
            page_accounts: pages.accounts,
            page_levels: pages.levels,
            page_ratedLevels: pages.ratedLevels
        },
        only: ['latest']
    });
}
</script>

<template>
    <CommonLayout>
        <n-space vertical>
            <n-card :title="account.name">
                <template #header-extra>
                    <n-text :depth="3" class="text-sm">注册于 {{ formatTime(account.created_at) }}</n-text>
                </template>

                <n-grid :cols="3" :x-gap="10" :y-gap="10" class="text-center">
                    <n-grid-item>
                        <n-statistic :value="statistic.comments.toString()" label="评论了">
                            <template #suffix>次</template>
                        </n-statistic>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.levels.toString()" label="创作了">
                            <template #suffix>关</template>
                        </n-statistic>
                    </n-grid-item>

                    <n-grid-item>
                        <n-statistic :value="statistic.likes.toString()" label="收到了">
                            <template #suffix>赞</template>
                        </n-statistic>
                    </n-grid-item>
                </n-grid>
            </n-card>

            <n-grid :x-gap="10" :y-gap="10" cols="1 768:3">
                <n-grid-item>
                    <n-card class="h-full lg:max-h-[50%] overflow-auto" title="新账号">
                        <n-space vertical>
                            <n-list bordered>
                                <n-list-item v-for="account in latest.accounts.data">
                                    <n-thing>
                                        <template #header>
                                            <n-button text type="primary"
                                                      @click="to_route('gdcs.account.info', account.id)">
                                                {{ account.name }}
                                            </n-button>
                                        </template>

                                        <template #description>
                                            <n-text :depth="3" class="text-sm">
                                                注册于 {{ formatTime(account.created_at) }}
                                            </n-text>
                                        </template>
                                    </n-thing>
                                </n-list-item>
                            </n-list>

                            <n-pagination v-model:page="pages.accounts" :page-count="latest.accounts.last_page"
                                          @update:page="handlePageUpdate"/>
                        </n-space>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card class="h-full lg:max-h-[50%] overflow-auto" title="新关卡">
                        <n-space vertical>
                            <n-list bordered>
                                <n-list-item v-for="level in latest.levels.data">
                                    <n-thing>
                                        <template #header>
                                            <n-space>
                                                <LevelDifficulty :rating="level.rating"/>

                                                <n-el class="leading-none">
                                                    <n-button text type="primary"
                                                              @click="to_route('gdcs.dashboard.level.info', level.id)">
                                                        {{ level.name }}
                                                    </n-button>

                                                    <br>

                                                    <n-text v-if="level.creator" :depth="3" class="text-sm">
                                                        By
                                                        <n-button v-if="level.creator.account" text type="primary"
                                                                  @click="to_route('gdcs.account.info', level.creator.account.id)">
                                                            {{ level.creator.account.name }}
                                                        </n-button>

                                                        <n-text v-else-if="level.creator.name">
                                                            {{ level.creator.name }}
                                                        </n-text>

                                                        <n-text v-else>未知</n-text>
                                                    </n-text>
                                                </n-el>
                                            </n-space>
                                        </template>

                                        <template #header-extra>
                                            <n-text :depth="3">#{{ level.id }}</n-text>
                                        </template>

                                        <template #description>
                                            <n-el class="leading-none">
                                                <n-text :depth="3" class="text-sm">
                                                    {{ level.desc ? Base64.decode(level.desc) : defaultLevelDesc }}
                                                    <br><br>
                                                    发布于 {{ formatTime(level.created_at) }}
                                                </n-text>
                                            </n-el>
                                        </template>
                                    </n-thing>
                                </n-list-item>
                            </n-list>

                            <n-pagination v-model:page="pages.levels" :page-count="latest.levels.last_page"
                                          @update:page="handlePageUpdate"/>
                        </n-space>
                    </n-card>
                </n-grid-item>

                <n-grid-item>
                    <n-card class="h-full max-h-[50%] overflow-auto" title="新 Rated 关卡">
                        <n-space vertical>
                            <n-list bordered>
                                <n-list-item v-for="level in latest.ratedLevels.data">
                                    <n-thing>
                                        <template #header>
                                            <n-space>
                                                <LevelDifficulty :rating="level.rating"/>

                                                <n-el class="leading-none">
                                                    <n-button text type="primary"
                                                              @click="to_route('gdcs.dashboard.level.info', level.id)">
                                                        {{ level.name }}
                                                    </n-button>

                                                    <br>

                                                    <n-text v-if="level.creator" :depth="3" class="text-sm">
                                                        By
                                                        <n-button v-if="level.creator.account" text type="primary"
                                                                  @click="to_route('gdcs.account.info', level.creator.account.id)">
                                                            {{ level.creator.account.name }}
                                                        </n-button>

                                                        <n-text v-else-if="level.creator.name">
                                                            {{ level.creator.name }}
                                                        </n-text>

                                                        <n-text v-else>未知</n-text>
                                                    </n-text>
                                                </n-el>
                                            </n-space>
                                        </template>

                                        <template #header-extra>
                                            <n-text :depth="3">#{{ level.id }}</n-text>
                                        </template>

                                        <template #description>
                                            <n-el class="leading-none">
                                                <n-text :depth="3" class="text-sm">
                                                    {{ level.desc ? Base64.decode(level.desc) : defaultLevelDesc }}
                                                    <br><br>
                                                    发布于 {{ formatTime(level.created_at) }}
                                                    <br>
                                                    Rate时间: {{ formatTime(level.rating.created_at) }}
                                                </n-text>
                                            </n-el>
                                        </template>
                                    </n-thing>
                                </n-list-item>
                            </n-list>

                            <n-pagination v-model:page="pages.ratedLevels" :page-count="latest.ratedLevels.last_page"
                                          @update:page="handlePageUpdate"/>
                        </n-space>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
