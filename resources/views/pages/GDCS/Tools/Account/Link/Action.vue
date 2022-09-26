<script lang="ts" setup>
import {App} from "@/scripts/types/backend";
import {visit} from "@/scripts/core/utils";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";

const props = defineProps<{
    item: App.Models.GDCS.AccountLink
}>();

const deleteForm = useForm({});

function deleteLink() {
    deleteForm.delete(
        route('gdcs.tools.account.link.delete.api', {
            link: props.item.id
        })
    );
}
</script>

<template>
    <n-space>
        <n-button @click="visit('https://gdbrowser.com/u/' + item.target_name)">
            <n-text>查看</n-text>
        </n-button>

        <n-popconfirm
            negative-text="我手滑了"
            positive-text="确定"
            @positive-click="deleteLink"
        >
            <template #trigger>
                <n-button type="error">删除</n-button>
            </template>

            <n-text>确定要删除吗?</n-text>
        </n-popconfirm>
    </n-space>
</template>
