<script lang="ts" setup>
import {useGlobalStore} from '@/scripts/stores'
import {useDialog, useLoadingBar, useMessage, useNotification} from 'naive-ui'

const globalStore = useGlobalStore()
globalStore.$message = useMessage()
globalStore.$dialog = useDialog()
globalStore.$notification = useNotification()
globalStore.$loadingBar = useLoadingBar()
</script>

<template/>
