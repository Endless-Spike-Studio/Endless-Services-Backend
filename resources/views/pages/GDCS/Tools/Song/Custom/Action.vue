<script lang="ts" setup>
import {App} from "@/scripts/types/backend";

defineProps<{
    item: App.Models.GDCS.CustomSong;
}>();
</script>

<template>
    <n-space>
        <n-button :href="item.download_url" tag="a">试听</n-button>
    </n-space>
</template>
