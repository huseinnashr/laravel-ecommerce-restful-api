require('./bootstrap');

window.Vue = require('vue');

Vue.component(
    "passport-personal-access-tokens",
    require("./components/passport/PersonalAccessTokens").default
);

Vue.component(
    "passport-clients",
    require("./components/passport/Clients").default
);

Vue.component(
    "passport-authorized-clients",
    require("./components/passport/AuthorizedClients").default
);

const app = new Vue({
    el: "#app",
});
