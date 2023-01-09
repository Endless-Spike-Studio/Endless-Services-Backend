<script lang="ts" setup>
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";
import {FileAddTwotone} from "@vicons/antd";
import {useApiStore} from "@/scripts/core/stores";
import {FormInst, UploadFileInfo} from "naive-ui";
import {get} from "lodash-es";
import {createRules} from "@/scripts/core/utils";

const apiStore = useApiStore();
type SongFile = File | null;

const form = useForm({
    name: null,
    artist_name: null,
    file: null as SongFile
});

const formRef = ref<FormInst>();
const rules = createRules(form);

function checkFile(_: File) {
    if (_.type.split('/')[0] !== 'audio') {
        apiStore.$message.error('请上传音频文件');
        return false;
    }

    return true;
}

function handleFilesUpdate(files: UploadFileInfo[]) {
    form.file = get(files, '0.file', null);
}

function submit() {
    formRef.value?.validate(errors => {
        if (!errors && form.file !== null && checkFile(form.file)) {
            form.post(route('gdcs.tools.song.custom.create.file.api'), {
                forceFormData: true,
                onFinish() {
                    formRef.value?.validate();
                    form.clearErrors();
                }
            });
        }
    })
}
</script>

<template>
    <n-card>
        <n-form ref="formRef" :model="form" :rules="rules">
            <n-form-item label="歌曲名">
                <n-input v-model:value="form.name"/>
            </n-form-item>

            <n-form-item label="歌手名">
                <n-input v-model:value="form.artist_name"/>
            </n-form-item>

            <n-form-item label="歌曲文件">
                <n-upload :max="1" @update:fileList="handleFilesUpdate">
                    <n-upload-dragger>
                        <n-icon :component="FileAddTwotone" class="block mb-2.5 mx-auto" size="50"/>

                        <n-text>
                            点击或者拖动文件到该区域来上传
                        </n-text>
                    </n-upload-dragger>
                </n-upload>
            </n-form-item>

            <n-form-item>
                <n-button :disabled="!form.isDirty || form.processing" :loading="form.processing"
                          @click="submit">
                    提交
                </n-button>
            </n-form-item>
        </n-form>
    </n-card>
</template>
