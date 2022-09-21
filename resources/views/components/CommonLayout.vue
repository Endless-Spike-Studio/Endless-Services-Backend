<script lang="ts" setup>
import {isMobile, useProp} from "@/scripts/core/utils";
import {Laravel, Php} from "@vicons/fa";
import {useAppStore} from "@/scripts/core/stores";
import {CommitRound} from "@vicons/material";
import {ref, useSlots} from "vue";
import route from "@/scripts/core/route";
import event from "@/scripts/core/event";
import BackendMessageReceiver from "@/views/components/BackendMessageReceiver.vue";

const versions = useProp<{
    php: string;
    laravel: string;
}>('versions');

const router = (() => {
    const reference = ref();

    function update() {
        reference.value = route();
    }

    update();
    event.once('routes.loaded', update);

    return reference;
})();

const slots = useSlots();
const appStore = useAppStore();
const globalThis = window as unknown as { frontend_git_commit_hash: string }
const frontend_git_commit_hash = globalThis.frontend_git_commit_hash || '未知';
const backend_git_commit_hash = useProp<string>('git_commit_hash');
</script>

<template>
    <n-config-provider :theme="appStore.themeRef.value" class="h-full">
        <n-message-provider>
            <n-dialog-provider>
                <n-notification-provider>
                    <n-loading-bar-provider>
                        <n-layout class="h-full">
                            <n-layout-header>
                                <slot name="header"/>
                            </n-layout-header>

                            <n-layout has-sider>
                                <n-layout-sider v-if="slots.aside">
                                    <slot name="aside"/>
                                </n-layout-sider>

                                <n-layout-content class="p-10 pb-32">
                                    <slot/>
                                </n-layout-content>
                            </n-layout>

                            <n-layout-footer
                                class="p-2.5 transition-all hover:!bottom-0 lg:!bottom-0 !bottom-[-75px] hover"
                                position="absolute">
                                <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
                                    <n-grid-item class="lg:float-left lg:text-left text-center">
                                        <n-text>&copy; 2022 - {{ new Date().getFullYear() }}</n-text>
                                        <n-divider vertical/>

                                        <n-button href="https://geometrydashchinese.com" tag="a" text>
                                            GDCN
                                        </n-button>

                                        <n-divider vertical/>
                                        <n-button href="https://beian.miit.gov.cn" tag="a" text>
                                            吉ICP备18006293号
                                        </n-button>
                                    </n-grid-item>

                                    <n-grid-item class="lg:float-right lg:text-right text-center">
                                        <n-button href="https://php.net" tag="a" text>
                                            <template #icon>
                                                <n-icon :component="Php"/>
                                            </template>

                                            {{ versions.php }}
                                        </n-button>

                                        <n-divider vertical/>

                                        <n-button href="https://laravel.com" tag="a" text>
                                            <template #icon>
                                                <n-icon :component="Laravel"/>
                                            </template>

                                            {{ versions.laravel }}
                                        </n-button>

                                        <n-divider vertical/>

                                        <n-button
                                            href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese"
                                            tag="a" text>
                                            <template #icon>
                                                <n-icon :component="CommitRound"/>
                                            </template>

                                            <n-grid v-if="frontend_git_commit_hash !== backend_git_commit_hash"
                                                    :cols="1">
                                                <n-grid-item title="前端">
                                                    <n-text type="info">
                                                        {{ frontend_git_commit_hash }}
                                                    </n-text>
                                                </n-grid-item>

                                                <n-grid-item title="后端">
                                                    <n-text type="success">
                                                        {{ backend_git_commit_hash }}
                                                    </n-text>
                                                </n-grid-item>
                                            </n-grid>

                                            <n-text v-else>{{ backend_git_commit_hash }}</n-text>
                                        </n-button>
                                    </n-grid-item>

                                    <n-grid-item v-if="isMobile" class="lg:mx-auto text-center">
                                        <n-button :type="(router.current('gdcs.*') ? 'success' : undefined)"
                                                  href="https://gf.geometrydashchinese.com" tag="a" text>
                                            GDCS
                                        </n-button>

                                        <n-divider vertical/>

                                        <n-button :type="(router.current('gdproxy.*') ? 'success' : undefined)"
                                                  href="https://dl.geometrydashchinese.com" tag="a" text>
                                            GDProxy
                                        </n-button>

                                        <n-divider vertical/>

                                        <n-button :type="(router.current('ngproxy.*') ? 'success'  : undefined)"
                                                  href="https://ng.geometrydashchinese.com" tag="a" text>
                                            NGProxy
                                        </n-button>
                                    </n-grid-item>
                                </n-grid>
                            </n-layout-footer>
                        </n-layout>

                        <BackendMessageReceiver/>
                    </n-loading-bar-provider>
                </n-notification-provider>
            </n-dialog-provider>
        </n-message-provider>
    </n-config-provider>
</template>
