<script lang="ts" setup>
import {Inertia} from "@inertiajs/inertia";
import {createRange, formatTime, to_route, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import c1 from "@/images/game/c1.png";
import c2 from "@/images/game/c2.png";

const level = useProp<App.Models.Level>('level');
const scores = useProp<App.Models.LevelScore[]>('scores');

nextTick(() => {
    Inertia.reload({
        only: ['scores']
    });
});
</script>

<template>
    <n-card>
        <n-list v-if="scores && scores.length > 0" bordered>
            <n-list-item v-for="score in scores">
                <n-thing>
                    <template #header>
                        <n-button text type="primary" @click="to_route('gdcs.account.info', score.account_id)">
                            {{ score.account.name }}
                        </n-button>

                        <n-text :depth="3" class="text-sm">&nbsp;{{ score.percent }}%</n-text>
                    </template>

                    <template #header-extra>
                        <n-image-group>
                            <n-image v-for="_ in createRange(score.coins)" :src="level.rating.coin_verified ? c2 : c1"
                                     :width="30"/>
                        </n-image-group>
                    </template>

                    <template #description>
                        <n-text :depth="3" class="text-sm">
                            {{ score.attempts }} Attempts
                            <br>
                            记录时间: {{ formatTime(score.created_at) }}
                        </n-text>
                    </template>
                </n-thing>
            </n-list-item>
        </n-list>

        <n-empty v-else/>
    </n-card>
</template>
