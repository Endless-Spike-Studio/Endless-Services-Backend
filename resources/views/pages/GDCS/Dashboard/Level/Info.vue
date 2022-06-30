<script lang="ts" setup>
import {formatTime, isMobile, toRouteWithParams} from "@/scripts/helpers";
import {
    NButton,
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NEmpty,
    NList,
    NListItem,
    NPopover,
    NSpace,
    NText,
    NThing
} from "naive-ui";
import {Base64} from "js-base64";
import {GDCS} from "@/scripts/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";
import {isEmpty} from "lodash-es";
import audioTracks from "@/scripts/enums/audioTracks";
import levelLength from "@/scripts/enums/levelLength";
import levelRatingDifficulties from "@/scripts/enums/levelRatingDifficulties";

defineProps<{
    level: GDCS.Level,
    permission: {
        rate: boolean,
        mark: boolean
    }
}>();

const markAsDailyForm = useForm({});
const markAsWeeklyForm = useForm({});
</script>

<template layout="GDCS">
    <n-space vertical>
        <n-card class="lg:w-2/3 mx-auto" title="关卡信息">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ level.id }}
                </n-descriptions-item>
                <n-descriptions-item label="作者">
                    <n-button text @click="toRouteWithParams('gdcs.dashboard.user.info', level.user.id)">
                        {{ level.user.name }} [{{ level.user.id }}]
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ level.name }}
                </n-descriptions-item>
                <n-descriptions-item label="简介">
                    {{ Base64.decode(level.desc ?? 'KE5vIGRlc2NyaXB0aW9uIHByb3ZpZGVkKQ==') }}
                </n-descriptions-item>
                <n-descriptions-item label="下载">
                    {{ level.downloads }}
                </n-descriptions-item>
                <n-descriptions-item label="点赞">
                    {{ level.likes }}
                </n-descriptions-item>
                <n-descriptions-item label="版本">
                    {{ level.version }}
                </n-descriptions-item>
                <n-descriptions-item label="长度">
                    {{ levelLength[level.length] }} [{{ level.length }}]
                </n-descriptions-item>
                <n-descriptions-item label="歌曲">
                    <n-text v-if="level.song_id <= 0" text>
                        {{ audioTracks[level.audio_track + 1] }} [{{ level.audio_track }}]
                    </n-text>

                    <n-button v-else text @click="toRouteWithParams('ngproxy.info', level.song_id)">
                        {{ level.song.name }} [{{ level.song_id }}]
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item v-if="level.original_level_id" label="原关卡">
                    <n-button text @click="toRouteWithParams('gdcs.dashboard.level.info', level.original_level_id)">
                        {{ level.original.name }}
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item label="双人模式">
                    {{ level.two_player ? '是' : '否' }}
                </n-descriptions-item>
                <n-descriptions-item label="对象数量">
                    {{ level.objects }}
                </n-descriptions-item>
                <n-descriptions-item>
                    <template #label>
                        <n-text v-if="level.rating.coin_verified">银币数量</n-text>
                        <n-text v-else>铜币数量</n-text>
                    </template>

                    {{ level.coins }}
                </n-descriptions-item>
                <n-descriptions-item label="请求星星">
                    {{ level.requested_stars }}
                </n-descriptions-item>
                <n-descriptions-item label="不公开 (Unlisted)">
                    {{ level.unlisted ? '是' : '否' }}
                </n-descriptions-item>
                <n-descriptions-item label="低画质模式 (Low Detail Mode, LDM)">
                    {{ level.ldm ? '有' : '无' }}
                </n-descriptions-item>
                <n-descriptions-item label="发布时间">
                    {{ formatTime(level.created_at, '未知') }}
                </n-descriptions-item>
                <n-descriptions-item label="更新时间">
                    {{ formatTime(level.updated_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>

            <template #header-extra>
                <n-text v-if="level.daily">
                    <n-popover>
                        <template #trigger>
                            每日关卡 #{{ level.daily.id }}
                        </template>

                        <n-text>{{ formatTime(level.daily.apply_at, '未知') }}</n-text>
                    </n-popover>
                </n-text>

                <n-text v-if="level.weekly">
                    <n-popover>
                        <template #trigger>
                            每周关卡 #{{ level.weekly.id }}
                        </template>

                        <n-text>{{ formatTime(level.weekly.apply_at, '未知') }}</n-text>
                    </n-popover>
                </n-text>
            </template>

            <template #footer>
                <n-space>
                    <n-button :disabled="!permission.rate"
                              @click="toRouteWithParams('gdcs.dashboard.level.rate', level.id)">
                        评分
                    </n-button>

                    <n-button :disabled="!permission.mark || !isEmpty(level.daily) || !isEmpty(level.weekly)"
                              :loading="markAsDailyForm.processing"
                              @click="markAsDailyForm.post( route('gdcs.admin.level.mark.daily', level.id) )">添加到 Daily
                    </n-button>
                    <n-button :disabled="!permission.mark || !isEmpty(level.daily) || !isEmpty(level.weekly)"
                              :loading="markAsWeeklyForm.processing"
                              @click="markAsWeeklyForm.post( route('gdcs.admin.level.mark.weekly', level.id) )">添加到
                        Weekly
                    </n-button>
                </n-space>
            </template>
        </n-card>

        <n-card v-if="level.rating.id" class="lg:w-2/3 mx-auto" title="关卡评分">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="难度">
                    <n-text v-if="level.rating.difficulty !== 60" text>
                        {{ levelRatingDifficulties[level.rating.difficulty] }}
                    </n-text>

                    <n-text v-else text>
                        {{ level.rating.auto ? 'Auto' : (level.rating.demon ? 'Demon' : '未知') }}
                    </n-text>
                </n-descriptions-item>
                <n-descriptions-item label="特色分 (Featured Score, FS)">
                    {{ level.rating.featured_score }}
                </n-descriptions-item>
                <n-descriptions-item label="史诗级的 (Epic)">
                    {{ level.rating.epic ? '是' : '否' }}
                </n-descriptions-item>
                <n-descriptions-item v-if="level.rating.demon" label="恶魔难度">
                    {{ level.rating.demon_difficulty }}
                </n-descriptions-item>
                <n-descriptions-item label="星星">
                    {{ level.rating.stars }}
                </n-descriptions-item>
                <n-descriptions-item label="银币验证 (Coin Verified, CV)">
                    {{ level.rating.coin_verified ? '是' : '否' }}
                </n-descriptions-item>
                <n-descriptions-item label="评分时间">
                    {{ formatTime(level.rating.created_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>

        <n-card class="lg:w-2/3 mx-auto" title="关卡评论">
            <n-list>
                <n-list-item v-for="comment in level.comments">
                    <n-thing>
                        <template #header>
                            <n-button text
                                      @click="toRouteWithParams('gdcs.dashboard.account.info', comment.account.id)">
                                {{ comment.account.name }}:
                            </n-button>

                            {{ Base64.decode(comment.comment) }}
                        </template>

                        <template #description>
                            <n-text :depth="3">{{ formatTime(comment.created_at, '未知') }}</n-text>
                            <span>, </span>
                            <n-text :depth="3">{{ comment.likes }} Likes</n-text>
                        </template>
                    </n-thing>
                </n-list-item>
            </n-list>

            <n-empty v-if="level.comments.length <= 0"/>
        </n-card>
    </n-space>
</template>
