import {defineStore} from "pinia";
import {darkTheme, lightTheme, useOsTheme} from "naive-ui";
import {useProp} from "@/scripts/core/utils";
import {App} from "@/types/backend";

export const useAppStore = defineStore('app', {
    state: () => ({
        theme: useOsTheme().value
    }),
    getters: {
        themeRef() {
            switch (this.theme) {
                case 'light':
                    return lightTheme;
                case 'dark':
                    return darkTheme;
                default:
                    return null;
            }
        }
    },
    actions: {
        switchTheme() {
            switch (this.theme) {
                case 'light':
                    this.theme = 'dark';
                    break;
                case 'dark':
                default:
                    this.theme = 'light';
                    break;
            }
        }
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
            account: useProp<App.Models.Account>('gdcs.account').value,
            user: useProp<App.Models.User>('gdcs.user').value
        }
    })
});
