<script setup lang="ts">
import {FormInst, NButton, NCard, NForm, NFormItem, NInput, NSelect} from "naive-ui";
import {ref, watchEffect} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

defineProps({
    levels: {
        type: Object,
        required: true
    },
    links: {
        type: Object,
        required: true
    }
});

const el = ref<FormInst>();
watchEffect(() => {
    el.value?.validate();
});

const rules = {
    levelID: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.levelID)
    },
    linkID: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.linkID)
    },
    password: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.password)
    }
}

const form = useForm({
    levelID: null,
    linkID: null,
    password: null
});
</script>

<template layout="GDCS">
    <n-card title="关卡转移(出)" class="lg:w-1/3 mx-auto">
        <n-form ref="el" :rules="rules" :model="form">
            <n-form-item label="关卡" path="levelID">
                <n-select v-model:value="form.levelID" :options="levels"/>
            </n-form-item>

            <n-form-item label="账号" path="linkID">
                <n-select v-model:value="form.linkID" :options="links"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-button :disabled="form.processing" @click="form.post( route('gdcs.tools.level.transfer.out.api') )">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
