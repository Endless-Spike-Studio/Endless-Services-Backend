<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";
import {formatTime} from "@/scripts/core/utils";
import {Inertia} from "@inertiajs/inertia";

const props = defineProps<{
    accesses: App.Models.LevelTempUploadAccess[]
}>();

const forms = computed(() => {
    return props.accesses.reduce(function (data, access) {
        data[access.id] = useForm({});
        return data;
    }, {} as Record<number, unknown>);
});
</script>

<template>
    <CommonLayout>
        <n-card class="lg:w-3/5 lg:mx-auto mx-2.5 mt-2.5" title="临时关卡上传许可">
            <template #header-extra>
                <n-button @click="Inertia.post(route('gdcs.tools.level.temp_upload_access.create.api'))">
                    创建新许可
                </n-button>
            </template>

            <n-list v-if="accesses.length > 0" bordered>
                <n-list-item v-for="access in accesses">
                    <n-thing>
                        <template #header>
                            {{ access.id }} 号许可
                        </template>

                        <template #description>
                            <n-text :depth="3" class="text-sm">
                                绑定IP: {{ access.ip }}
                                <br>
                                创建于 {{ formatTime(access.created_at) }}
                            </n-text>
                        </template>
                    </n-thing>

                    <template #suffix>
                        <n-space>
                            <n-button :disabled="forms[access.id].processing" :loading="forms[access.id].processing"
                                      type="error"
                                      @click="forms[access.id].delete(route('gdcs.tools.level.temp_upload_access.delete.api', access.id))">
                                销毁
                            </n-button>
                        </n-space>
                    </template>
                </n-list-item>
            </n-list>

            <n-empty v-else/>
        </n-card>
    </CommonLayout>
</template>
