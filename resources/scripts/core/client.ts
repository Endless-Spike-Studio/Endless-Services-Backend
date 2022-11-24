import {createPinia} from "pinia";
import {Product} from "@/scripts/types/product";

export const pinia = createPinia();

export const products = [
    {
        name: 'GDCS',
        route: 'gdcs.home'
    },
    {
        name: 'GDProxy',
        route: 'gdproxy.home'
    },
    {
        name: 'NGProxy',
        route: 'ngproxy.home'
    }
] as Product[];
