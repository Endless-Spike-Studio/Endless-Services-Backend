<script setup lang="ts">
import {getProp, isMobile, toRoute, toURL} from "@/scripts/helpers";
import {NButton, NDivider, NIcon, NSpace, NThing} from "naive-ui";
import {BranchesOutlined} from "@vicons/antd";

const phpVersion = getProp('php_version');
const laravelVersion = getProp('laravel_version');
const gitCommitHash = getProp('git_commit_hash');

defineProps({
    shortText: {
        type: String,
        required: true
    },
    longText: {
        type: String,
        required: true
    }
});
</script>

<template>
    <n-space justify="space-between">
        <n-thing>
            <span>&copy; 2022 - {{ new Date().getFullYear() }}</span>
            <n-divider vertical/>

            <n-button text @click="toRoute('home')">
                {{ isMobile ? shortText : longText }}
            </n-button>

            <n-divider vertical/>
            <n-button text @click="toURL('https://beian.miit.gov.cn')">吉ICP备18006293号</n-button>
        </n-thing>

        <n-thing>
            <span>PHP {{ phpVersion }}</span>
            <n-divider vertical/>

            <span>Laravel {{ laravelVersion }}</span>
            <n-divider vertical/>

            <span>
                <n-icon :component="BranchesOutlined"/> {{ gitCommitHash }}
            </span>
        </n-thing>
    </n-space>
</template>
