<script lang="ts" setup>
import CommonLayout from "@/views/layouts/GDCS/Common.vue";
import {App} from "@/types/backend";
import {MenuOption, NIcon} from "naive-ui";
import {InfoCircleTwotone, SettingTwotone} from "@vicons/antd";
import DetailsTab from "@/views/pages/GDCS/Dashboard/Level/Tabs/Details.vue";
import ScoresTab from "@/views/pages/GDCS/Dashboard/Level/Tabs/Scores.vue";
import CommentsTab from "@/views/pages/GDCS/Dashboard/Level/Tabs/Comments.vue";
import SettingsTab from "@/views/pages/GDCS/Dashboard/Level/Tabs/Settings.vue";
import {LeaderboardTwotone} from "@vicons/material";
import {CommentRegular} from "@vicons/fa";

const props = defineProps<{
    level: App.Models.Level;
    can: {
        edit: boolean;
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
            label: '评论',
            key: 'comments',
            icon: () => h(NIcon, {
                component: CommentRegular
            }),
            render: () => h(CommentsTab)
        },
        {
            label: '排行榜',
            key: 'scores',
            icon: () => h(NIcon, {
                component: LeaderboardTwotone
            }),
            render: () => h(ScoresTab)
        },
        {
            label: '管理',
            type: 'group',
            key: 'manage',
            show: props.can.edit,
            children: [
                {
                    label: '设置',
                    key: 'settings',
                    icon: () => h(NIcon, {
                        component: SettingTwotone
                    }),
                    render: () => h(SettingsTab, {
                        onSubmitted() {
                            menu.active = 'details';
                        }
                    })
                }
            ] as MenuOption[]
        }
    ] as MenuOption[],
    items: computed(() => {
        const result = new Array<MenuOption>;

        menu.options.forEach(option => {
            if ('render' in option) {
                result.push(option as MenuOption);
            }

            if ('children' in option) {
                result.push(...option.children as MenuOption[]);
            }
        });

        return result;
    })
});
</script>

<template>
    <CommonLayout>
        <n-grid :x-gap="10" :y-gap="10" cols="1 640:4">
            <n-grid-item>
                <n-card :content-style="{ padding: 0 }">
                    <n-menu v-model:value="menu.active" :options="menu.options" mode="vertical"/>
                </n-card>
            </n-grid-item>

            <n-grid-item :span="3">
                <n-tabs v-model:value="menu.active" :tab-style="{ display: 'none' }" animated pane-class="!p-0">
                    <n-tab-pane v-for="option in menu.items" :name="option.key">
                        <Component :is="option.render"/>
                    </n-tab-pane>
                </n-tabs>
            </n-grid-item>
        </n-grid>
    </CommonLayout>
</template>
