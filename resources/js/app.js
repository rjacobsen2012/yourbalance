import bootstrapper             from './bootstrap'
import { InertiaApp }           from '@inertiajs/inertia-vue'
import Vue                      from 'vue'
import BootstrapVue             from 'bootstrap-vue'
import VModal                   from 'vue-js-modal'
import moment                   from 'moment-timezone'
import VueMoment                from 'vue-moment'
import VueNumerals              from 'vue-numerals'
import VueAxios                 from 'vue-axios'
import Bus                      from '@plugins/Bus'
import VueToasted               from 'vue-toasted'
import VeeValidate              from 'vee-validate'
import VeeValidateLaravel       from '@pmochine/vee-validate-laravel'
import VuejsDialog              from 'vuejs-dialog'
import 'vuejs-dialog/dist/vuejs-dialog.min.css'
import VueBootstrapTypeahead    from 'vue-bootstrap-typeahead'
import VueCookie                from 'vue-cookie'
import { Datetime }             from 'vue-datetime'
import 'vue-datetime/dist/vue-datetime.css'

import '@util/filters'
import '@vendor/vue-awesome'
import '@vendor/vue-fontawesome'

Vue.use(InertiaApp)
Vue.use(Bus)
Vue.use(BootstrapVue)
Vue.use(VueAxios, bootstrapper.axios)
Vue.use(VModal, { componentName: 'v-modal' })
Vue.use(VueMoment, { moment })
Vue.use(VueNumerals)
Vue.use(VuejsDialog)
Vue.use(VueCookie)
Vue.component('vue-bootstrap-typeahead', VueBootstrapTypeahead)
Vue.component('pagination', require('laravel-vue-pagination'))
Vue.component('datetime', Datetime)

import FormHelper from '@mixins/formHelper'
Vue.mixin(FormHelper)

import '@vendor/passport'

Vue.use(VeeValidate, {
    inject: true,
    fieldsBagName: 'veeFields',
    errorBagName: 'veeErrors'
})
Vue.use(VeeValidateLaravel)

import CommonMixins from '@mixins/common'
Vue.mixin(CommonMixins)

Vue.use(VueToasted, {
    theme: 'budget',
    position: 'bottom-right',
    duration: 3000
})

require('@components')

const app = document.getElementById('app')

new Vue({
    render: h => h(InertiaApp, {
        props: {
            initialPage: JSON.parse(app.dataset.page),

            resolveComponent: (name) => {
                name = name[0].toLowerCase() + name.slice(1)
                return import(`@views/${name}`).then(module => module.default)
            },
        },
    }),
}).$mount(app)
