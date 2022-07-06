<script setup lang="ts">
import { FormInst, NButton, NCard, NForm, NFormItem, NInput, NSpace } from 'naive-ui'
import { ref, watch, watchEffect } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'
import { toRoute } from '@/scripts/helpers'

const el = ref<FormInst>()
watch(el, element => {
  if (element) {
    element.validate()
  }
})

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
}

const form = useForm({
  name: null,
  password: null
})
</script>

<template layout="GDCN">
    <n-card class="lg:w-1/3 mx-auto" title="登录">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="用户名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-input v-model:value="form.password" type="password"/>
            </n-form-item>

            <n-space justify="space-between">
                <n-button :disabled="form.processing" @click="form.post( route('login.api') )">登录</n-button>
                <n-button text @click="toRoute('register')">没有账号? 去注册</n-button>
            </n-space>
        </n-form>
    </n-card>
</template>
