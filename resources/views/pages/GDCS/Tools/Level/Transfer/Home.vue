<script lang="ts" setup>
import {NIcon} from "naive-ui";
import {ExtraMenuOption} from "@/scripts/types/menu";
import {ArrowBarToLeft, ArrowBarToRight} from "@vicons/tabler";
import {App} from "@/scripts/types/backend";
import AccountSelectorForTransferIn from "@/views/pages/GDCS/Tools/Level/Transfer/In/AccountSelectorForHome.vue";
import LevelSelectorForTransferOut from "@/views/pages/GDCS/Tools/Level/Transfer/Out/LevelSelectorForHome.vue";

defineProps<{
    links: App.Models.GDCS.AccountLink[]
}>();

const aside = reactive({
    active: ref('in'),
    options: [
        {
            label: '转入',
            key: 'in',
            icon: () => h(NIcon, {
                component: ArrowBarToRight
            })
        },
        {
            label: '转出',
            key: 'out',
            icon: () => h(NIcon, {
                component: ArrowBarToLeft
            })
        }
    ] as ExtraMenuOption[]
});
</script>

<template layout="GDCS">
    <n-el class="lg:w-2/3 mx-auto">
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:3">
            <n-grid-item class="text-left">
                <n-card content-style="padding: 0;">
                    <n-menu v-model:value="aside.active" :options="aside.options"/>
                </n-card>
            </n-grid-item>

            <n-grid-item :span="2">
                <n-tabs v-model:value="aside.active" animated tab-style="display: none;">
                    <n-tab-pane name="in">
                        <n-card>
                            <AccountSelectorForTransferIn/>
                        </n-card>
                    </n-tab-pane>

                    <n-tab-pane name="out">
                        <n-card>
                            <LevelSelectorForTransferOut/>
                        </n-card>
                    </n-tab-pane>
                </n-tabs>
            </n-grid-item>
        </n-grid>
    </n-el>
</template>
