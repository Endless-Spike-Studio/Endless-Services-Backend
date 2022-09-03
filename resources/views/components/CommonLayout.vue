<script lang="ts" setup>
import {isMobile, useProp} from "@/scripts/core/utils";
import {Laravel, Php} from "@vicons/fa";
import {useAppStore} from "@/scripts/core/stores";
import {CommitRound} from "@vicons/material";
import {useSlots} from "vue";

const versions = useProp<{
    php: string;
    laravel: string;
}>('versions');

const git_commit_hash = window.git_commit_hash;
const slots = useSlots();
const appStore = useAppStore();
</script>

<template>
    <n-config-provider :theme="appStore.theme" class="h-full">
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

                            <n-layout-footer class="p-2.5" position="absolute">
                                <n-space :justify="(isMobile ? 'center' : 'space-between')"
                                         :size="(isMobile ? 'small' : 'medium')"
                                         :vertical="isMobile">
                                    <n-thing class="leading-loose mx-auto text-center">
                                        <n-text>&copy; 2022 - {{ new Date().getFullYear() }}</n-text>
                                        <n-divider vertical/>

                                        <n-button href="https://geometrydashchinese.com" tag="a" text>
                                            GDCN
                                        </n-button>

                                        <n-divider vertical/>
                                        <n-button href="https://beian.miit.gov.cn" tag="a" text>
                                            吉ICP备18006293号
                                        </n-button>
                                    </n-thing>

                                    <n-thing class="mx-auto text-center">
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

                                            {{ git_commit_hash }}
                                        </n-button>
                                    </n-thing>
                                </n-space>
                            </n-layout-footer>
                        </n-layout>
                    </n-loading-bar-provider>
                </n-notification-provider>
            </n-dialog-provider>
        </n-message-provider>
    </n-config-provider>
</template>
