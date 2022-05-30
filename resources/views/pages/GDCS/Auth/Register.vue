<script setup lang="ts">
import {ref, watchEffect} from "vue";
import {FormInst, NButton, NCard, NForm, NFormItem, NInput, NSpace} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";
import {toRoute} from "@/scripts/helpers";

const el = ref<FormInst>();
watchEffect(() => {
    el.value?.validate();
});

const rules = {
    name: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.name)
    },
    email: {
        required: true,
        type: 'email',
        validator: () => Promise.reject(form.errors.email)
    },
    password: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.password)
    }
};

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null
});
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="注册">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="用户名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="邮箱" path="email">
                <n-input v-model:value="form.email" type="email"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-form-item label="确认密码" path="password_confirmation">
                <n-input v-model:value="form.password_confirmation" type="password"/>
            </n-form-item>

            <n-space justify="space-between">
                <n-button :disabled="form.processing || form.password !== form.password_confirmation"
                          @click="form.post( route('gdcs.register.api') )">
                    注册
                </n-button>

                <n-button text @click="toRoute('gdcs.login')">已有账号? 去登录</n-button>
            </n-space>
        </n-form>
    </n-card>
</template>
