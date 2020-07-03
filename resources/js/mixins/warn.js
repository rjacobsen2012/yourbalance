import Vue from 'vue'

const warn = {
    methods: {
        undefinedAttrWarning(state) {
            Vue.util.warn(`Component [${this.$options.name}] must define the state [${state}].`, this)
        },

        undefinedMethodWarning(method) {
            Vue.util.warn(`Component [${this.$options.name}] must define the method [${method}].`, this)
        },
    }
}

export default warn