<script lang="ts" setup>
import {
    FormInst,
    FormRules,
    NButton,
    NButtonGroup,
    NCard,
    NForm,
    NFormItem,
    NInputNumber,
    NSelect,
    NSpace,
    NSwitch,
    NText
} from 'naive-ui'
import {useForm} from '@inertiajs/inertia-vue3'
import {GDCS} from '@/scripts/types/backend'
import {isMobile, toRouteWithParams} from '@/scripts/helpers'
import {ref, watch} from 'vue'
import levelRatingDifficulties from '@/scripts/enums/levelRatingDifficulties'
import {map} from 'lodash-es'
import levelRatingDemonDifficulties from '@/scripts/enums/levelRatingDemonDifficulties'
import route from '@/scripts/route'

const props = defineProps<{
    level: GDCS.Level
}>()

const el = ref<FormInst>()
const form = useForm({
    stars: props.level.rating.stars,
    difficulty: props.level.rating.difficulty.toString(),
    featured_score: props.level.rating.featured_score,
    epic: props.level.rating.epic,
    coin_verified: props.level.rating.coin_verified,
    demon_difficulty: props.level.rating.demon_difficulty.toString(),
    auto: props.level.rating.auto,
    demon: props.level.rating.demon
})

const rules = {
    stars: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.stars)
    },
    difficulty: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.difficulty)
    },
    featured_score: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.featured_score)
    },
    epic: {
        type: 'boolean',
        required: true,
        validator: () => Promise.reject(form.errors.epic)
    },
    coin_verified: {
        type: 'boolean',
        required: true,
        validator: () => Promise.reject(form.errors.coin_verified)
    },
    demon_difficulty: {
        type: 'number',
        required: true,
        validator: () => Promise.reject(form.errors.demon_difficulty)
    },
    auto: {
        type: 'boolean',
        required: true,
        validator: () => Promise.reject(form.errors.auto)
    },
    demon: {
        type: 'boolean',
        required: true,
        validator: () => Promise.reject(form.errors.demon)
    }
} as FormRules

const difficultyOptions = map(levelRatingDifficulties, (difficulty: string, value: number) => {
    return {
        label: difficulty.toString(),
        value: value.toString()
    }
})

const demonDifficultyOptions = map(levelRatingDemonDifficulties, (difficulty: string, value: number) => {
    return {
        label: difficulty.toString(),
        value: value.toString()
    }
})

watch([el, form], () => {
    if (el.value) {
        el.value.validate()
    }
})

function applyPreset(name: string) {
    switch (name) {
        case 'un_rate':
            form.reset()
            break
        case 'featured':
            form.featured_score = 1
            break
        case 'epic':
            form.epic = true
            break
        case 'coin_verify':
            form.coin_verified = true
            break
        case 'auto':
            form.stars = 1
            form.difficulty = '60'
            form.auto = true
            form.demon = false
            break
        case 'easy':
            form.stars = 2
            form.difficulty = '10'
            form.auto = false
            form.demon = false
            break
        case 'normal':
            form.stars = 3
            form.difficulty = '20'
            form.auto = false
            form.demon = false
            break
        case 'hard':
            form.stars = 4
            form.difficulty = '30'
            form.auto = false
            form.demon = false
            break
        case 'harder':
            form.stars = 6
            form.difficulty = '40'
            form.auto = false
            form.demon = false
            break
        case 'insane':
            form.stars = 8
            form.difficulty = '50'
            form.auto = false
            form.demon = false
            break
        case 'easy_demon':
            form.stars = 10
            form.difficulty = '60'
            form.auto = false
            form.demon = true
            form.demon_difficulty = '3'
            break
        case 'medium_demon':
            form.stars = 10
            form.difficulty = '60'
            form.auto = false
            form.demon = true
            form.demon_difficulty = '4'
            break
        case 'hard_demon':
            form.stars = 10
            form.difficulty = '60'
            form.auto = false
            form.demon = true
            form.demon_difficulty = '0'
            break
        case 'insane_demon':
            form.stars = 10
            form.difficulty = '60'
            form.auto = false
            form.demon = true
            form.demon_difficulty = '5'
            break
        case 'extreme_demon':
            form.stars = 10
            form.difficulty = '60'
            form.auto = false
            form.demon = true
            form.demon_difficulty = '6'
            break
    }
}

function submit() {
    form.post(
        route('gdcs.admin.level.rate.api', [props.level.id])
    )
}
</script>

<template layout="GDCS">
    <n-space vertical>
        <n-card class="lg:w-1/3 mx-auto" title="快捷设置">
            <n-space :vertical="!isMobile" justify="center">
                <div class="mx-auto text-center">
                    <n-button-group :vertical="isMobile">
                        <n-button @click="applyPreset('un_rate')">取消评分</n-button>
                        <n-button @click="applyPreset('featured')">Featured</n-button>
                        <n-button @click="applyPreset('epic')">Epic</n-button>
                        <n-button @click="applyPreset('coin_verify')">银币</n-button>
                    </n-button-group>
                </div>

                <div class="mx-auto text-center">
                    <n-button-group :vertical="isMobile">
                        <n-button @click="applyPreset('auto')">Auto</n-button>
                        <n-button @click="applyPreset('easy')">Easy</n-button>
                        <n-button @click="applyPreset('normal')">Normal</n-button>
                        <n-button @click="applyPreset('hard')">Hard</n-button>
                        <n-button @click="applyPreset('harder')">Harder</n-button>
                        <n-button @click="applyPreset('insane')">Insane</n-button>
                    </n-button-group>
                </div>

                <n-button-group :vertical="isMobile" class="mx-auto">
                    <n-button @click="applyPreset('easy_demon')">Easy Demon</n-button>
                    <n-button @click="applyPreset('medium_demon')">Medium Demon</n-button>
                    <n-button @click="applyPreset('hard_demon')">Hard Demon</n-button>
                    <n-button @click="applyPreset('insane_demon')">Insane Demon</n-button>
                    <n-button @click="applyPreset('extreme_demon')">Extreme Demon</n-button>
                </n-button-group>
            </n-space>
        </n-card>

        <n-card class="lg:w-1/5 mx-auto">
            <template #header>
                <n-space justify="space-between">
                    <n-text>关卡评分</n-text>
                    <n-button text type="primary" @click="toRouteWithParams( 'gdcs.dashboard.level.info', level.id )">
                        {{ level.name }} [{{ level.id }}]
                    </n-button>
                </n-space>
            </template>

            <n-form ref="el" :model="form" :rules="rules">
                <n-form-item label="星星" path="stars">
                    <n-input-number v-model:value="form.stars"/>
                </n-form-item>

                <n-form-item label="脸" path="difficulty">
                    <n-select v-model:value="form.difficulty" :options="difficultyOptions"/>
                </n-form-item>

                <n-form-item label="特色分数" path="featured_score">
                    <n-input-number v-model:value="form.featured_score"/>
                </n-form-item>

                <n-space justify="space-evenly">
                    <n-form-item label="Epic" path="epic">
                        <n-switch v-model:value="form.epic"/>
                    </n-form-item>

                    <n-form-item label="银币" path="coin_verified">
                        <n-switch v-model:value="form.coin_verified"/>
                    </n-form-item>
                </n-space>

                <n-form-item label="恶魔脸" path="demon_difficulty">
                    <n-select v-model:value="form.demon_difficulty" :options="demonDifficultyOptions"/>
                </n-form-item>

                <n-space justify="space-evenly">
                    <n-form-item label="自动" path="auto">
                        <n-switch v-model:value="form.auto"/>
                    </n-form-item>

                    <n-form-item label="恶魔" path="demon">
                        <n-switch v-model:value="form.demon"/>
                    </n-form-item>
                </n-space>

                <n-button class="w-full" @click="submit">保存
                </n-button>
            </n-form>
        </n-card>
    </n-space>
</template>
