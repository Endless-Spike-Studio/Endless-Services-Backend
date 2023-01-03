<script lang="ts" setup>
import {App} from "@/types/backend";

const props = defineProps<{
    rating: App.Models.LevelRating;
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
    switch (props.rating.difficulty) {
        case 10:
            return 2;
        case 20:
            return 3;
        case 30:
            return 4;
        case 40:
            return 5;
        case 50:
            return 6;
        default:
            if (props.rating.auto) {
                return 1;
            }

            if (props.rating.demon) {
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
            }
    }

    return 0;
});

const url = computed(() => {
    return images['/resources/images/difficulties/' + faceID.value + (props.rating.epic ? '_epic' : featured.value ? '_featured' : '') + '.png']?.default;
});

const name = computed(() => {
    switch (props.rating.difficulty) {
        case 10:
            return 'Easy';
        case 20:
            return 'Normal';
        case 30:
            return 'Hard';
        case 40:
            return 'Harder';
        case 50:
            return 'Insane';
        default:
            if (props.rating.auto) {
                return 'Auto';
            }

            if (props.rating.demon) {
                switch (props.rating.demon_difficulty) {
                    case 3:
                        return 'Easy Demon';
                    case 4:
                        return 'Medium Demon';
                    case 1:
                        return 'Hard Demon';
                    case 5:
                        return 'Insane Demon';
                    case 6:
                        return 'Extreme Demon';
                    default:
                        return 'Demon';
                }
            }
    }

    return 'N/A';
});
</script>

<template>
    <n-popover>
        <template #trigger>
            <n-image :src="url" :width="size ?? 30" preview-disabled/>
        </template>

        {{ name }} {{ rating.stars }}
    </n-popover>
</template>
