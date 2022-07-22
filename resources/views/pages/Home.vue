<script lang="ts" setup>
import { computed, h } from 'vue'
import {
  createDiscreteApi,
  darkTheme,
  MenuOption,
  NAlert,
  NButton,
  NCard,
  NConfigProvider,
  NDivider,
  NGrid,
  NGridItem,
  NImage,
  NLayout,
  NLayoutContent,
  NLayoutFooter,
  NLayoutHeader,
  NMenu,
  NSpace,
  NText,
  NThing,
  useOsTheme
} from 'naive-ui'
import Logo from '@/images/GDCN/logo.png'
import Banner from '@/images/GDCN/banner.png'
import _569801410 from '@/images/GDCN/569801410.png'
import _302185016 from '@/images/GDCN/302185016.png'
import { isMobile } from '@/scripts/helpers'

const osTheme = useOsTheme().value
const theme = computed(() => {
  return osTheme === 'light' ? null : darkTheme
})

const menuOptions = [
  {
    label: '几何冲刺玩家网',
    key: 'home',
    icon: () => h(NImage, {
      src: Logo,
      width: 20
    }),
    url: '/'
  }
] as MenuOption[]

function handleMenuClick (label: string, item: MenuOption) {
  location.href = item.url as string
}

function showJoinGroupDialog (id: number, key: string) {
  const instance = createDiscreteApi(['dialog'], {
    configProviderProps: {
      theme: theme.value
    }
  })

  function destroy () {
    setTimeout(() => {
      instance.unmount()
    }, 1000)
  }

  instance.dialog.create({
    showIcon: false,
    title: '群号: ' + id,
    content: () => h(NImage, {
      src: {
        569801410: _569801410,
        302185016: _302185016
      }[id],
      imgProps: {
        class: 'w-full'
      }
    }),
    onClose: destroy,
    onMaskClick: destroy,
    closeOnEsc: false,
    action: () => h(NButton, {
      tag: 'a',
      href: 'https://qm.qq.com/cgi-bin/qm/qr?k=' + key
    }, () => '点我进群')
  })
}
</script>

<template>
    <n-config-provider :theme="theme" class="h-full">
        <n-layout class="h-full">
            <n-layout-header>
                <n-menu :options="menuOptions" mode="horizontal" @update:value="handleMenuClick"/>
            </n-layout-header>

            <n-layout-content class="p-[24px] pb-20">
                <div class="mx-auto w-full mb-2.5 text-center">
                    <n-image :img-props="{ class: 'w-full' }" :src="Banner" class="lg:w-1/2"/>
                </div>

                <n-alert class="lg:w-1/3 mb-5 mx-auto" type="info">
                    如你所见, 欢迎来到崭新的
                    <n-button href="/" tag="a" text type="primary">几何冲刺玩家网</n-button>
                    !
                </n-alert>

                <div class="lg:w-1/2 mx-auto">
                    <n-divider>相关链接</n-divider>

                    <n-space vertical>
                        <n-card>
                            <n-space :justify="(isMobile ? 'space-evenly' : 'center')" class="mx-auto text-center">
                                <n-button class="w-[8em]" href="https://robtopgames.com" tag="a">Robtop Games</n-button>
                                <n-button class="w-[8em]" href="https://pointercrate.com" tag="a">PointerCrate
                                </n-button>
                                <n-button class="w-[8em]" href="https://newgrounds.com" tag="a">Newgrounds</n-button>

                                <n-button class="w-[8em]"
                                          href="https://www.boomlings.com/database/accounts/accountManagement.php"
                                          tag="a">
                                    官服账号管理
                                </n-button>

                                <n-divider v-if="!isMobile" vertical/>

                                <n-button href="https://nsc.geometrydashchinese.com" tag="a">
                                    Newgrounds Song Collection
                                </n-button>
                            </n-space>
                        </n-card>

                        <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
                            <n-grid-item>
                                <n-card>
                                    <template #header>
                                        <n-thing>
                                            <template #header>
                                                Geometry Dash Chinese
                                            </template>

                                            <template #description>
                                                <n-text>GDCN</n-text>
                                            </template>
                                        </n-thing>
                                    </template>

                                    <template #header-extra>
                                        <n-button
                                            href="https://qm.qq.com/cgi-bin/qm/qr?k=l5AT2gbxhx1uXDV8u1HC_XT9LCIGseLz"
                                            tag="a"
                                            text type="primary">
                                            交流群
                                        </n-button>
                                    </template>

                                    <n-text type="info">GDCN 是对 GDProxy, NGProxy, GDCS 的统称</n-text>
                                    <br/><br/>
                                </n-card>
                            </n-grid-item>
                            <n-grid-item>
                                <n-card>
                                    <template #header>
                                        <n-thing>
                                            <template #header>
                                                Geometry Dash Chinese Server
                                            </template>

                                            <template #description>
                                                <n-text>GDCS</n-text>
                                            </template>
                                        </n-thing>
                                    </template>

                                    <template #header-extra>
                                        <n-button href="https://gf.geometrydashchinese.com" tag="a" text type="primary">
                                            查看
                                        </n-button>
                                    </template>

                                    <n-text type="info">
                                        GDCS 是一个由
                                        <n-button href="https://github.com/WOSHIZHAZHA120" tag="a" text type="primary">
                                            渣渣120
                                        </n-button>
                                        独立开发的私服

                                        <br>

                                        仓库地址:
                                        <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese"
                                                  tag="a" text
                                                  type="primary">
                                            Github
                                        </n-button>
                                    </n-text>
                                </n-card>
                            </n-grid-item>
                            <n-grid-item>
                                <n-card>
                                    <template #header>
                                        <n-thing>
                                            <template #header>
                                                Geometry Dash Proxy
                                            </template>

                                            <template #description>
                                                <n-text>GDProxy</n-text>
                                            </template>
                                        </n-thing>
                                    </template>

                                    <template #header-extra>
                                        <n-button href="https://dl.geometrydashchinese.com" tag="a" text type="primary">
                                            查看
                                        </n-button>
                                    </template>

                                    <n-text type="info">
                                        GDProxy 是一个加速器, 可以加速
                                        <n-text depth="3">(或减速?)</n-text>
                                        您链接服务器的速度
                                    </n-text>
                                </n-card>
                            </n-grid-item>
                            <n-grid-item>
                                <n-card>
                                    <template #header>
                                        <n-thing>
                                            <template #header>
                                                Newgrounds Proxy
                                            </template>

                                            <template #description>
                                                <n-text>NGProxy</n-text>
                                            </template>
                                        </n-thing>
                                    </template>

                                    <template #header-extra>
                                        <n-button href="https://ng.geometrydashchinese.com" tag="a" text type="primary">
                                            查看
                                        </n-button>
                                    </template>

                                    <n-text type="info">
                                        NGProxy 也是一个加速器, 可以加速
                                        <n-text depth="3">(或减速?)</n-text>
                                        您下载歌曲的速度
                                    </n-text>
                                </n-card>
                            </n-grid-item>
                        </n-grid>
                    </n-space>

                    <n-divider>社区</n-divider>

                    <n-card>
                        <n-space :vertical="isMobile" justify="space-evenly">
                            <n-space :vertical="isMobile" class="items-center">
                                <n-text depth="3">百度贴吧</n-text>
                                <n-button href="https://ng.geometrydashchinese.com" tag="a">几何冲刺吧</n-button>
                            </n-space>

                            <n-divider v-if="isMobile"/>

                            <n-space :vertical="isMobile" class="items-center">
                                <n-text depth="3">QQ群</n-text>
                                <n-button @click="showJoinGroupDialog(569801410, 'gmeT9wov61xLRlGiQZZzXc-IxioxBbHG')">
                                    几何冲刺萌新聚集地
                                </n-button>
                                <n-button @click="showJoinGroupDialog(302185016, 'WY64FZrDI0wSslR7pegIJuMhpe-qKO7w')">
                                    几何冲刺爱好者联盟
                                </n-button>
                            </n-space>
                        </n-space>
                    </n-card>

                    <n-divider>开源项目</n-divider>

                    <n-card>
                        <n-space justify="center">
                            <n-button href="https://github.com/Geometry-Dash-Chinese/Geometry-Dash-Chinese" tag="a">
                                Geometry Dash Chinese
                            </n-button>

                            <n-button href="https://github.com/WEGFan/Geometry-Dash-Savefile-Fix" tag="a">
                                存档修复器
                            </n-button>
                        </n-space>
                    </n-card>
                </div>
            </n-layout-content>

            <n-layout-footer class="text-center p-2.5 lg:p-5" position="absolute">
                <n-text>&copy; 2022 - {{ new Date().getFullYear() }}</n-text>
                <n-divider vertical/>
                <n-button href="/" tag="a" text>几何冲刺玩家网</n-button>
                <n-divider vertical/>
                <n-button href="https://beian.miit.gov.cn" tag="a" text>吉ICP备18006293号</n-button>
            </n-layout-footer>
        </n-layout>
    </n-config-provider>
</template>
