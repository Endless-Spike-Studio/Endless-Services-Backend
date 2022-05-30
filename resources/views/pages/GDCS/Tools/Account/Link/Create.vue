<script setup lang="ts">
import {FormInst, NButton, NCard, NForm, NFormItem, NInput, NSelect} from "naive-ui";
import {ref, watchEffect} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

defineProps({
    servers: {
        type: Array,
        required: true
    }
});

const el = ref<FormInst>();
watchEffect(() => {
    el.value?.validate();
});

const rules = {
    server: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.server)
    },
    name: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.name)
    },
    password: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.password)
    }
}

const form = useForm({
    server: null,
    name: null,
    password: null
});
</script>

<template layout="GDCS">
    <n-card title="创建新链接" class="lg:w-1/3 mx-auto">
        <n-form ref="el" :rules="rules" :model="form">
            <n-form-item label="服务器" path="server">
                <n-select v-model:value="form.server" :options="servers"/>
            </n-form-item>

            <n-form-item label="用户名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-button :disabled="form.processing" @click="form.post( route('gdcs.tools.account.link.create.api') )">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
