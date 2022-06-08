<script lang="ts" setup>
import Banner from "@/images/GDCN/Banner.png";
import {NButton, NCard, NGrid, NGridItem, NText} from "naive-ui";
import {toRoute} from "@/scripts/helpers";
import CommonHome from "@/views/components/CommonHome.vue";</script>

<template layout="GDCN">
    <common-home :banner="Banner" group-name="GDCN 讨论群" team-name="GDCN 团队">
        <n-grid :x-gap="10" :y-gap="10" cols="1 768:2">
            <n-grid-item>
                <n-card title="Geometry Dash Chinese (GDCN)">
                    <template #header-extra>
                        <n-button text type="primary" @click="toRoute('home')">查看</n-button>
                    </template>

                    <n-text>GDCN 提供了 GDProxy, NGProxy, GDCS 并提供了账号服务</n-text>
                    <br>
                    <n-text type="warning">说明: 这些服务并非官方提供, 请以官方为准</n-text>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="Geometry Dash Chinese Server (GDCS)">
                    <template #header-extra>
                        <n-button text type="primary" @click="toRoute('gdcs.home')">查看</n-button>
                    </template>

                    <n-text>GDCS 提供了 Geometry Dash 私服</n-text>
                    <br>
                    <n-text type="warning">说明: GDCS属私服, 不可代替官服</n-text>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="Geometry Dash Proxy (GDProxy)">
                    <template #header-extra>
                        <n-button text type="primary" @click="toRoute('gdproxy.home')">查看</n-button>
                    </template>

                    <n-text>GDProxy 提供了 Geometry Dash 加速服务</n-text>
                    <br>
                    <n-text type="warning">说明: GDProxy属第三方加速服务, 如果您的网络没有问题那么建议您直连</n-text>
                </n-card>
            </n-grid-item>
            <n-grid-item>
                <n-card title="Newgrounds Proxy (NGProxy)">
                    <template #header-extra>
                        <n-button text type="primary" @click="toRoute('ngproxy.home')">查看</n-button>
                    </template>

                    <n-text>NGProxy 提供了 Newgrounds 加速下载服务</n-text>
                    <br>
                    <n-text type="warning">说明: NGProxy属第三方加速服务, 如果您的网络没有问题那么建议您直连</n-text>
                </n-card>
            </n-grid-item>
        </n-grid>
    </common-home>
</template>
