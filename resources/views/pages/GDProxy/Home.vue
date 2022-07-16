<script lang="ts" setup>
import { NButton, NDivider, NDropdown, NIcon, NSpace } from 'naive-ui'
import Banner from '@/images/GDProxy/Banner.png'
import { AndroidFilled, AppleFilled, WindowsFilled } from '@vicons/antd'
import { toURL } from '@/scripts/helpers'
import CommonHome from '@/views/components/CommonHome.vue'

const windowsDownloadOptions = [
  {
    label: '无资源包 (exe)',
    key: 'exe'
  },
  {
    label: '带资源包',
    key: 'zip'
  }
]

function handleWindowsDownload (suffix: string) {
  toURL('https://cdn.geometrydashchinese.com/client/GDProxy.' + suffix)
}
</script>

<template layout="GDProxy">
    <common-home :banner="Banner" group-name="GDProxy 讨论群" team-name="GDProxy 团队">
        <n-space class="lg:w-1/3 mx-auto" vertical>
            <n-divider>下载</n-divider>

            <n-space justify="center">
                <n-button @click="toURL('https://cdn.geometrydashchinese.com/client/GDProxy.apk')">
                    <template #icon>
                        <n-icon>
                            <android-filled/>
                        </n-icon>
                    </template>

                    Android
                </n-button>

                <n-dropdown :options="windowsDownloadOptions" trigger="click" @select="handleWindowsDownload">
                    <n-button>
                        <template #icon>
                            <n-icon>
                                <windows-filled/>
                            </n-icon>
                        </template>

                        Windows
                    </n-button>
                </n-dropdown>

                <n-button disabled title="下次一定!"
                          @click="toURL('https://cdn.geometrydashchinese.com/client/GDProxy.ipa')">
                    <template #icon>
                        <n-icon>
                            <apple-filled/>
                        </n-icon>
                    </template>

                    Mac | IOS
                </n-button>
            </n-space>
        </n-space>
    </common-home>
</template>
