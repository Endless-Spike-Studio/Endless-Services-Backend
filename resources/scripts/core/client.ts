import {createPinia} from "pinia";

export const pages = import.meta.glob('../views/pages/**/*.vue');
export const pinia = createPinia();
