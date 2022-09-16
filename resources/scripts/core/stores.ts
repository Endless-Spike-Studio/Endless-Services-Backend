import {defineStore} from "pinia";
import {darkTheme, useOsTheme} from "naive-ui";
import {computed} from "vue";
import {useProp} from "@/scripts/core/utils";
import {App} from "@/scripts/types/backend";

export const useAppStore = defineStore('app', {
    state: () => ({
        theme: useOsTheme().value
    }),
    getters: {
        themeRef(store) {
            return computed(() => {
                return store.theme === 'light' ? null : darkTheme;
            })
        }
    },
    actions: {
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
        }
    },
    persist: {
        enabled: true,
        strategies: [
            {
                storage: localStorage,
                paths: ['theme']
            }
        ]
    }
});

export const useGeometryDashChineseServerStore = defineStore('GDCS', {
    state: () => ({
        account: useProp<App.Models.GDCS.Account>('GDCS.account'),
        user: useProp<App.Models.GDCS.User>('GDCS.user'),
    }),
    getters: {
        logged(store) {
            return store.account !== undefined;
        }
    }
});
