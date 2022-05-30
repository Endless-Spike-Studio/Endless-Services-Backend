<script lang="ts" setup>
import {getProp} from "@/scripts/helpers";
import {User} from "@/scripts/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import {FormInst, NButton, NCard, NForm, NFormItem, NInput} from "naive-ui";
import {ref, watchEffect} from "vue";
import route from "@/scripts/route";

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

const account = getProp<User>('gdcs.account');
const form = useForm({
    name: account.value.name,
    email: account.value.email,
    password: null,
    password_confirmation: null
});
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="账号设置">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="用户名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="邮箱" path="email">
                <n-input v-model:value="form.email" type="email"/>
            </n-form-item>

            <n-form-item label="密码 (留空不变)" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-form-item v-if="form.password" label="确认密码" path="password_confirmation">
                <n-input v-model:value="form.password_confirmation" type="password"/>
            </n-form-item>

            <n-form-item>
                <n-button
                    :disabled="!form.isDirty || form.processing || (form.password !== '' && form.password !== form.password_confirmation)"
                    @click="form.patch( route('gdcs.account.setting.update.api') )">
                    修改
                </n-button>
            </n-form-item>
        </n-form>
    </n-card>
</template>
