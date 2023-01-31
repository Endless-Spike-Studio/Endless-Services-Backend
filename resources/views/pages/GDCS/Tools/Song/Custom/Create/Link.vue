<script lang="ts" setup>
import {Head, useForm} from "@inertiajs/vue3";
import route from "ziggy-js";
import {FormInst} from "naive-ui";
import {createRules} from "@/scripts/core/utils";

const form = useForm({
    name: null,
    artist_name: null,
    link: null
});

const formRef = ref<FormInst>();
const rules = createRules(form);

function submit() {
    formRef.value?.validate(errors => {
        if (!errors) {
            form.post(route('gdcs.tools.song.custom.create.link.api'), {
                onFinish() {
                    formRef.value?.validate();
                    form.clearErrors();
                }
            });
        }
    });
}
</script>

<template>
    <Head>
        <title>在线工具 - 自定义歌曲 - 创建 - 外链</title>
    </Head>

    <n-card>
        <n-form ref="formRef" :model="form" :rules="rules">
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
                <n-button :disabled="!form.isDirty || form.processing" :loading="form.processing"
                          @click="submit">
                    提交
                </n-button>
            </n-form-item>
        </n-form>
    </n-card>
</template>
