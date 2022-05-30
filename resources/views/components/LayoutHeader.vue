<script lang="ts" setup>
import {NMenu, NSpace} from "naive-ui";
import {isMobile, toRoute} from "@/scripts/helpers";
import {ref, watch} from "vue";
import route, {routes} from "@/scripts/route";

defineProps({
    menu: {
        type: Object,
        required: true
    }
});

const active = ref(
    route().current()
);

watch(routes, () => {
    active.value = route().current();
});
</script>

<template>
    <n-space v-if="!isMobile" justify="space-between">
        <n-menu v-model:value="active"
                :options="menu.left"
                mode="horizontal"
                @update:value="toRoute"/>

        <n-menu v-model:value="active"
                :options="menu.right"
                mode="horizontal"
                @update:value="toRoute"/>
    </n-space>

    <n-menu v-else
            v-model:value="active"
            :default-expanded-keys="[]"
            :options="menu.mobile"
            @update:value="toRoute"/>
</template>
