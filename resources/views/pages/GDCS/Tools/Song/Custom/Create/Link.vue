<script lang="ts" setup>
import { FormInst, FormRules, NButton, NCard, NForm, NFormItem, NInput } from 'naive-ui'
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'

const el = ref<FormInst>()
const form = useForm({
  name: null,
  artist_name: null,
  link: null
})

const rules = {
  name: {
    type: 'string',
    required: true,
    validator: () => Promise.reject(form.errors.name)
  },
  artist_name: {
    type: 'string',
    required: true,
    validator: () => Promise.reject(form.errors.artist_name)
  },
  link: {
    type: 'string',
    required: true,
    validator: () => Promise.reject(form.errors.link)
  }
} as FormRules

watch([el, form], () => {
  if (el.value) {
    el.value.validate()
  }
})

function submit () {
  form.post(
    route('gdcs.tools.song.custom.create.link.api')
  )
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="自定义歌曲上传 (外链)">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="歌曲名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="歌手名" path="artist_name">
                <n-input v-model:value="form.artist_name"/>
            </n-form-item>

            <n-form-item label="歌曲外链" path="link">
                <n-input v-model:value="form.link"/>
            </n-form-item>

            <n-button :disabled="form.processing" @click="submit">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
