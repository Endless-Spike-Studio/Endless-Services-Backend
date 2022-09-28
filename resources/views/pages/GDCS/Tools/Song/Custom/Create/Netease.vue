<script lang="ts" setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {FormInst, FormRules} from "naive-ui";
import {ref, watch} from "vue";
import route from "@/scripts/core/route";

const props = defineProps<{
    url: string | null;
}>();

const el = ref<FormInst>();
const value = ref<string>();

const form = useForm({
    music_id: null as number | null
});

const rules = {
    music_id: {
        required: true,
        type: 'integer',
        validator: () => Promise.reject(form.errors.music_id)
    }
} as FormRules;

watch(value, newValue => {
    try {
        const url = new URL(newValue as string);
        const musicID = url.searchParams.get('id');
        form.music_id = Number(musicID);
    } catch (e) {
        form.music_id = Number(newValue);
    }
});

if (props.url !== null) {
    const cancelWatch = watch(form, () => {
        cancelWatch();
        create();
    });

    value.value = props.url;
}

watch([el, form], () => {
    el.value?.validate();
});

function create() {
    form.put(
        route('gdcs.tools.song.custom.create.netease.api')
    );
}
</script>

<template>
    <n-form ref="el" :model="form" :rules="rules">
        <n-form-item label="音乐ID" path="music_id">
            <n-input v-model:value="value" placeholder="也可以输入音乐分享链接, 会自动解析喔~"/>
        </n-form-item>

        <n-el>
            <n-text v-if="form.music_id" type="info">解析结果: {{ form.music_id }}</n-text>

            <n-button :disabled="form.processing"
                      :loading="form.processing"
                      class="float-right"
                      @click="create">
                <n-text>提交</n-text>
            </n-button>
        </n-el>
    </n-form>
</template>
