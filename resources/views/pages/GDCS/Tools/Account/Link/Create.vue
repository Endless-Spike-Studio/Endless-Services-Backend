<script lang="ts" setup>
import {FormInst, FormRules, NButton, NCard, NForm, NFormItem, NInput, NSelect, NTabPane, NTabs} from 'naive-ui'
import {ref, watch} from 'vue'
import {useForm} from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'
import servers from '@/scripts/enums/servers'
import {map} from 'lodash-es'

const el = ref<FormInst>()
const form = useForm({
    server: 'http://www.boomlings.com/database',
    name: null,
    password: null
})

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
} as FormRules

const serverOptions = map(servers, server => {
    return {
        label: `${server.name} [${server.id}]`,
        value: server.host
    }
})

watch([el, form], () => {
    if (el.value) {
        el.value.validate()
    }
})

function submit () {
    form.post(
        route('gdcs.tools.account.link.create.api')
    )
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="创建新链接">
        <n-form ref="el" :model="form" :rules="rules">
            <n-tabs animated type="line">
                <n-tab-pane name="switch" tab="简单">
                    <n-form-item label="服务器" path="server">
                        <n-select v-model:value="form.server" :options="serverOptions"/>
                    </n-form-item>
                </n-tab-pane>
                <n-tab-pane name="custom" tab="高级自定义">
                    <n-form-item label="服务器" path="server">
                        <n-input v-model:value="form.server" placeholder="www.boomlings.com"/>
                    </n-form-item>
                </n-tab-pane>
            </n-tabs>

            <n-form-item label="用户名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-button :disabled="form.processing" @click="submit">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
