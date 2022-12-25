<script lang="ts" setup>
import {useAppStore} from "@/scripts/core/stores";
import GlobalApiInjector from "@/views/components/GlobalApiInjector.vue";
import BackendMessageReceiver from "@/views/components/BackendMessageReceiver.vue";

const appStore = useAppStore();
</script>

<template>
    <n-config-provider :theme="appStore.themeRef" class="h-full">
        <n-message-provider>
            <n-dialog-provider>
                <n-notification-provider>
                    <n-loading-bar-provider>
                        <GlobalApiInjector/>
                        <slot/>
                        <BackendMessageReceiver/>
                    </n-loading-bar-provider>
                </n-notification-provider>
            </n-dialog-provider>
        </n-message-provider>
    </n-config-provider>
</template>
