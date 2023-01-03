<script lang="ts" setup>
import {createRange, formatTime, guessDifficultyNameFromStars, to_route, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import LevelDifficulty from "@/views/components/LevelDifficulty.vue";
import {Base64} from "js-base64";
import c1 from "@/images/game/c1.png";
import c2 from "@/images/game/c2.png";
import {defaultLevelDesc, lengths, tracks} from "@/scripts/core/shared";
import {EyeInvisibleTwotone} from "@vicons/antd";
import {Inertia} from "@inertiajs/inertia";

const customSongOffset = useProp<number>('customSongOffset');
const level = useProp<App.Models.Level>('level');
const song = useProp<App.Models.Song | App.Models.CustomSong>('song');

nextTick(() => {
    if (level.value.song_id > 0) {
        Inertia.reload({
            only: ['song']
        });
    }
});

const track = computed(() => {
    return tracks[level.value.audio_track];
});
</script>

<template>
    <n-card>
        <template #header>
            <n-thing>
                <template #header>
                    <n-space>
                        <n-h4 class="mb-0">
                            <n-text>{{ level.name }} [{{ level.id }}] By&nbsp;</n-text>

                            <n-button v-if="level.creator.account" text type="primary"
                                      @click="to_route('gdcs.account.info', level.creator.account.id)">
                                {{ level.creator.account.name }}
                            </n-button>

                            <n-text v-else>未知</n-text>
                        </n-h4>
                    </n-space>
                </template>

                <template #description>
                    <n-text :depth="3" class="text-sm">
                        {{ level.desc ? Base64.decode(level.desc) : defaultLevelDesc }}
                    </n-text>
                </template>
            </n-thing>
        </template>

        <template #header-extra>
            <LevelDifficulty :rating="level.rating" :size="30"/>
        </template>

        <n-space vertical>
            <n-el class="leading-relaxed">
                <n-space>
                    <n-text>长度: {{ lengths[level.length] ?? '未知' }}</n-text>
                    <n-text>Objects: {{ level.objects }}</n-text>
                </n-space>

                <n-text>
                    期望评分: {{ guessDifficultyNameFromStars(level.requested_stars) }} {{ level.requested_stars }}
                </n-text>
            </n-el>

            <n-image-group>
                <n-image v-for="_ in createRange(level.coins)" :src="level.rating.coin_verified ? c2 : c1" :width="30"/>
            </n-image-group>

            <n-card>
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

                        <n-space v-if="level.song_id > 0">
                            <n-text>ID: {{ level.song_id }}</n-text>
                            <n-text>Size: {{ song.size }} MB</n-text>
                        </n-space>
                    </n-el>

                    <n-text v-else-if="track">
                        {{ track.name }}
                        <br>
                        By {{ track.artist_name }}
                    </n-text>
                </n-space>
            </n-card>

            <n-space>
                <n-text>发布于 {{ formatTime(level.created_at) }}</n-text>
                <n-text>最后更新: {{ formatTime(level.created_at) }}</n-text>
            </n-space>

            <n-el v-if="level.unlisted" class="mt-2.5">
                <n-icon :component="EyeInvisibleTwotone"/>
                <n-text type="error">&nbsp;不公开列出</n-text>
            </n-el>
        </n-space>
    </n-card>
</template>
