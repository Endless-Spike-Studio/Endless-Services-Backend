<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {createRules, to_route} from "@/scripts/core/utils";
import route from "@/scripts/core/route";
import {FormInst} from "naive-ui";

const form = useForm({
    name: null,
    password: null
});

const formRef = ref<FormInst>();
const rules = createRules(form);

function submit() {
    formRef.value?.validate(errors => {
        if (!errors) {
            form.post(route('gdcs.auth.login.api'), {
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
        <n-el class="sm:w-1/2 sm:mt-64 mx-auto">
            <n-card>
                <n-form ref="formRef" :model="form" :rules="rules">
                    <n-form-item label="用户名" path="name">
                        <n-input v-model:value="form.name"/>
                    </n-form-item>

                    <n-form-item label="密码" path="password">
                        <n-input v-model:value="form.password" show-password-on="click" type="password"/>
                    </n-form-item>
                </n-form>

                <n-space class="w-full" justify="space-between">
                    <n-button :disabled="!form.isDirty || form.processing" :loading="form.processing" @click="submit">
                        登录
                    </n-button>

                    <n-space size="small">
                        <n-button text type="info" @click="to_route('gdcs.auth.forgetName')">
                            忘记用户名
                        </n-button>

                        <n-button text type="info" @click="to_route('gdcs.auth.forgetPassword')">
                            忘记密码
                        </n-button>

                        <n-button text @click="to_route('gdcs.auth.register')">还没账号? 去注册</n-button>
                    </n-space>
                </n-space>
            </n-card>
        </n-el>
    </CommonLayout>
</template>
