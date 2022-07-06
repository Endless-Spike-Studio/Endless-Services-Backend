<script lang="ts" setup>
import {formatTime, getProp, isMobile, toRoute} from "@/scripts/helpers"
import {User} from "@/scripts/types/backend"
import {useForm} from "@inertiajs/inertia-vue3"
import route from "@/scripts/route"
import {reactive, watch} from "vue"
import {NButton, NCard, NDescriptions, NDescriptionsItem, NSpace, NText} from "naive-ui"
import {useGlobalStore} from "@/scripts/stores"
import {each} from "lodash-es"

const account = getProp<User>('gdcs.account')
const user = getProp<User>('gdcs.user')
const resendEmailVerificationForm = useForm({})

watch(resendEmailVerificationForm, newForm => {
    const globalStore = useGlobalStore()

    each(newForm.errors, (error, field) => {
        globalStore.$message.error(`[${field}] ${error}`)
    })
})

const hidden = reactive({
    uuid: true,
    udid: true
})
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
                    <n-button @click="toRoute('gdcs.account.failed-log')">失败日志</n-button>
                </n-space>
            </template>
        </n-card>

        <n-card v-if="user" class="lg:w-2/3 mx-auto" title="用户资料">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ user.id }}
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ user.name }}
                </n-descriptions-item>
                <n-descriptions-item label="uuid">
                    <n-space>
                        <n-text v-if="hidden.uuid">{{ '*'.repeat(user.uuid.length) }}</n-text>
                        <n-text v-else>{{ user.uuid }}</n-text>

                        <n-button text type="primary" @click="hidden.uuid = !hidden.uuid">
                            ({{ hidden.uuid ? '显示' : '隐藏' }})
                        </n-button>
                    </n-space>
                </n-descriptions-item>
                <n-descriptions-item label="udid">
                    <n-space>
                        <n-text v-if="hidden.udid">{{ '*'.repeat(user.udid.length) }}</n-text>
                        <n-text v-else>{{ user.udid }}</n-text>

                        <n-button text type="primary" @click="hidden.udid = !hidden.udid">
                            ({{ hidden.udid ? '显示' : '隐藏' }})
                        </n-button>
                    </n-space>
                </n-descriptions-item>
                <n-descriptions-item label="创建时间">
                    {{ formatTime(user.created_at, '未知') }}
                </n-descriptions-item>
            </n-descriptions>
        </n-card>
    </n-space>
</template>
