<script lang="ts" setup>
import {ref, watch} from 'vue'
import {FormInst, FormRules, NButton, NCard, NForm, NFormItem, NSelect} from 'naive-ui'
import {useForm} from '@inertiajs/inertia-vue3'
import route from '@/scripts/route'
import {Inertia} from '@inertiajs/inertia'
import {GDCS} from '@/scripts/types/backend'

withDefaults(
    defineProps<{
        links: GDCS.AccountLink[],
        levels?: GDCS.Level[]
    }>(),
    {
        levels: () => []
    }
)

const el = ref<FormInst>()
const form = useForm({
    linkID: null,
    levelID: null
})

const rules = {
    linkID: {
        type: 'string',
        required: true,
        validator: () => Promise.reject(form.errors.linkID)
    },
    levelID: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.levelID)
    }
} as FormRules

watch(form, newForm => {
    if (newForm.linkID) {
        Inertia.reload({
            data: {
                link: newForm.linkID
            },
            only: ['levels']
        })
    }
})

watch([el, form], () => {
    if (el.value) {
        el.value.validate()
    }
})

function submit () {
    form.post(
        route('gdcs.tools.level.transfer.in.api')
    )
}
</script>

<template layout="GDCS">
    <n-card class="lg:w-1/3 mx-auto" title="关卡转移(进)">
        <n-form ref="el" :model="form" :rules="rules">
            <n-form-item label="账号" path="linkID">
                <n-select v-model:value="form.linkID" :options="links"/>
            </n-form-item>

            <n-form-item label="关卡" path="levelID">
                <n-select v-model:value="form.levelID" :options="levels"/>
            </n-form-item>

            <n-button :disabled="form.processing" @click="submit">
                提交
            </n-button>
        </n-form>
    </n-card>
</template>
