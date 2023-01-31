<script lang="ts" setup>
import {formatTime, guessDifficultyNameFromStars, isMobile, to_route, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import LevelDifficulty from "@/views/components/LevelDifficulty.vue";
import c1 from "@/images/game/c1.png";
import c2 from "@/images/game/c2.png";
import {ClockCircleTwotone, DownloadOutlined, EyeInvisibleTwotone, LikeTwotone, StarTwotone} from "@vicons/antd";
import {Head, router} from "@inertiajs/vue3";
import tracks from "@/shared/tracks.json";
import lengths from "@/shared/level_lengths.json";
import {Box} from "@vicons/tabler";
import {Version} from "@vicons/carbon";
import Grid from "@/views/components/Grid.vue";
import {StarsTwotone} from "@vicons/material";

const customSongOffset = useProp<number>('customSongOffset');
const level = useProp<App.Models.Level>('level');
const song = useProp<App.Models.Song | App.Models.CustomSong>('song');

nextTick(() => {
    if (level.value.song_id > 0) {
        router.reload({
            only: ['song']
        });
    }
});

const track = computed(() => {
    return tracks.find(_ => level.value.audio_track === _.id);
});
</script>

<template>
    <Head>
        <title>关卡 - {{ level.name }} - 详细信息</title>
    </Head>

    <n-card>
        <n-space vertical>
            <n-card>
                <n-space :justify="(isMobile ? 'center' : 'space-evenly')">
                    <LevelDifficulty #="{ difficulty, face }" :rating="level.rating">
                        <n-space :size="0" class="text-center text-2xl font-bold" vertical>
                            <n-image :src="face" :width="50" preview-disabled/>
                            <n-text>{{ difficulty }}</n-text>

                            <n-el class="flex justify-center items-center">
                                <n-text>{{ level.rating.stars }}&nbsp;</n-text>
                                <n-icon :component="StarTwotone" :size="20" class="text-[#FFFF00]"/>
                            </n-el>
                        </n-space>
                    </LevelDifficulty>

                    <n-el class="text-center">
                        <n-text :depth="3">#{{ level.id }}</n-text>

                        <br>

                        <n-button text type="primary"
                                  @click="to_route('gdcs.dashboard.level.info', level.id)">
                            {{ level.name }}
                        </n-button>

                        <br>

                        <n-text :depth="3">By</n-text>

                        <br>

                        <n-text v-if="level.creator" :depth="3">
                            <n-button v-if="level.creator.account" text type="primary"
                                      @click="to_route('gdcs.dashboard.account.info', level.creator.account.id)">
                                {{ level.creator.account.name }}
                            </n-button>

                            <n-text v-else-if="level.creator.name">
                                {{ level.creator.name }}
                            </n-text>

                            <n-text v-else>未知</n-text>
                        </n-text>
                    </n-el>
                </n-space>

                <template #footer>
                    <n-space vertical>
                        <Grid :cols="isMobile ? 4 : 7" class="[&>*]:mx-auto">
                            <n-grid-item class="flex items-center">
                                <n-icon :component="DownloadOutlined" :size="20"/>
                                <n-text>&nbsp;{{ level.downloads }}</n-text>
                            </n-grid-item>

                            <n-grid-item class="flex items-center">
                                <n-icon :component="LikeTwotone" :size="20"/>
                                <n-text>&nbsp;{{ level.likes }}</n-text>
                            </n-grid-item>

                            <n-grid-item class="flex items-center">
                                <n-icon :component="ClockCircleTwotone" :size="20"/>
                                <n-text>&nbsp;{{ lengths.find(_ => level.length === _.index)?.name ?? '未知' }}</n-text>
                            </n-grid-item>

                            <n-grid-item class="flex items-center">
                                <n-popover>
                                    <template #trigger>
                                        <n-icon :component="Version" :size="20"/>
                                    </template>

                                    <n-text>版本</n-text>
                                </n-popover>

                                <n-text>&nbsp;{{ level.version }}</n-text>
                            </n-grid-item>

                            <n-grid-item class="flex items-center">
                                <n-popover>
                                    <template #trigger>
                                        <n-icon :component="Box" :size="20"/>
                                    </template>

                                    <n-text>Objects</n-text>
                                </n-popover>

                                <n-text>&nbsp;{{ level.objects }}</n-text>
                            </n-grid-item>

                            <n-grid-item :span="isMobile ? 2 : 1" class="flex items-center">
                                <n-popover>
                                    <template #trigger>
                                        <n-icon :component="StarsTwotone" :size="20"/>
                                    </template>

                                    <n-text>期望评分</n-text>
                                </n-popover>

                                <n-text>
                                    &nbsp;{{ guessDifficultyNameFromStars(level.requested_stars) }} {{
                                        level.requested_stars
                                    }}
                                </n-text>
                            </n-grid-item>

                            <n-grid-item class="flex items-center">
                                <n-image :src="level.rating.coin_verified ? c2 : c1" :width="20" preview-disabled/>

                                <n-text>
                                    &nbsp;{{ level.coins }}
                                </n-text>
                            </n-grid-item>
                        </Grid>

                        <n-el class="text-center">
                            <n-text :depth="3">发布于 {{ formatTime(level.created_at) }}</n-text>
                            <n-divider vertical/>
                            <n-text :depth="3">最后更新: {{ formatTime(level.created_at) }}</n-text>
                        </n-el>

                        <n-el v-if="level.unlisted" class="flex justify-center items-center">
                            <n-icon :component="EyeInvisibleTwotone" :size="20"/>
                            <n-text type="error">&nbsp;不公开列出</n-text>
                        </n-el>
                    </n-space>
                </template>
            </n-card>

            <n-card class="text-center">
                <n-space vertical>
                    <n-el v-if="song">
                        <n-button v-if="level.song_id >= customSongOffset"
                                  text type="primary"
                                  @click="to_route('gdcs.tools.song.custom.index', { id: level.song_id })">
                            {{ song.name }} - {{ song.artist_name }}
                        </n-button>

                        <n-button v-else-if="level.song_id > 0" text type="primary"
                                  @click="to_route('ngproxy.info', level.song_id)">
                            {{ song.name }} - {{ song.artist_name }}
                        </n-button>

                        <br>

                        <n-text>ID: {{ level.song_id }}</n-text>
                        <n-divider vertical/>
                        <n-text>Size: {{ song.size }} MB</n-text>
                    </n-el>

                    <n-text v-else-if="track">
                        {{ track.name }}
                        <br>
                        By {{ track.artist_name }}
                    </n-text>
                </n-space>
            </n-card>
        </n-space>
    </n-card>
</template>
