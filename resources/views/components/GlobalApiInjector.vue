<script lang="ts" setup>
import {useApiStore} from "@/scripts/core/stores";

const apiStore = useApiStore();
apiStore.$message = useMessage();
apiStore.$dialog = useDialog();
apiStore.$notification = useNotification();
apiStore.$loadingBar = useLoadingBar();
</script>

<template/>
