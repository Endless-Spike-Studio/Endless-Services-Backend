import {defineStore} from "pinia";
import {darkTheme, useOsTheme} from "naive-ui";
import {useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";

export const useAppStore = defineStore('app', {
    state: () => ({
        theme: useOsTheme().value
    }),
    getters: {
        themeRef(store) {
            return store.theme === 'dark' ? darkTheme : null;
        }
    },
    actions: {
        switchTheme() {
            this.theme = (this.theme === 'dark' ? ' light' : 'dark') as (typeof this.theme);
        }
    },
    persist: {
        enabled: true
    }
});

export const useApiStore = defineStore('api', {
    state: () => ({
        $message: useMessage(),
        $dialog: useDialog(),
        $notification: useNotification(),
        $loadingBar: useLoadingBar()
    })
});

export const useBackendStore = defineStore('backend', {
    state: () => ({
        gdcs: {
            account: useProp('gdcs.account') as unknown as App.Models.Account,
            user: useProp('gdcs.user') as unknown as App.Models.User
        }
    })
});
