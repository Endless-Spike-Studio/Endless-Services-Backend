<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {formatTime, isMobile, to_route} from "@/scripts/core/utils";
import {Head, router} from "@inertiajs/vue3";
import LevelInfo from "@/views/components/Info/Level.vue";
import star from "@/images/game/star.png";
import gold_coin from "@/images/game/c0.png";
import silver_coin from "@/images/game/c2.png";
import demon from "@/images/game/difficulties/9.png";
import cp from "@/images/game/cp.png";
import {useWindowSize} from "@vueuse/core";
import {PaginatedData} from "@/types/utils";
import Grid from "@/views/components/Grid.vue";

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
        scores: PaginatedData<App.Models.UserScore>
    }
}>();

const pages = reactive({
    accounts: ref(props.latest.accounts.current_page),
    contests: ref(props.latest.contests.current_page),
    levels: ref(props.latest.levels.current_page),
    ratedLevels: ref(props.latest.ratedLevels.current_page),
    scores: ref(props.latest.scores.current_page)
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

const scoreSorter = reactive({
    name: ref('stars'),
    order: ref('desc')
});

function handlePageUpdate() {
    router.reload({
        data: {
            page_accounts: pages.accounts,
            page_contests: pages.contests,
            page_levels: pages.levels,
            page_ratedLevels: pages.ratedLevels,
            page_scores: pages.scores,
            sort_scores_column: scoreSorter.name,
            sort_scores_order: scoreSorter.order
        },
        only: ['latest']
    });
}

const {width} = useWindowSize();
</script>

<template>
    <CommonLayout>
        <Head>
            <title>Dashboard</title>
        </Head>

        <n-space vertical>
            <n-card :title="account.name">
                <template #header-extra>
                    <n-text :depth="3" class="text-sm">注册于 {{ formatTime(account.created_at) }}</n-text>
                </template>

                <Grid :cols="3" class="text-center">
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
                </Grid>
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

            <Grid :cols="isMobile ? 1 : 3" class="sm:h-[50vh]">
                <n-grid-item class="h-full overflow-auto">
                    <n-card title="新账号">
                        <n-space vertical>
                            <n-list bordered>
                                <n-list-item v-for="account in latest.accounts.data">
                                    <n-thing>
                                        <template #header>
                                            <n-button text type="primary"
                                                      @click="to_route('gdcs.dashboard.account.info', account.id)">
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
            </Grid>

            <n-card title="排行榜">
                <n-space vertical>
                    <n-space :vertical="width < 640" class="items-center" justify="center">
                        <n-space class="items-center" justify="center">
                            <n-text>排序:</n-text>

                            <n-radio-group v-model:value="scoreSorter.name" @update:value="handlePageUpdate">
                                <n-radio-button value="stars">
                                    <n-image :img-props="{ class: 'w-[1em]' }" :src="star"
                                             preview-disabled/>
                                </n-radio-button>

                                <n-radio-button value="coins">
                                    <n-image :img-props="{ class: 'w-[1em]' }" :src="gold_coin"
                                             preview-disabled/>
                                </n-radio-button>

                                <n-radio-button value="user_coins">
                                    <n-image :img-props="{ class: 'w-[1em]' }" :src="silver_coin"
                                             preview-disabled/>
                                </n-radio-button>

                                <n-radio-button value="demons">
                                    <n-image :img-props="{ class: 'w-[1em]' }" :src="demon"
                                             preview-disabled/>
                                </n-radio-button>

                                <n-radio-button value="creator_points">
                                    <n-image :img-props="{ class: 'w-[1em]' }" :src="cp"
                                             preview-disabled/>
                                </n-radio-button>
                            </n-radio-group>
                        </n-space>

                        <n-space class="items-center" justify="center">
                            <n-text>顺序:</n-text>

                            <n-radio-group v-model:value="scoreSorter.order" @update:value="handlePageUpdate">
                                <n-radio-button value="asc">
                                    正序
                                </n-radio-button>

                                <n-radio-button value="desc">
                                    逆序
                                </n-radio-button>
                            </n-radio-group>
                        </n-space>
                    </n-space>

                    <n-list bordered>
                        <n-list-item v-for="(score, i) in latest.scores.data">
                            <n-thing>
                                <template #header>
                                    {{ score.user.name }}
                                </template>

                                <template #header-extra>
                                    <n-text :depth="3">
                                        #{{ i + (latest.scores.current_page - 1) * latest.scores.per_page + 1 }}
                                    </n-text>
                                </template>

                                <n-space vertical>
                                    <Grid :cols="5" class="sm:!w-1/2 sm:mx-auto">
                                        <n-grid-item>
                                            <n-space justify="center" size="small">
                                                <n-image :img-props="{ class: 'w-[1.5em]' }" :src="star"
                                                         preview-disabled/>

                                                <n-text>{{ score.stars }}</n-text>
                                            </n-space>
                                        </n-grid-item>

                                        <n-grid-item>
                                            <n-space justify="center" size="small">
                                                <n-image :img-props="{ class: 'w-[1.5em]' }" :src="gold_coin"
                                                         preview-disabled/>

                                                <n-text>{{ score.coins }}</n-text>
                                            </n-space>
                                        </n-grid-item>

                                        <n-grid-item>
                                            <n-space justify="center" size="small">
                                                <n-image :img-props="{ class: 'w-[1.5em]' }" :src="silver_coin"
                                                         preview-disabled/>

                                                <n-text>{{ score.user_coins }}</n-text>
                                            </n-space>
                                        </n-grid-item>

                                        <n-grid-item>
                                            <n-space justify="center" size="small">
                                                <n-image :img-props="{ class: 'w-[1.5em]' }" :src="demon"
                                                         preview-disabled/>

                                                <n-text>{{ score.demons }}</n-text>
                                            </n-space>
                                        </n-grid-item>

                                        <n-grid-item>
                                            <n-space justify="center" size="small">
                                                <n-image :img-props="{ class: 'w-[1.5em]' }" :src="cp"
                                                         preview-disabled/>

                                                <n-text>{{ score.creator_points }}</n-text>
                                            </n-space>
                                        </n-grid-item>
                                    </Grid>

                                    <n-el class="text-center">
                                        <n-text :depth="3" class="text-sm">
                                            数据更新时间: {{ formatTime(score.updated_at) }}
                                        </n-text>
                                    </n-el>
                                </n-space>
                            </n-thing>
                        </n-list-item>
                    </n-list>

                    <n-pagination v-model:page="pages.scores" :page-count="latest.scores.last_page"
                                  :page-slot="8"
                                  @update:page="handlePageUpdate"/>
                </n-space>
            </n-card>
        </n-space>
    </CommonLayout>
</template>
