<script lang="ts" setup>
import {GDCS} from "@/scripts/types/backend";
import {formatTime, isMobile} from "@/scripts/helpers";
import {NButton, NCard, NDescriptions, NDescriptionsItem, NInput, NSpace, NText} from "naive-ui";
import {reactive} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

const props = defineProps<{
    role: GDCS.Role
}>()

const changing = reactive({
    title: false
})

const roleUpdateForm = useForm({
    title: props.role.title
})
</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="角色信息">
        <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
            <n-descriptions-item label="ID">
                {{ role.id }}
            </n-descriptions-item>
            <n-descriptions-item label="名称">
                {{ role.name }}
            </n-descriptions-item>
            <n-descriptions-item label="描述">
                <n-space>
                    <n-text v-if="!changing.title">{{ roleUpdateForm.title }}</n-text>
                    <n-input v-else v-model:value="roleUpdateForm.title"/>

                    <n-button text type="primary" @click="changing.title = !changing.title">
                        {{ !changing.title ? '(修改)' : '(保存)' }}
                    </n-button>
                </n-space>
            </n-descriptions-item>
            <n-descriptions-item label="作用域">
                {{ role.scope ?? '无' }}
            </n-descriptions-item>
            <n-descriptions-item label="创建时间">
                {{ formatTime(role.created_at, '未知') }}
            </n-descriptions-item>
            <n-descriptions-item label="更新时间">
                {{ formatTime(role.updated_at, '未知') }}
            </n-descriptions-item>
        </n-descriptions>

        <template #footer>
            <n-space>
                <n-button v-if="roleUpdateForm.isDirty"
                          :disabled="roleUpdateForm.processing"
                          :loading="roleUpdateForm.processing"
                          type="success"
                          @click="roleUpdateForm.patch( route('gdcs.admin.account.role.update.api', role.id) )">
                    保存修改
                </n-button>
            </n-space>
        </template>
    </n-card>
</template>
