<script setup lang="ts">
import {FormInst, NButton, NCard, NForm, NFormItem, NInput, NSpace} from "naive-ui";
import {ref, watchEffect} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

const el = ref<FormInst>();
watchEffect(() => {
    el.value?.validate();
});

const rules = {
    music_id: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.music_id)
    }
};

const form = useForm({
    music_id: 0
});

const link = ref();
watchEffect(() => {
    try {
        const url = new URL(link.value);
        const musicID = url.searchParams.get('id');
        form.music_id = Number(musicID);
    } catch (e) {
        form.music_id = Number(link.value);
    }
});
</script>

<template layout="GDCS">
    <n-card title="自定义歌曲上传 (网易云)" class="lg:w-1/3 mx-auto">
        <n-form ref="el" :rules="rules" :model="form">
            <n-form-item label="音乐ID | 分享链接 (自动解析)" path="music_id">
                <n-space class="w-full" vertical>
                    <n-input v-model:value="link"/>
                    <span>解析结果: {{ form.music_id }}</span>
                </n-space>
            </n-form-item>

            <n-button :disabled="form.processing" @click="form.post( route('gdcs.tools.song.custom.create.netease.api') )">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
