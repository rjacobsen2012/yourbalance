import Vue from "vue";

Vue.component(
    'passport-clients',
    require('../../components/passport/Clients').default
);

Vue.component(
    'passport-authorized-clients',
    require('../../components/passport/AuthorizedClients').default
);

Vue.component(
    'passport-personal-access-tokens',
    require('../../components/passport/PersonalAccessTokens').default
);
