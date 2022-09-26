<script lang="ts" setup>
import {FormInst, FormRules} from 'naive-ui'
import {useForm} from '@inertiajs/inertia-vue3'
import route from '@/scripts/core/route'
import {visit_route} from '@/scripts/core/utils'
import {ref, watch} from "vue";

const el = ref<FormInst>();
const form = useForm({
    name: null,
    password: null
});

watch([el, form], () => {
    el.value?.validate();
});

const rules = {
    name: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.name)
    },
    password: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.password)
    }
} as FormRules;

function login() {
    form.post(
        route('gdcs.auth.login.api')
    );
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="登录">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="用户名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-space justify="space-between">
                <n-button :disabled="form.processing" :loading="form.processing" @click="login">登录</n-button>
                <n-button text @click="visit_route('gdcs.auth.register')">没有账号? 去注册</n-button>
            </n-space>
        </n-form>
    </n-card>
</template>
