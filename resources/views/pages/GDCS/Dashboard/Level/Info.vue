<script lang="ts" setup>
import { formatTime, getProp, isMobile, toRouteWithParams } from '@/scripts/helpers'
import {
  NButton,
  NCard,
  NDescriptions,
  NDescriptionsItem,
  NEmpty,
  NInput,
  NInputNumber,
  NList,
  NListItem,
  NPopover,
  NSelect,
  NSpace,
  NSwitch,
  NTabPane,
  NTabs,
  NText,
  NThing
} from 'naive-ui'
import { Base64 } from 'js-base64'
import { GDCS } from '@/scripts/types/backend'
import { useForm } from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'
import { each, isEmpty, map, random } from 'lodash-es'
import levelLength from '@/scripts/enums/levelLength'
import levelRatingDifficulties from '@/scripts/enums/levelRatingDifficulties'
import { computed, reactive, watch } from 'vue'
import audioTracks from '@/scripts/enums/audioTracks'
import { useGlobalStore } from '@/scripts/stores'

const options = map(audioTracks, (value, index) => {
  return {
    label: value,
    value: index
  }
})

const props = defineProps<{
    level: GDCS.Level,
    permission: {
        rate: boolean,
        mark: boolean
    }
}>()

const levelUpdateForm = useForm({
  name: props.level.name,
  desc: Base64.decode(props.level.desc ?? 'KE5vIGRlc2NyaXB0aW9uIHByb3ZpZGVkKQ=='),
  audio_track: props.level.audio_track.toString(),
  song_id: props.level.song_id,
  password: props.level.password.toString(),
  requested_stars: props.level.requested_stars,
  unlisted: props.level.unlisted
})

watch(levelUpdateForm, newForm => {
  const globalStore = useGlobalStore()

  each(newForm.errors, (error, field) => {
    globalStore.$message.error(`[${field}] ${error}`)
  })
})

const changing = reactive({
  name: false,
  desc: false,
  password: false,
  song: false,
  requested_stars: false
})

const markAsDailyForm = useForm({})
watch(markAsDailyForm, newForm => {
  const globalStore = useGlobalStore()

  each(newForm.errors, (error, field) => {
    globalStore.$message.error(`[${field}] ${error}`)
  })
})

const markAsWeeklyForm = useForm({})
watch(markAsWeeklyForm, newForm => {
  const globalStore = useGlobalStore()

  each(newForm.errors, (error, field) => {
    globalStore.$message.error(`[${field}] ${error}`)
  })
})

const isLevelOwner = computed(() => getProp('gdcs.user.id').value === props.level.user_id)

function handleSongTypeUpdate (value: string) {
  if (value === 'official') {
    levelUpdateForm.audio_track = props.level.audio_track.toString()
    levelUpdateForm.song_id = 0
  }

  if (value === 'newgrounds') {
    levelUpdateForm.song_id = props.level.song_id
    levelUpdateForm.audio_track = '0'
  }
}
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
                    <n-space>
                        <n-text v-if="!changing.name">{{ levelUpdateForm.name }}</n-text>
                        <n-input v-else v-model:value="levelUpdateForm.name"/>

                        <n-button v-if="isLevelOwner" text type="primary" @click="changing.name = !changing.name">
                            {{ !changing.name ? '(修改)' : '(保存)' }}
                        </n-button>
                    </n-space>
                </n-descriptions-item>
                <n-descriptions-item label="简介">
                    <n-space>
                        <n-text v-if="!changing.desc">{{ levelUpdateForm.desc }}</n-text>
                        <n-input v-else v-model:value="levelUpdateForm.desc"/>

                        <n-button v-if="isLevelOwner" text type="primary" @click="changing.desc = !changing.desc">
                            {{ !changing.desc ? '(修改)' : '(保存)' }}
                        </n-button>
                    </n-space>
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
                <n-descriptions-item v-if="isLevelOwner" label="密码">
                    <n-space>
                        <template v-if="!changing.password">
                            <n-text v-if="(levelUpdateForm.password.toString() === '0')" type="info">禁止复制</n-text>
                            <n-text v-else-if="(levelUpdateForm.password.toString() === '1')" type="info">免费复制</n-text>
                            <n-text v-else>{{ levelUpdateForm.password }}</n-text>
                        </template>

                        <template v-else>
                            <n-space vertical>
                                <n-input v-model:value="levelUpdateForm.password"/>

                                <n-space>
                                    <n-button
                                        @click="(levelUpdateForm.password = '0')">
                                        禁止复制
                                    </n-button>

                                    <n-button
                                        @click="(levelUpdateForm.password = '1')">
                                        免费复制
                                    </n-button>

                                    <n-button
                                        @click="levelUpdateForm.password = random(0, 999999, false).toString()">
                                        随机密码
                                    </n-button>
                                </n-space>
                            </n-space>
                        </template>

                        <n-button v-if="isLevelOwner" text type="primary"
                                  @click="changing.password = !changing.password">
                            {{ !changing.password ? '(修改)' : '(保存)' }}
                        </n-button>
                    </n-space>
                </n-descriptions-item>
                <n-descriptions-item label="歌曲">
                    <n-space :vertical="changing.song">
                        <template v-if="!changing.song">
                            <n-text v-if="(levelUpdateForm.song_id.toString() === '0')" text>
                                {{ audioTracks[levelUpdateForm.audio_track] }} [{{ levelUpdateForm.audio_track }}]
                            </n-text>

                            <n-button v-else text @click="toRouteWithParams('ngproxy.info', levelUpdateForm.song_id)">
                                {{
                                    level.song_id.toString() === levelUpdateForm.song_id.toString() ? level.song.name :
                                        'Unknown'
                                }} [{{ levelUpdateForm.song_id }}]
                            </n-button>
                        </template>

                        <template v-else>
                            <n-tabs
                                :default-value="(levelUpdateForm.song_id.toString() === '0' ? 'official' : 'newgrounds')"
                                justify-content="space-evenly"
                                type="line"
                                @update:value="handleSongTypeUpdate"
                            >
                                <n-tab-pane name="official" tab="官方歌曲">
                                    <n-select v-model:value="levelUpdateForm.audio_track" :options="options"/>
                                </n-tab-pane>

                                <n-tab-pane name="newgrounds" tab="NG歌曲">
                                    <n-input-number v-model:value="levelUpdateForm.song_id"/>
                                </n-tab-pane>
                            </n-tabs>
                        </template>

                        <n-button v-if="isLevelOwner"
                                  class="mt-2.5" text type="primary"
                                  @click="changing.song = !changing.song">
                            {{ !changing.song ? '(修改)' : '(保存)' }}
                        </n-button>
                    </n-space>
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
                    <n-space>
                        <n-text v-if="!changing.requested_stars">{{ levelUpdateForm.requested_stars }}</n-text>
                        <n-input-number v-else v-model:value="levelUpdateForm.requested_stars" :max="10" :min="0"/>

                        <n-button v-if="isLevelOwner" text type="primary"
                                  @click="changing.requested_stars = !changing.requested_stars">
                            {{ !changing.requested_stars ? '(修改)' : '(保存)' }}
                        </n-button>
                    </n-space>
                </n-descriptions-item>
                <n-descriptions-item label="不公开 (Unlisted)">
                    <n-space>
                        <n-text>{{ levelUpdateForm.unlisted ? '是' : '否' }}</n-text>
                        <n-switch v-if="isLevelOwner" v-model:value="levelUpdateForm.unlisted"/>
                    </n-space>
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
                    <n-button v-if="isLevelOwner && levelUpdateForm.isDirty"
                              :disabled="levelUpdateForm.processing"
                              :loading="levelUpdateForm.processing"
                              type="success"
                              @click="levelUpdateForm.patch( route('gdcs.dashboard.level.update', level.id) )">
                        保存修改
                    </n-button>

                    <n-button v-if="permission.rate"
                              @click="toRouteWithParams('gdcs.admin.level.rate', level.id)">
                        评分
                    </n-button>

                    <n-button
                        v-if="permission.mark"
                        :disabled="levelUpdateForm.processing || !isEmpty(level.daily) || !isEmpty(level.weekly)"
                        :loading="markAsDailyForm.processing"
                        @click="markAsDailyForm.post( route('gdcs.admin.level.mark.daily', level.id) )">
                        添加到 Daily
                    </n-button>

                    <n-button
                        v-if="permission.mark"
                        :disabled="levelUpdateForm.processing || !isEmpty(level.daily) || !isEmpty(level.weekly)"
                        :loading="markAsWeeklyForm.processing"
                        @click="markAsWeeklyForm.post( route('gdcs.admin.level.mark.weekly', level.id) )">
                        添加到 Weekly
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
