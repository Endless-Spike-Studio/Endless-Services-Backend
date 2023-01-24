<script lang="ts" setup>
defineProps<{
    name: string
    avatar: string
    qq: number
    bilibili_uid: number
    role: string
    works: Array<string>
}>();
</script>

<template>
    <n-grid :cols="3">
        <n-grid-item class="text-center">
            <n-el class="flex justify-center items-center h-full">
                <n-space vertical>
                    <n-image :src="avatar" :width="75"
                             class="rounded-full transition duration-1000 ease-in-out hover:rotate-[360deg]"
                             preview-disabled/>
                    <n-text class="font-bold text-2xl">{{ name }}</n-text>
                    <n-text :depth="3">{{ role }}</n-text>
                </n-space>
            </n-el>
        </n-grid-item>

        <n-grid-item :span="2" class="pl-2.5">
            <n-space class="text-left leading-none" size="large" vertical>
                <n-space>
                    <n-space vertical>
                        <n-text>QQ:</n-text>
                        <n-button :href="('https://wpa.qq.com/msgrd?uin=' + qq)" tag="a" text
                                  type="primary">
                            {{ qq }}
                        </n-button>
                    </n-space>

                    <n-space vertical>
                        <n-text>哔哩哔哩:</n-text>
                        <n-button :href="('https://space.bilibili.com/' + bilibili_uid)" tag="a" text
                                  type="primary">
                            {{ bilibili_uid }}
                        </n-button>
                    </n-space>
                </n-space>

                <slot name="extra"/>

                <n-space vertical>
                    <n-text>职责:</n-text>

                    <n-space size="small">
                        <n-tag v-for="work in works">
                            {{ work }}
                        </n-tag>
                    </n-space>
                </n-space>
            </n-space>
        </n-grid-item>
    </n-grid>
</template>
