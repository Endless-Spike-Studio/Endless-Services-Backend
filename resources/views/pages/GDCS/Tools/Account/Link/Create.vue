<script lang="ts" setup>
import {ref, watch} from "vue";
import {FormInst, FormRules, SelectOption} from "naive-ui";
import {useForm} from "@inertiajs/inertia-vue3";
import {sample} from "lodash-es";
import route from "@/scripts/core/route";

const rules = {
    server: {
        required: true,
        type: 'string',
        validator: () => Promise.reject(form.errors.server)
    },
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

const serverOptions = [
    {
        label: '官服',
        value: 'http://www.boomlings.com/database'
    }
] as SelectOption[];

const el = ref<FormInst>();
const form = useForm({
    server: sample(serverOptions)!.value,
    name: null,
    password: null
});

watch([el, form], () => {
    el.value?.validate();
});

function create() {
    form.put(
        route('gdcs.tools.account.link.create.api')
    );
}
</script>

<template>
    <n-el class="mt-5">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="服务器" path="server">
                <n-select v-model:value="form.server" :options="serverOptions"/>
            </n-form-item>

            <n-form-item label="用户名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-space justify="space-between">
                <n-button :disabled="form.processing" :loading="form.processing" @click="create">提交</n-button>
            </n-space>
        </n-form>
    </n-el>
</template>
