import {initializeHybridly} from "hybridly/vue";
import "virtual:hybridly/router";

initializeHybridly({
    cleanup: !import.meta.env.DEV,
    pages: import.meta.glob('../views/pages/**/*.vue'),
    progress: {
        includeCSS: true,
        spinner: true
    }
}).then(() => {

});
