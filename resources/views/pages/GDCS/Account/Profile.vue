<script lang="ts" setup>
import {formatTime, getProp, isMobile, toRoute} from "@/scripts/helpers";
import {NButton, NCard, NDescriptions, NDescriptionsItem, NSpace} from "naive-ui";
import {User} from "@/scripts/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

const account = getProp<User>('gdcs.account');
const resendEmailVerificationForm = useForm({});
</script>

<template layout="GDCS">
    <n-space vertical>
        <n-card class="lg:w-2/3 mx-auto" title="账号资料">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ account.id }}
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ account.name }}
                </n-descriptions-item>
                <n-descriptions-item label="邮箱">
                    {{ account.email }}
                </n-descriptions-item>
                <n-descriptions-item label="邮箱验证时间">
                    {{ formatTime(account.email_verified_at, '未验证') }}
                </n-descriptions-item>
                <n-descriptions-item label="注册时间">
                    {{ formatTime(account.created_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>

            <template #footer>
                <n-space>
                    <n-button v-if="!account.email_verified_at"
                              :disabled="resendEmailVerificationForm.processing"
                              @click="resendEmailVerificationForm.post( route('gdcs.account.resendEmailVerification.api') )">
                        重发验证邮件
                    </n-button>

                    <n-button @click="toRoute('gdcs.account.setting')">账号设置</n-button>
                </n-space>
            </template>
        </n-card>

        <n-card v-if="account.user" class="lg:w-2/3 mx-auto" title="用户资料">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ account.user.id }}
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ account.user.name }}
                </n-descriptions-item>
                <n-descriptions-item label="uuid">
                    {{ account.user.uuid }}
                </n-descriptions-item>
                <n-descriptions-item label="udid">
                    {{ account.user.udid }}
                </n-descriptions-item>
                <n-descriptions-item label="创建时间">
                    {{ formatTime(account.user.created_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>
    </n-space>
</template>
