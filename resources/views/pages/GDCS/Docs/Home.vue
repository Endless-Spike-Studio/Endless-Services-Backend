<script lang="ts" setup>
import { NButton, NCard, NSpace } from 'naive-ui'
import { toRoute } from '@/scripts/helpers'</script>

<template layout="GDCS">
    <n-card class="lg:w-1/2 mx-auto" title="命令">
        <n-space justify="center">
            <n-button @click="toRoute('gdcs.docs.command.account')">账号命令</n-button>
            <n-button @click="toRoute('gdcs.docs.command.level')">关卡命令</n-button>
        </n-space>
    </n-card>
</template>
