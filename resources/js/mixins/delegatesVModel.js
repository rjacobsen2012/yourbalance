
const DelegatesVModel = {
    props: {
        value: {},
    },
    
    watch: {
        value(val) {
            this.innerValue = val
        },

        innerValue: _.debounce(function (val) {
            return this.$emit('input', val)
        }, 250),
    },

    data() {
        return {
            innerValue: null,
        }
    },

    created() {
        this.innerValue = this.value
    },
}

export default DelegatesVModel