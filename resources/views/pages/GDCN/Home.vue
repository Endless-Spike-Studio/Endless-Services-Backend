<script setup lang="ts">
import Banner from "@/images/GDCN/Banner.png";
import {NButton, NCard, NGrid, NGridItem, NSpace, NText} from "naive-ui";
import HomeButtons from "@/views/components/HomeButtons.vue";
import CodeTime from "@/views/components/CodeTime.vue";
import TeamMembers from "@/views/components/TeamMembers.vue";
import {toRoute} from "@/scripts/helpers";</script>

<template layout="GDCN">
    <n-space vertical>
        <div class="mx-auto lg:w-1/2">
            <img :src="Banner" alt="Banner">
        </div>

        <div class="mx-auto py-5">
            <home-buttons join-group-button-text="加入 GDCN 讨论群"/>
        </div>

        <div class="text-center lg:w-1/5 mx-auto pt-5">
            <code-time/>
        </div>

        <div class="lg:w-2/3 mx-auto">
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
        </div>

        <div class="lg:w-2/3 py-5 mx-auto">
            <team-members title="GDCS 团队"/>
        </div>
    </n-space>
</template>
