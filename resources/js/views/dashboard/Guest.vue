<template>
    <guest-layout title="Welcome">
        <faq/>
    </guest-layout>
</template>

<script>
    import User from '@mixins/user'
    import GuestLayout from "../../components/layout/GuestLayout";
    import Faq from "../../components/Faq";

    export default {
        components: {GuestLayout, Faq},
        mixins: [User],

        data() {
            return {
                actionItems: [],
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>
