<script lang="ts" setup>
import {FormInst, FormRules, NButton, NCard, NForm, NFormItem, NInput, NSpace} from 'naive-ui'
import {ref, watch} from 'vue'
import {useForm} from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'

const el = ref<FormInst>()
const form = useForm({
    music_id: 0
})

const rules = {
    music_id: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.music_id)
    }
} as FormRules

const link = ref()
watch(link, newLink => {
    try {
        const url = new URL(newLink)
        const musicID = url.searchParams.get('id')
        form.music_id = Number(musicID)
    } catch (e) {
        form.music_id = Number(newLink)
    }
})

watch([el, form], () => {
    if (el.value) {
        el.value.validate()
    }
})

function submit () {
    form.post(
        route('gdcs.tools.song.custom.create.netease.api')
    )
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="自定义歌曲上传 (网易云)">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="音乐ID | 分享链接 (自动解析)" path="music_id">
                <n-space class="w-full" vertical>
                    <n-input v-model:value="link"/>
                    <span>解析结果: {{ form.music_id }}</span>
                </n-space>
            </n-form-item>

            <n-button :disabled="form.processing" @click="submit">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
