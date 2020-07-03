import Vue from "vue";

export default {
    name: 'FormHelper',
    methods: {
        maybeDelete(...args) {
            this.$toasted.show('Are you sure you want to delete?', {
                duration: null,
                action: [{
                    text: 'No',
                    href: 'javascript:void(0)',
                    onClick: (e, toastObject) => toastObject.goAway(0),
                }, {
                    text: 'Yes',
                    href: 'javascript:void(0)',
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0)
                        this.delete(...args)
                    },
                    class: 'btn-danger'
                }],
            })
        },

        maybeDeleteAdjustedBudgetedAmounts(...args) {
            this.$toasted.show('Delete adjusted amounts as well?', {
                duration: null,
                action: [{
                    text: 'No',
                    href: 'javascript:void(0)',
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0)
                        this.deleteAddition(...args, false)
                    },
                }, {
                    text: 'Yes',
                    href: 'javascript:void(0)',
                    onClick: (e, toastObject) => {
                        toastObject.goAway(0)
                        this.deleteAddition(...args, true)
                    },
                    class: 'btn-danger'
                }],
            })
        },

        delete() {
            this.undefinedMethodWarning('delete')
        },

        deleteAddition() {
            this.undefinedMethodWarning('deleteAddition')
        },

        undefinedAttrWarning(state) {
            Vue.util.warn(`Component [${this.$options.name}] must define the state [${state}].`, this)
        },

        undefinedMethodWarning(method) {
            Vue.util.warn(`Component [${this.$options.name}] must define the method [${method}].`, this)
        },
    },
}
