<script lang="ts" setup>
import {toURL} from "@/scripts/helpers";
import {NButton, NSpace} from "naive-ui";

defineProps({
    sponsorButtonText: {
        type: String,
        required: false,
        default: '支持一下 (赞助)'
    },
    joinGroupButtonText: {
        type: String,
        required: false,
        default: "加入讨论群"
    }
});
</script>

<template>
    <n-space justify="center">
        <n-button type="tertiary" @click="toURL('https://afdian.net/@WOSHIZHAZHA120')">
            {{ sponsorButtonText }}
        </n-button>

        <n-button type="tertiary"
                  @click="toURL('https://qm.qq.com/cgi-bin/qm/qr?k=jQxB2uKVm30XzwuxsgrJwbKRErCkFAjw')">
            {{ joinGroupButtonText }}
        </n-button>
    </n-space>
</template>
