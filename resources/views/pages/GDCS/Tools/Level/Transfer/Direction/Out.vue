<script lang="ts" setup>
import {useProp} from "@/scripts/core/utils";
import {App} from "@/scripts/types/backend";

const links = useProp<App.Models.GDCS.AccountLink[]>('links');
</script>

<template layout="GDCS">
    <n-card title="关卡转出">

    </n-card>
</template>
