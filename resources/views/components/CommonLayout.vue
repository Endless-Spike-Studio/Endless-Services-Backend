<script lang="ts" setup>
import {
    darkTheme,
    GlobalTheme,
    lightTheme,
    MenuOption,
    NButton,
    NConfigProvider,
    NDialogProvider,
    NDivider,
    NIcon,
    NLayout,
    NLayoutContent,
    NLayoutFooter,
    NLayoutHeader,
    NLoadingBarProvider,
    NMenu,
    NMessageProvider,
    NNotificationProvider,
    NSpace,
    NThing,
    useOsTheme
} from "naive-ui"
import {ref, watch} from "vue"
import {getProp, isMobile, toRoute, toURL} from "@/scripts/helpers"
import route, {routes} from "@/scripts/route"
import {Message} from "@/scripts/types/backend"
import {each} from "lodash-es"
import {BranchesOutlined} from "@vicons/antd"
import GlobalApiInjector from "@/views/components/GlobalApiInjector.vue"
import {useGlobalStore} from "@/scripts/stores"

const currentRoute = ref()
const props = withDefaults(
    defineProps<{
        theme?: string,
        menu: {
            left: MenuOption[],
            right: MenuOption[],
            mobile: MenuOption[]
        },
        footer?: {
            short: string,
            long: string
        }
    }>(),
    {
        theme: () => useOsTheme()
                ?.value
                ?.toString()
            ?? 'light',
        footer: () => ({
            short: 'GDCN',
            long: 'Geometry Dash Chinese'
        })
    }
)

const versions = getProp('versions', {
    php: 'Unknown',
    laravel: 'Unknown',
    git: 'Unknown'
})

const themeRef = ref<GlobalTheme>(props.theme === 'light' ? lightTheme : darkTheme)

watch(routes, () => {
    currentRoute.value = route().current()
})

watch(getProp<Message[]>('messages', []), messages => {
    const globalStore = useGlobalStore()

    return each(messages, message => {
        return globalStore.$message.create(message.content, message.options)
    })
})
</script>

<template>
    <n-config-provider :theme="themeRef" class="h-full">
        <n-message-provider>
            <n-dialog-provider>
                <n-notification-provider>
                    <n-loading-bar-provider>
                        <global-api-injector/>

                        <n-layout class="h-full">
                            <n-layout-header>
                                <n-space v-if="!isMobile" justify="space-between">
                                    <n-menu v-model:value="currentRoute"
                                            :options="menu.left"
                                            mode="horizontal"
                                            @update:value="toRoute"/>

                                    <n-menu v-model:value="currentRoute"
                                            :options="menu.right"
                                            mode="horizontal"
                                            @update:value="toRoute"/>
                                </n-space>

                                <n-menu v-else
                                        v-model:value="currentRoute"
                                        :default-expanded-keys="[]"
                                        :options="menu.mobile"
                                        @update:value="toRoute"/>
                            </n-layout-header>

                            <n-layout-content class="p-5 pb-32 lg:pb-20">
                                <n-space vertical>
                                    <slot/>
                                </n-space>
                            </n-layout-content>

                            <n-layout-footer class="lg:text-center p-5" position="absolute">
                                <n-space justify="space-between">
                                    <n-thing>
                                        <span>&copy 2022 - {{ new Date().getFullYear() }}</span>
                                        <n-divider vertical/>

                                        <n-button text @click="toRoute('home')">
                                            {{ isMobile ? footer.short : footer.long }}
                                        </n-button>

                                        <n-divider vertical/>
                                        <n-button text @click="toURL('https://beian.miit.gov.cn')">吉ICP备18006293号
                                        </n-button>
                                    </n-thing>

                                    <n-thing>
                                        <span>PHP {{ versions.php }}</span>
                                        <n-divider vertical/>

                                        <span>Laravel {{ versions.laravel }}</span>
                                        <n-divider vertical/>

                                        <span>
                                    <n-icon :component="BranchesOutlined"/> {{ versions.git }}
                                </span>
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
