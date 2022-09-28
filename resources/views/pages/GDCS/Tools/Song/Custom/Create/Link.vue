<script lang="ts" setup>
import {useForm} from "@inertiajs/inertia-vue3";
import {ref, watch} from "vue";
import {FormInst, FormRules} from "naive-ui";
import route from "@/scripts/core/route";

const $emit = defineEmits(['netease']);
const currentStep = ref('link');

const el = ref<FormInst>();
const form = useForm({
    link: null,
    name: null,
    artist_name: null
});

const rules = {
    link: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.link)
    },
    name: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.name)
    },
    artist_name: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.artist_name)
    }
} as FormRules;

watch([el, form], () => {
    el.value?.validate();
});

function checkUrlAndNext() {
    if (String(form.link).match(/(163|126|netease)/)) {
        return $emit('netease', form.link);
    }

    currentStep.value = 'information';
}

function create() {
    form.put(
        route('gdcs.tools.song.custom.create.link.api')
    );
}
</script>

<template>
    <n-form ref="el" :model="form" :rules="rules">
        <n-tabs v-model:value="currentStep" :tab-style="{ display: 'none' }" animated>
            <n-tab-pane name="link">
                <n-form-item label="歌曲外链" path="link">
                    <n-input v-model:value="form.link"/>
                </n-form-item>

                <n-button class="float-right" @click="checkUrlAndNext">下一步</n-button>
            </n-tab-pane>

            <n-tab-pane name="information">
                <n-form-item label="歌曲外链" path="link">
                    <n-input v-model:value="form.link" disabled readonly/>
                </n-form-item>

                <n-form-item label="歌曲名" path="name">
                    <n-input v-model:value="form.name"/>
                </n-form-item>

                <n-form-item label="歌手名" path="artist_name">
                    <n-input v-model:value="form.artist_name"/>
                </n-form-item>

                <n-button class="float-right" @click="create">提交</n-button>
            </n-tab-pane>
        </n-tabs>
    </n-form>
</template>
