<script lang="ts" setup>
import {App} from "@/scripts/types/backend";

const $emit = defineEmits(['select']);

defineProps<{
    item: App.Models.GDCS.AccountLink
}>();
</script>

<template>
    <n-button @click="$emit('select', item.id)">选择</n-button>
</template>
