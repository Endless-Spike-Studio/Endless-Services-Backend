<script lang="ts" setup>
import {isMobile, useProp} from "@/scripts/core/utils";
import {App} from "@/scripts/types/backend";
import {DataTableColumn} from "naive-ui";
import AccountSelectAction from "@/views/pages/GDCS/Tools/Level/Transfer/Out/AccountSelectAction.vue";
import {useForm} from "@inertiajs/inertia-vue3";
import route from "@/scripts/core/route";

const props = defineProps<{
    levelID: number
}>();

const accountColumns = [
    {
        title: 'ID',
        key: 'id'
    },
    {
        title: '服务器',
        key: 'server'
    },
    {
        title: '绑定账号',
        render: (item: App.Models.GDCS.AccountLink) => `${item.target_name} [${item.target_account_id}, ${item.target_user_id}]`
    },
    {
        title: '操作',
        render: (item: App.Models.GDCS.AccountLink) => {
            const form = useForm({
                levelID: props.levelID,
                linkID: -1,
                password: null
            });

            return h(AccountSelectAction, {
                item, form,
                onSelect: (item: App.Models.GDCS.AccountLink) => {
                    form.linkID = item.id;
                    currentForm.value = form;
                    currentStep.value = 2;
                    console.log(currentForm, form);
                }
            }, () => '选择');
        }
    }
] as DataTableColumn[];

const currentStep = ref(1);
const currentForm = ref();
const links = useProp<App.Models.GDCS.AccountLink[]>('links');

function submit() {
    currentForm.value
        ?.post(route('gdcs.tools.level.transfer.out.api'))
}
</script>

<template layout="GDCS">
    <n-card :class="{ 'mx-auto': true, 'lg:w-1/2': currentStep === 1, 'lg:w-1/3': currentStep === 2 }" title="关卡转出">
        <n-tabs v-model:value="currentStep" animated tab-style="display: none;">
            <n-tab-pane :name="1">
                <n-data-table :columns="accountColumns" :data="links"
                              :max-height="isMobile ? 500 : undefined"
                              :pagination="{ pageSize: 10 }" :scroll-x="isMobile ? 1000 : undefined"
                              :virtual-scroll="isMobile"/>
            </n-tab-pane>

            <n-tab-pane :name="2">
                <n-space v-if="currentForm" vertical>
                    <n-form-item label="确认密码">
                        <n-input v-model:value="currentForm.password" type="password"/>
                    </n-form-item>

                    <n-button :disabled="currentForm.processing || !currentForm.password" @click="submit">
                        转出
                    </n-button>
                </n-space>

                <n-text v-else type="error">发生了错误 可以试着刷新一下 (?</n-text>
            </n-tab-pane>
        </n-tabs>
    </n-card>
</template>
