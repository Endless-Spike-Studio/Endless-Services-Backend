<script lang="ts" setup>
import {NButton, NCard, NSpace} from "naive-ui"
import {toRoute} from "@/scripts/helpers"</script>

<template layout="GDCS">
    <n-space class="mx-auto lg:w-1/3" vertical>
        <n-card title="账号相关">
            <n-space>
                <n-button @click="toRoute('gdcs.tools.account.link.list')">账号链接</n-button>
            </n-space>
        </n-card>
        <n-card title="关卡相关">
            <n-space>
                <n-button @click="toRoute('gdcs.tools.level.temp_upload_access.list')">临时关卡上传许可证</n-button>
                <n-button @click="toRoute('gdcs.tools.level.transfer.home')">关卡转移</n-button>
            </n-space>
        </n-card>
        <n-card title="歌曲相关">
            <n-space>
                <n-button @click="toRoute('gdcs.tools.song.custom.list')">自定义歌曲</n-button>
            </n-space>
        </n-card>
    </n-space>
</template>
