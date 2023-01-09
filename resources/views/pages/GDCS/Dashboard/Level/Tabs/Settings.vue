<script lang="ts" setup>
import {createRules, useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";
import {Inertia} from "@inertiajs/inertia";
import {InertiaForm, useForm} from "@inertiajs/inertia-vue3";
import {FormInst, SelectOption} from "naive-ui";
import {Base64} from "js-base64";
import {tracks} from "@/scripts/core/shared";
import {map} from "lodash-es";
import route from "@/scripts/core/route";

const level = useProp<App.Models.Level>('level');
const settings = useProp<App.Models.Level>('settings');
const $emits = defineEmits(['submitted']);

const form = ref(), rules = ref();
const formRef = ref<FormInst>();

nextTick(() => {
    Inertia.reload({
        only: ['settings'],
        onFinish() {
            const data = ((_) => {
                _.desc = Base64.decode(_.desc);
                _.unlisted = !!(_.unlisted as unknown as number);
                return _;
            })(settings.value);

            form.value = useForm(data);
            rules.value = createRules(form.value);
        }
    });
});

const audioTrackOptions = computed(() => {
    return map(tracks, (track, id) => {
        return {
            label: track.name + ' By ' + track.artist_name,
            value: parseInt(id)
        } as SelectOption;
    });
});

function changeSongType(custom = false) {
    if (custom) {
        form.value.audio_track = 0;
        form.value.song_id = settings.value.song_id || 1;
    } else {
        form.value.audio_track = settings.value.audio_track;
        form.value.song_id = 0;
    }
}

function submit() {
    form.value.desc = Base64.encode(form.value.desc);

    formRef.value?.validate(errors => {
        if (!errors) {
            (form.value as InertiaForm<{}>)?.post(route('gdcs.dashboard.level.edit.api', level.value.id), {
                onFinish() {
                    formRef.value?.validate();
                    form.value.clearErrors();
                },
                onSuccess() {
                    $emits('submitted');
                }
            });
        }
    });
}
</script>

<template>
    <n-card>
        <n-form v-if="settings && form" ref="formRef" :model="form" :rules="rules">
            <n-form-item label="关卡名" path="name">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="简介" path="desc">
                <n-input v-model:value="form.desc" type="textarea"/>
            </n-form-item>

            <n-form-item label="密码" path="password">
                <n-space vertical>
                    <n-radio-group v-model:value="form.password">
                        <n-radio :value="0">不允许 Copy</n-radio>
                        <n-radio :value="1">Free Copy</n-radio>
                    </n-radio-group>

                    <n-input-number v-model:value="form.password" :max="999999" :min="0"/>
                </n-space>
            </n-form-item>

            <n-form-item v-if="form.song_id <= 0" label="官方歌曲" path="audio_track">
                <n-button class="mr-2.5" @click="changeSongType(true)">
                    使用自定义歌曲
                </n-button>

                <n-select v-model:value="form.audio_track" :options="audioTrackOptions"/>
            </n-form-item>

            <n-form-item v-else label="自定义歌曲" path="song_id">
                <n-space>
                    <n-button @click="changeSongType(false)">
                        使用官方歌曲
                    </n-button>

                    <n-input-number v-model:value="form.song_id" :min="1"/>
                </n-space>
            </n-form-item>

            <n-form-item label="不公开" path="unlisted">
                <n-switch v-model:value="form.unlisted"/>
            </n-form-item>

            <n-button :disabled="!form.isDirty || form.processing" :loading="form.processing" @click="submit">
                提交
            </n-button>
        </n-form>

        <n-empty v-else/>
    </n-card>
</template>
