<script lang="ts" setup>
import {
    NButton,
    NCard,
    NDescriptions,
    NDescriptionsItem,
    NDynamicTags,
    NEmpty,
    NList,
    NListItem,
    NPopover,
    NSpace,
    NTag,
    NText,
    NThing
} from "naive-ui"
import {formatTime, isMobile, toRouteWithParams} from "@/scripts/helpers"
import {Base64} from "js-base64"
import {GDCS} from "@/scripts/types/backend"
import {useForm} from "@inertiajs/inertia-vue3"
import {map} from "lodash-es"

const props = defineProps<{
    account: GDCS.Account,
    permission: {
        manage: boolean
    }
}>()

const updateForm = useForm({
    abilities: map(props.account.abilities, ability => {
        return {
            label: ability.title,
            value: ability.name
        }
    }),
    roles: map(props.account.roles, role => {
        return {
            label: role.title,
            value: role.name
        }
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
                    <n-dynamic-tags v-if="permission.manage" v-model:value="updateForm.abilities"/>

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
                    <n-dynamic-tags v-if="permission.manage" v-model:value="updateForm.roles"/>

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
