<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App, PaginatedData} from "@/types/backend";
import {formatTime, to_route} from "@/scripts/core/utils";
import {Inertia} from "@inertiajs/inertia";
import LevelInfo from "@/views/components/Info/Level.vue";

const props = defineProps<{
    account: App.Models.Account;
    statistic: {
        comments: number;
        levels: number;
        likes: number;
    }
    latest: {
        contests: PaginatedData<App.Models.Contest>;
        accounts: PaginatedData<App.Models.Account>;
        levels: PaginatedData<App.Models.Level>;
        ratedLevels: PaginatedData<App.Models.LevelRating>;
    }
}>();

const pages = reactive({
    accounts: ref(props.latest.accounts.current_page),
    contests: ref(props.latest.contests.current_page),
    levels: ref(props.latest.levels.current_page),
    ratedLevels: ref(props.latest.ratedLevels.current_page)
});

const latestRatedLevels = computed(() => {
    const paginate = Object.assign({}, props.latest.ratedLevels);

    paginate.data = paginate.data.map(rating => {
        if (rating.level) {
            rating.level.rating = rating;
        }

        return rating;
    });

    return paginate;
});

function handlePageUpdate() {
    Inertia.reload({
        data: {
            page_accounts: pages.accounts,
            page_contests: pages.contests,
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

            <n-card title="最新赛事">
                <n-space v-if="latest.contests && latest.contests.data?.length > 0" vertical>
                    <n-list bordered>
                        <n-list-item v-for="contest in latest.contests.data">
                            <n-thing>
                                <template #header>
                                    {{ contest.name }}
                                </template>

                                <template #description>
                                    <n-space vertical>
                                        <n-text :depth="3" class="text-sm whitespace-pre-wrap">
                                            {{ contest.desc }}
                                        </n-text>

                                        <n-text :depth="3" class="text-sm" type="info">
                                            从 {{ formatTime(contest.created_at) }} 到
                                            {{ contest.deadline_at ? formatTime(contest.deadline_at) : '?' }}
                                        </n-text>
                                    </n-space>
                                </template>
                            </n-thing>

                            <template #suffix>
                                <n-button @click="to_route('gdcs.dashboard.contest.info', contest.id)">查看</n-button>
                            </template>
                        </n-list-item>
                    </n-list>

                    <n-pagination v-model:page="pages.contests" :page-count="latest.contests.last_page"
                                  @update:page="handlePageUpdate"/>
                </n-space>

                <n-empty v-else/>
            </n-card>

            <n-grid :x-gap="10" :y-gap="10" class="lg:h-[50vh]" cols="1 768:3">
                <n-grid-item class="h-full overflow-auto">
                    <n-card title="新账号">
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
                                          :page-slot="8"
                                          @update:page="handlePageUpdate"/>
                        </n-space>
                    </n-card>
                </n-grid-item>

                <n-grid-item class="h-full overflow-auto">
                    <n-card title="新关卡">
                        <n-space vertical>
                            <n-list bordered>
                                <n-list-item v-for="level in latest.levels.data">
                                    <LevelInfo :level="level"/>
                                </n-list-item>
                            </n-list>

                            <n-pagination v-model:page="pages.levels" :page-count="latest.levels.last_page"
                                          :page-slot="8"
                                          @update:page="handlePageUpdate"/>
                        </n-space>
                    </n-card>
                </n-grid-item>

                <n-grid-item class="h-full overflow-auto">
                    <n-card title="新 Rated 关卡">
                        <n-space vertical>
                            <n-list bordered>
                                <n-list-item v-for="rating in latestRatedLevels.data">
                                    <LevelInfo :level="rating.level"/>
                                </n-list-item>
                            </n-list>

                            <n-pagination v-model:page="pages.ratedLevels" :page-count="latest.ratedLevels.last_page"
                                          :page-slot="8"
                                          @update:page="handlePageUpdate"/>
                        </n-space>
                    </n-card>
                </n-grid-item>
            </n-grid>
        </n-space>
    </CommonLayout>
</template>
