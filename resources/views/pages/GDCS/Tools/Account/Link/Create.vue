<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {servers} from "@/scripts/core/shared";
import {FormInst, SelectOption} from "naive-ui";
import {first} from "lodash-es";
import route from "@/scripts/core/route";
import {createRules} from "@/scripts/core/utils";

const form = useForm({
    server: first(servers)?.address,
    name: null,
    password: null
});

const formRef = ref<FormInst>();
const rules = createRules(form);

const serverOptions = computed(() => {
    return servers.map(server => {
        return {
            label: server.name,
            value: server.address
        } as SelectOption;
    });
});

function submit() {
    formRef.value?.validate(errors => {
        if (!errors) {
            form.post(route('gdcs.tools.account.link.create.api'), {
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
        <n-card title="链接创建">
            <n-form ref="formRef" :model="form" :rules="rules">
                <n-form-item label="服务器">
                    <n-select v-model:value="form.server" :options="serverOptions"/>
                </n-form-item>

                <n-form-item label="用户名">
                    <n-input v-model:value="form.name"/>
                </n-form-item>

                <n-form-item label="密码">
                    <n-input v-model:value="form.password" show-password-on="click" type="password"/>
                </n-form-item>

                <n-form-item>
                    <n-button :disabled="form.processing" :loading="form.processing" @click="submit">
                        提交
                    </n-button>
                </n-form-item>
            </n-form>
        </n-card>
    </CommonLayout>
</template>
