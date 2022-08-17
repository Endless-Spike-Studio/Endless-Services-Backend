<script lang="ts" setup>
import {GDCS} from '@/scripts/types/backend'
import {formatTime, isMobile} from '@/scripts/helpers'
import {NButton, NCard, NDescriptions, NDescriptionsItem, NInput, NSpace, NText} from 'naive-ui'
import {reactive} from 'vue'
import {useForm} from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'

const props = defineProps<{
    ability: GDCS.Ability
}>()

const changing = reactive({
    title: false
})

const abilityUpdateForm = useForm({
    title: props.ability.title
})

function updateAbility() {
    abilityUpdateForm.patch(
        route('gdcs.admin.account.ability.update.api', [props.ability.id])
    )
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="能力信息">
        <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
            <n-descriptions-item label="ID">
                {{ ability.id }}
            </n-descriptions-item>
            <n-descriptions-item label="名称">
                {{ ability.name }}
            </n-descriptions-item>
            <n-descriptions-item label="描述">
                <n-space>
                    <n-text v-if="!changing.title">{{ abilityUpdateForm.title }}</n-text>
                    <n-input v-else v-model:value="abilityUpdateForm.title"/>

                    <n-button text type="primary" @click="changing.title = !changing.title">
                        {{ !changing.title ? '(修改)' : '(保存)' }}
                    </n-button>
                </n-space>
            </n-descriptions-item>
            <n-descriptions-item label="目标">
                <n-text v-if="!ability.entity_type">未指定</n-text>
                <n-text v-else-if="(ability.entity_type === '*')">所有</n-text>
                <n-text v-else>{{ ability.entity_type }} #{{ ability.entity_id }}</n-text>
            </n-descriptions-item>
            <n-descriptions-item label="作用域">
                {{ ability.scope ?? '无' }}
            </n-descriptions-item>
            <n-descriptions-item label="创建时间">
                {{ formatTime(ability.created_at, '未知') }}
            </n-descriptions-item>
            <n-descriptions-item label="更新时间">
                {{ formatTime(ability.updated_at, '未知') }}
            </n-descriptions-item>
        </n-descriptions>

        <template #footer>
            <n-space>
                <n-button v-if="abilityUpdateForm.isDirty" :disabled="abilityUpdateForm.processing"
                          :loading="abilityUpdateForm.processing" type="success" @click="updateAbility">
                    保存修改
                </n-button>
            </n-space>
        </template>
    </n-card>
</template>
