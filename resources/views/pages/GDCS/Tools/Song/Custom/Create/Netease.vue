<script lang="ts" setup>
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";
import {FormInst} from "naive-ui";
import {createRules} from "@/scripts/core/utils";

const content = ref();
type MusicID = string | number | null;

watch(content, newContent => {
    try {
        form.music_id = null;

        if (parseInt(newContent)) {
            form.music_id = newContent;
        } else {
            form.music_id = new URL(newContent).searchParams.get('id');
        }
    } catch (e) {

    }
});

const form = useForm({
    music_id: null as MusicID
});

const formRef = ref<FormInst>();
const rules = createRules(form);

function submit() {
    formRef.value?.validate(errors => {
        if (!errors) {
            form.post(route('gdcs.tools.song.custom.create.netease.api'), {
                onFinish: () => {
                    formRef.value?.validate();
                    form.clearErrors();
                }
            });
        }
    });
}
</script>

<template>
    <n-card>
        <n-form-item label="音乐ID / 分享链接">
            <n-space class="w-full" vertical>
                <n-input v-model:value="content"/>
                <n-text v-if="form.music_id">解析结果: {{ form.music_id }}</n-text>
            </n-space>
        </n-form-item>

        <n-form-item>
            <n-button :disabled="form.processing" :loading="form.processing"
                      @click="submit">
                提交
            </n-button>
        </n-form-item>
    </n-card>
</template>
