<template>
    <b-container class="faq">
        <b-row class="mt-4">
            Welcome to YourBalance! Feel free to login and handle your balances, or register and begin adding your balances.
        </b-row>
    </b-container>
</template>

<script>
    import User from '@mixins/user'

    export default {
        mixins: [User],
        name: "Faq",
    }
</script>

<style lang="scss" scoped>
    @import '~sass/helpers';
    .faq {

    }
</style>
