import {createPinia} from "pinia";
import persist from "pinia-plugin-persist";

export const pinia = createPinia()
    .use(persist);

if (import.meta.env.PROD && location.protocol === 'http:') {
    location.protocol = 'https:';
}
