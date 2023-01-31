<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {Head, useForm} from "@inertiajs/vue3";
import {FormInst} from "naive-ui";
import {createRules, to_route} from "@/scripts/core/utils";
import route from "ziggy-js";

const form = useForm({
    name: null,
    email: null
});

const formRef = ref<FormInst>();
const rules = createRules(form);

function submit() {
    formRef.value?.validate(errors => {
        if (!errors) {
            form.post(route('gdcs.auth.find.password.api'), {
                onFinish() {
                    formRef.value?.validate();
                    form.clearErrors();
                }
            });
        }
    });
}
</script>

<template>
    <CommonLayout>
        <Head>
            <title>找回密码</title>
        </Head>

        <n-el class="sm:w-1/2 sm:mt-64 mx-auto">
            <n-card>
                <n-form ref="formRef" :model="form" :rules="rules">
                    <n-form-item label="用户名" path="name">
                        <n-input v-model:value="form.name"/>
                    </n-form-item>

                    <n-form-item label="邮箱" path="email">
                        <n-input v-model:value="form.email"/>
                    </n-form-item>
                </n-form>

                <n-space class="w-full" justify="space-between">
                    <n-button :disabled="!form.isDirty || form.processing" :loading="form.processing" @click="submit">
                        提交
                    </n-button>

                    <n-button class="ml-2.5" text @click="to_route('gdcs.auth.login')">想起来了? 去登录</n-button>
                </n-space>
            </n-card>
        </n-el>
    </CommonLayout>
</template>
