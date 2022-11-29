<script lang="ts" setup>
import {SelectableMenuOption} from "@/types/components/menu";

defineProps<{
    items: SelectableMenuOption[];
}>();

function handleSelect(_key: string, option: SelectableMenuOption) {
    option?.onSelect?.();
}
</script>

<template>
    <n-menu :options="items" @update:value="handleSelect"/>
</template>
