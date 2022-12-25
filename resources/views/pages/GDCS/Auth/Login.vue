<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import {to_route} from "@/scripts/core/utils";
import route from "@/scripts/core/route";

const form = useForm({
    name: null,
    password: null
});

function submit() {
    form.post(route('gdcs.auth.login.api'));
}
</script>

<template>
    <CommonLayout>
        <n-card class="lg:mx-auto mx-2.5 mt-10 lg:w-1/3">
            <n-form-item label="用户名">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="密码">
                <n-input v-model:value="form.password" show-password-toggle type="password"/>
            </n-form-item>

            <n-space class="w-full" justify="space-between">
                <n-button :disabled="form.processing" @click="submit()">登录</n-button>
                <n-button text @click="to_route('gdcs.auth.register')">还没账号? 去注册</n-button>
            </n-space>
        </n-card>
    </CommonLayout>
</template>
