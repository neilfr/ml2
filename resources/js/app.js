import { InertiaApp } from "@inertiajs/inertia-vue";
import Vue from "vue";


const app = document.getElementById("app");

Vue.prototype.$route = (...args) => route(...args);

Vue.use(InertiaApp);

new Vue({
    metaInfo: {
        titleTemplate: title => (title ? `${title} - ML2` : "ML2")
    },
    render: h =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: name => require(`./Pages/${name}`).default
            }
        })
}).$mount(app);
