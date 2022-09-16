<script lang="ts" setup>
import {useProp} from "@/scripts/core/utils";
import {Message} from "@/scripts/types/backend";
import {watch} from "vue";
import {useMessage} from "naive-ui";
import {each} from "lodash-es";

(() => {
    const $message = useMessage();
    const items = useProp<Message[]>('messages');

    function process() {
        each(items.value, message => {
            $message.create(message.content, message.options);
        });
    }

    watch(items, process);
    process();
})();
</script>

<template/>
