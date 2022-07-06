<script lang="ts" setup>
import {
    NButton,
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NEmpty,
    NList,
    NListItem,
    NPopover,
    NSelect,
    NSpace,
    NTag,
    NText,
    NThing
} from "naive-ui"
import {formatTime, isMobile, toRoute, toRouteWithParams} from "@/scripts/helpers"
import {Base64} from "js-base64"
import {GDCS} from "@/scripts/types/backend"
import {useForm} from "@inertiajs/inertia-vue3"
import {each, map} from "lodash-es"
import {computed, h, watch} from "vue"
import route from "@/scripts/route"
import {useGlobalStore} from "@/scripts/stores"

const props = defineProps<{
    account: GDCS.Account,
    abilities: GDCS.Ability[],
    roles: GDCS.Role[],
    permission: {
        manage: boolean
    }
}>()

const abilityOptions = computed(() => {
    return map(props.abilities, ability => {
        return {
            label: () => h(NPopover, null, {
                trigger: () => ability.title,
                default: () => ability.name
            }),
            value: ability.name
        }
    })
})

const roleOptions = computed(() => {
    return map(props.roles, role => {
        return {
            label: () => h(NPopover, null, {
                trigger: () => role.title,
                default: () => role.name
            }),
            value: role.name
        }
    })
})

const updateForm = useForm({
    abilities: map(props.account.abilities, 'name'),
    roles: map(props.account.roles, 'name')
})

watch(updateForm, newForm => {
    const globalStore = useGlobalStore()

    each(newForm.errors, (error, field) => {
        globalStore.$message.error(`[${field}] ${error}`)
    })
})
</script>

<template layout="GDCS">
    <n-space vertical>
        <n-card class="lg:w-2/3 mx-auto" title="账号信息">
            <n-descriptions :bordered="true" :column="isMobile ? 1 : 3">
                <n-descriptions-item label="ID">
                    {{ account.id }}
                </n-descriptions-item>
                <n-descriptions-item label="名称">
                    {{ account.name }}
                </n-descriptions-item>
                <n-descriptions-item v-if="account.user.uuid" label="用户">
                    <n-button text @click="toRouteWithParams('gdcs.dashboard.user.info', account.user.id)">
                        {{ account.user.name }} [{{ account.user.id }}]
                    </n-button>
                </n-descriptions-item>
                <n-descriptions-item label="注册时间">
                    {{ formatTime(account.created_at, '未知') }}
                </n-descriptions-item>
                <n-descriptions-item label="能力">
                    <n-space v-if="permission.manage" vertical>
                        <n-select v-model:value="updateForm.abilities" :options="abilityOptions" filterable multiple
                                  tag/>
                        <n-button @click="toRoute('gdcs.admin.account.ability.list')">能力管理</n-button>
                    </n-space>

                    <n-space v-else>
                        <n-tag v-for="ability in account.abilities">
                            <n-popover>
                                <template #trigger>
                                    {{ ability.title }}
                                </template>

                                {{ ability.name }}
                            </n-popover>
                        </n-tag>
                    </n-space>
                </n-descriptions-item>
                <n-descriptions-item label="角色">
                    <n-space v-if="permission.manage" vertical>
                        <n-select v-model:value="updateForm.roles" :options="roleOptions" filterable multiple tag/>
                        <n-button @click="toRoute('gdcs.admin.account.role.list')">角色管理</n-button>
                    </n-space>

                    <n-space v-else>
                        <n-tag v-for="role in account.roles">
                            <n-popover>
                                <template #trigger>
                                    {{ role.title }}
                                </template>

                                {{ role.name }}
                            </n-popover>
                        </n-tag>
                    </n-space>
                </n-descriptions-item>
            </n-descriptions>

            <template #footer>
                <n-button v-if="updateForm.isDirty"
                          type="success"
                          @click="updateForm.patch( route('gdcs.admin.account.permission.update.api', account.id) )">
                    保存修改
                </n-button>
            </template>
        </n-card>

        <n-card class="lg:w-2/3 mx-auto" title="账号评论">
            <n-list>
                <n-list-item v-for="comment in account.comments">
                    <n-thing>
                        <template #header>
                            <n-button text @click="toRouteWithParams('gdcs.dashboard.account.info', account.id)">
                                {{ account.name }}:
                            </n-button>

                            {{ Base64.decode(comment.comment) }}
                        </template>

                        <template #description>
                            <n-text :depth="3">{{ formatTime(comment.created_at, '未知') }}</n-text>
                            <span>, </span>
                            <n-text :depth="3">{{ comment.likes }} Likes</n-text>
                        </template>
                    </n-thing>
                </n-list-item>
            </n-list>

            <n-empty v-if="account.comments.length <= 0"/>
        </n-card>
    </n-space>
</template>
