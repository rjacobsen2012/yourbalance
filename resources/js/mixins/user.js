export default {
    name: 'UserHelper',
    created() {
        this.$user = this.$page.user
    },
    methods: {
        user() {
            return this.$page.user
        },
    },
}
