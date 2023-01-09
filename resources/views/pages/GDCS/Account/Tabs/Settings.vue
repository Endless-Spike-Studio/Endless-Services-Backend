<script lang="ts" setup>
import {createRules, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {Inertia} from "@inertiajs/inertia";
import {InertiaForm, useForm} from "@inertiajs/inertia-vue3";
import {FormInst} from "naive-ui";
import route from "@/scripts/core/route";

const account = useProp<App.Models.Account>('account');
const settings = useProp<App.Models.Account>('settings');
const $emits = defineEmits(['submitted']);

const form = ref(), rules = ref();
const formRef = ref<FormInst>();

nextTick(() => {
    Inertia.reload({
        only: ['settings'],
        onFinish() {
            form.value = useForm(settings.value);
            rules.value = createRules(form.value);
        }
    });
});

function submit() {
    formRef.value?.validate(errors => {
        if (!errors) {
            (form.value as InertiaForm<{}>)?.post(route('gdcs.account.edit.api', account.value.id), {
                onFinish() {
                    formRef.value?.validate();
                    form.value.clearErrors();
                },
                onSuccess() {
                    $emits('submitted');
                }
            });
        }
    });
}

const changePasswordFormRef = ref<FormInst>();

const changePasswordForm = useForm({
    password: null,
    password_confirmation: null
});

const changePasswordFormRules = createRules(changePasswordForm);

function changePasswordSubmit() {
    changePasswordFormRef.value?.validate(errors => {
        if (!errors) {
            changePasswordForm.post(route('gdcs.account.changePassword.api', account.value.id), {
                onFinish() {
                    changePasswordFormRef.value?.validate();
                    changePasswordForm.clearErrors();
                },
                onSuccess() {
                    $emits('submitted');
                }
            });
        }
    });
}
</script>

<template>
    <n-space vertical>
        <n-card>
            <n-form v-if="settings && form" ref="formRef" :model="form" :rules="rules">
                <n-form-item label="用户名" path="name">
                    <n-input v-model:value="form.name"/>
                </n-form-item>

                <n-form-item label="邮箱" path="email">
                    <n-input v-model:value="form.email"/>
                </n-form-item>

                <n-button :disabled="!form.isDirty || form.processing" :loading="form.processing" @click="submit">
                    提交
                </n-button>
            </n-form>

            <n-empty v-else/>
        </n-card>

        <n-card title="修改密码">
            <n-form ref="changePasswordFormRef" :model="changePasswordForm" :rules="changePasswordFormRules">
                <n-form-item label="新密码" path="password">
                    <n-input v-model:value="changePasswordForm.password" show-password-on="click" type="password"/>
                </n-form-item>

                <n-form-item label="密码确认" path="password_confirmation">
                    <n-input v-model:value="changePasswordForm.password_confirmation" show-password-on="click"
                             type="password"/>
                </n-form-item>

                <n-button :disabled="!changePasswordForm.isDirty || changePasswordForm.processing"
                          :loading="changePasswordForm.processing" @click="changePasswordSubmit">
                    提交
                </n-button>
            </n-form>
        </n-card>
    </n-space>
</template>
