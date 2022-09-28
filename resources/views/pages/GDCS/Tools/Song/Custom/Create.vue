<script lang="ts" setup>
import LinkUpload from "@/views/pages/GDCS/Tools/Song/Custom/Create/Link.vue";
import NeteaseUpload from "@/views/pages/GDCS/Tools/Song/Custom/Create/Netease.vue";

const currentTab = ref('link');
const url = ref<number | null>(null);

function handleNeteaseChecked(link: string) {
    url.value = link;
    currentTab.value = 'netease';
}
</script>

<template>
    <n-el class="mt-5">
        <n-tabs v-model:value="currentTab" animated justify-content="space-evenly" type="line">
            <n-tab-pane name="link" tab="外链">
                <link-upload @netease="handleNeteaseChecked"/>
            </n-tab-pane>

            <n-tab-pane name="netease" tab="网易">
                <netease-upload :url="url"/>
            </n-tab-pane>
        </n-tabs>
    </n-el>
</template>
