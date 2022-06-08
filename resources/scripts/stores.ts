import {defineStore} from "pinia";
import {useDialog, useLoadingBar, useMessage, useNotification} from "naive-ui";

export const useGlobalStore = defineStore('global', {
    state: () => ({
        $message: useMessage(),
        $dialog: useDialog(),
        $notification: useNotification(),
        $loadingBar: useLoadingBar()
    })
});
