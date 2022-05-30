<script setup lang="ts">
import {toRoute} from "@/scripts/helpers";
import {NButton, NCard, NSpace} from "naive-ui";</script>

<template layout="GDCS">
    <n-card title="关卡转移" class="lg:w-1/3 mx-auto">
        <n-space justify="center">
            <n-button @click="toRoute('gdcs.tools.level.transfer.in')">转进</n-button>
            <n-button @click="toRoute('gdcs.tools.level.transfer.out')">转出</n-button>
        </n-space>
    </n-card>
</template>
