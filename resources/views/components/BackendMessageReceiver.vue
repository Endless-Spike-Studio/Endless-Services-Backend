<script lang="ts" setup>
import {useProp} from "@/scripts/core/utils";
import {BackendMessage} from "@/types/backend";
import {useApiStore} from "@/scripts/core/stores";

const apiStore = useApiStore();
watch(useProp<BackendMessage[]>('messages', []), newMessages => {
    newMessages.forEach(newMessage => {
        apiStore.$message.create(newMessage.content, newMessage.options);
    });
}, {
    immediate: true,
    deep: true
});
</script>

<template/>
