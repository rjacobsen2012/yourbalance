import { library }              from '@fortawesome/fontawesome-svg-core'
import {
    faDollarSign,
    faFile,
    faCalculator,
    faCopy,
    faCoins,
    faSquare,
    faLayerGroup,
    faPlus,
    faCircle,
    faPen,
    faEye,
    faTimes,
    faSave,
    faQuestionCircle,
}     from '@fortawesome/free-solid-svg-icons'
import {FontAwesomeIcon, FontAwesomeLayers, FontAwesomeLayersText} from '@fortawesome/vue-fontawesome'
import Vue from "vue";

library.add(
    faDollarSign,
    faFile,
    faCalculator,
    faCopy,
    faCoins,
    faSquare,
    faLayerGroup,
    faPlus,
    faCircle,
    faPen,
    faEye,
    faTimes,
    faSave,
    faQuestionCircle,
)

Vue.component('font-awesome-icon', FontAwesomeIcon)
Vue.component('font-awesome-layers', FontAwesomeLayers)
Vue.component('font-awesome-layers-text', FontAwesomeLayersText)
