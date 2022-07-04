<script lang="ts" setup>
import {NCard, NDescriptions, NDescriptionsItem, NGrid, NGridItem, NText} from "naive-ui"
import {isMobile} from "@/scripts/helpers"</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="账号命令">
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:3">
            <n-grid-item>
                <n-descriptions :bordered="true" :column="isMobile ? 1 : 3" title="!test">
                    <n-descriptions-item label="用法">
                        !test
                    </n-descriptions-item>
                    <n-descriptions-item label="描述">
                        测试命令
                    </n-descriptions-item>
                    <n-descriptions-item label="返回值">
                        <n-text code>worked!</n-text>
                    </n-descriptions-item>
                </n-descriptions>
            </n-grid-item>
        </n-grid>
    </n-card>
</template>
