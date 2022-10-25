<script lang="ts" setup>
import {RemoteLevel} from "@/scripts/types/backend";
import {InertiaForm} from "@inertiajs/inertia-vue3";

const $emit = defineEmits(['select']);

const props = defineProps<{
    form: InertiaForm<any>;
    item: RemoteLevel
}>();

function handleSelect() {
    $emit('select', props.item);
}

const processing = ref(false);
watch(props, () => {
    processing.value = props.item.transferred || props.form.processing;
});
</script>

<template>
    <n-button :disabled="processing"
              :loading="processing" @click="handleSelect">
        转出
    </n-button>
</template>
