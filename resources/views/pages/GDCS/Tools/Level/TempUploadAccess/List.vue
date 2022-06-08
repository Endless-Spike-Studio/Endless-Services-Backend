<script lang="ts" setup>
import {NButton, NCard, NDataTable, NPopconfirm, NSpace} from "naive-ui";
import {formatTime, toRoute} from "@/scripts/helpers";
import {TempLevelUploadAccess} from "@/scripts/types/backend";
import {h} from "vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/route";

defineProps({
    accesses: {
        type: Array,
        required: true
    }
});

const columns = [
    {
        title: 'ID',
        key: 'id'
    },
    {
        title: '绑定IP',
        key: 'ip'
    },
    {
        title: '创建时间',
        key: 'created_at',
        render: (row: TempLevelUploadAccess) => formatTime(row.created_at, '未知')
    },
    {
        title: '操作',
        key: 'action',
        render: (row: TempLevelUploadAccess) => {
            const form = useForm({});

            return h(NPopconfirm, {
                onPositiveClick: () => form.delete(
                    route('gdcs.tools.level.temp_upload_access.delete.api', {
                        id: row.id
                    })
                )
            }, {
                default: () => '确认删除 ?',
                trigger: () => h(NButton, {
                    type: 'error',
                    disabled: form.processing
                }, {
                    default: () => '删除'
                })
            });
        }
    }
]
</script>

<template layout="GDCS">
    <n-card class="lg:w-2/3 mx-auto" title="临时关卡上传许可">
        <n-space vertical>
            <n-button @click="toRoute('gdcs.tools.level.temp_upload_access.create.api')">创建新的临时关卡上传许可</n-button>
            <n-data-table :columns="columns" :data="accesses"/>
        </n-space>
    </n-card>
</template>
