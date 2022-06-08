<script lang="ts" setup>
import {ref, watchEffect} from "vue";
import {FormInst, NButton, NCard, NForm, NFormItem, NSelect} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";
import {Inertia} from "@inertiajs/inertia";

defineProps({
    links: {
        type: Array,
        required: true
    },
    levels: {
        type: Array,
        default: () => []
    }
});

const el = ref<FormInst>();
watchEffect(() => {
    el.value?.validate();
});

const rules = {
    linkID: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.linkID)
    },
    levelID: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.levelID)
    }
}

const form = useForm({
    linkID: null,
    levelID: null
});

watchEffect(() => {
    if (form.linkID) {
        Inertia.reload({
            data: {link: form.linkID},
            only: ['levels']
        });
    }
});
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="关卡转移(进)">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="账号" path="linkID">
                <n-select v-model:value="form.linkID" :options="links"/>
            </n-form-item>

            <n-form-item label="关卡" path="levelID">
                <n-select v-model:value="form.levelID" :options="levels"/>
            </n-form-item>

            <n-button :disabled="form.processing" @click="form.post( route('gdcs.tools.level.transfer.in.api') )">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
