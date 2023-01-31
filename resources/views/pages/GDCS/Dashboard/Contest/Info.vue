<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {MenuOption, NIcon} from "naive-ui";
import {InfoCircleTwotone, UploadOutlined} from "@vicons/antd";
import DetailsTab from "@/views/pages/GDCS/Dashboard/Contest/Tabs/Details.vue";
import ParticipantsTab from "@/views/pages/GDCS/Dashboard/Contest/Tabs/Participants.vue";
import SubmitTab from "@/views/pages/GDCS/Dashboard/Contest/Tabs/Submit.vue";
import {Users} from "@vicons/tabler";
import Grid from "@/views/components/Grid.vue";
import {isMobile} from "@/scripts/core/utils";
import {Head} from "@inertiajs/vue3";

const props = defineProps<{
    contest: App.Models.Contest;
    can: {
        submit: boolean;
    }
}>();

const menu = reactive({
    active: ref('details'),
    options: [
        {
            label: '详细信息',
            key: 'details',
            icon: () => h(NIcon, {
                component: InfoCircleTwotone
            }),
            render: () => h(DetailsTab)
        },
        {
            label: '参与者',
            key: 'participants',
            icon: () => h(NIcon, {
                component: Users
            }),
            render: () => h(ParticipantsTab)
        },
        {
            label: '投稿',
            key: 'submit',
            disabled: !props.can.submit,
            icon: () => h(NIcon, {
                component: UploadOutlined
            }),
            render: () => h(SubmitTab, {
                onSubmitted() {
                    menu.active = 'participants';
                }
            })
        }
    ] as MenuOption[]
});
</script>

<template>
    <CommonLayout>
        <Head>
            <title>比赛 - {{ contest.name }}</title>
        </Head>

        <Grid :cols="isMobile ? 1 : 4">
            <n-grid-item>
                <n-card :content-style="{ padding: 0 }">
                    <n-menu v-model:value="menu.active" :options="menu.options" mode="vertical"/>
                </n-card>
            </n-grid-item>

            <n-grid-item :span="3">
                <n-tabs v-model:value="menu.active" :tab-style="{ display: 'none' }" animated pane-class="!p-0">
                    <n-tab-pane v-for="option in menu.options" :name="option.key">
                        <Component :is="option.render"/>
                    </n-tab-pane>
                </n-tabs>
            </n-grid-item>
        </Grid>
    </CommonLayout>
</template>
