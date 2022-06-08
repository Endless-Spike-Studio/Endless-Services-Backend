<script lang="ts" setup>
import {formatTime, getProp, isMobile, toRoute} from "@/scripts/helpers";
import {NButton, NCard, NDescriptions, NDescriptionsItem, NSpace} from "naive-ui";
import {User} from "@/scripts/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

const user = getProp<User>('gdcn.user');
const resendEmailVerificationForm = useForm({});
</script>

<template layout="GDCN">
    <n-card class="lg:w-1/3 mx-auto" title="用户资料">
        <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
            <n-descriptions-item label="ID">
                {{ user.id }}
            </n-descriptions-item>
            <n-descriptions-item label="名称">
                {{ user.name }}
            </n-descriptions-item>
            <n-descriptions-item label="邮箱">
                {{ user.email }}
            </n-descriptions-item>
            <n-descriptions-item label="邮箱验证时间">
                {{ formatTime(user.email_verified_at, '未验证') }}
            </n-descriptions-item>
            <n-descriptions-item label="注册时间">
                {{ formatTime(user.created_at, '未知') }}
            </n-descriptions-item>
        </n-descriptions>

        <template #footer>
            <n-space>
                <n-button v-if="!user.email_verified_at"
                          :disabled="resendEmailVerificationForm.processing"
                          @click="resendEmailVerificationForm.post( route('user.resendEmailVerification.api') )">
                    重发验证邮件
                </n-button>

                <n-button @click="toRoute('user.setting')">账号设置</n-button>
            </n-space>
        </template>
    </n-card>
</template>
