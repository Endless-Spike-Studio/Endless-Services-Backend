<script lang="ts" setup>
import {NAlert} from "naive-ui";</script>

<template>
    <n-alert :show-icon="false" closable title="Code Time">
        <img
            :src=" 'https://img.shields.io/endpoint?url=' + encodeURIComponent('https://codetime-api.datreks.com/badge/2270?project=GDCN&showProject=true') + '&style=for-the-badge' "
            alt="Code Time"/>
    </n-alert>
</template>
