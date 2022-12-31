<script lang="ts" setup>
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";

const form = useForm({
    name: null,
    artist_name: null,
    link: null
});
</script>

<template>
    <n-card>
        <n-form-item label="歌曲名">
            <n-input v-model:value="form.name"/>
        </n-form-item>

        <n-form-item label="歌手名">
            <n-input v-model:value="form.artist_name"/>
        </n-form-item>

        <n-form-item label="歌曲链接">
            <n-input v-model:value="form.link"/>
        </n-form-item>

        <n-form-item>
            <n-button :disabled="form.processing" :loading="form.processing"
                      @click="form.post(route('gdcs.tools.song.custom.create.link.api'))">提交
            </n-button>
        </n-form-item>
    </n-card>
</template>
