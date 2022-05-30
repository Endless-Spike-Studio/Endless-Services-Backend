<script setup lang="ts">
import {NButton, NCard, NDataTable, NSpace} from "naive-ui";
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

            return h(NSpace, null, () => [
                h(NButton, {
                    disabled: form.processing,
                    onClick: () => form.delete(
                        route('gdcs.tools.level.temp_upload_access.delete.api', {
                            id: row.id
                        })
                    )
                }, {
                    default: () => '删除'
                })
            ]);
        }
    }
]
</script>

<template layout="GDCS">
    <n-card title="临时关卡上传许可" class="lg:w-2/3 mx-auto">
        <n-space vertical>
            <n-button @click="toRoute('gdcs.tools.level.temp_upload_access.create.api')">创建新的临时关卡上传许可</n-button>
            <n-data-table :columns="columns" :data="accesses"/>
        </n-space>
    </n-card>
</template>
