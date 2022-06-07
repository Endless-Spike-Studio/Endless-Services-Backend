<script lang="ts" setup>
import {watchEffect} from "vue";
import {each} from "lodash-es";
import {useMessage} from "naive-ui";
import {getProp} from "@/scripts/helpers";
import {Message} from "@/scripts/types/backend";

const messages = getProp<Message[]>('messages');
const $message = useMessage();

watchEffect(() => {
    each(messages.value, (message: Message) => {
        $message.create(message.content, message.options);
    });
});
</script>

<template/>
