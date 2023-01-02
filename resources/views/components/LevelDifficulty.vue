<script lang="ts" setup>

import {App} from "@/types/backend";

const props = defineProps<{
    rating: App.Models.LevelRating;
    showName?: boolean;
    size?: number;
}>();

const images = import.meta.glob<{
    default: string;
}>('@/images/difficulties/*.png', {
    eager: true
});

const featured = computed(() => {
    return props.rating.featured_score > 0;
});

const faceID = computed(() => {
    switch (props.rating.stars) {
        case 1:
            return 1;
        case 2:
            return 2;
        case 3:
            return 3;
        case 4:
        case 5:
            return 4;
        case 6:
        case 7:
            return 5;
        case 8:
        case 9:
            return 6;
        case 10:
            switch (props.rating.demon_difficulty) {
                case 3:
                    return 7;
                case 4:
                    return 8;
                case 5:
                    return 10;
                case 6:
                    return 11;
                default:
                    return 9;
            }
        default:
            return 0;
    }
});

const url = computed(() => {
    return images['/resources/images/difficulties/' + faceID.value + (props.rating.epic ? '_epic' : featured.value ? '_featured' : '') + '.png']?.default;
});

const name = computed(() => {
    return {
        0: 'N/A',
        1: 'Auto',
        2: 'Easy',
        3: 'Normal',
        4: 'Hard',
        5: 'Harder',
        6: 'Insane',
        7: 'Easy Demon',
        8: 'Medium Demon',
        9: 'Hard Demon',
        10: 'Insane Demon',
        11: 'Extreme Demon'
    }[faceID.value];
});
</script>

<template>
    <n-grid :cols="1">
        <n-grid-item class="mx-auto">
            <n-image :src="url" :title="name + ' ' + rating.stars" :width="size ?? 30" preview-disabled/>
        </n-grid-item>

        <n-grid-item v-if="showName" class="text-center">
            <n-text class="text-sm">
                {{ name }}
                <br>
                {{ rating.stars }}
            </n-text>
        </n-grid-item>
    </n-grid>
</template>
