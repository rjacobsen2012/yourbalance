<template>
    <layout title="Admin">
        <passport-clients/>
        <passport-authorized-clients/>
        <passport-personal-access-tokens/>
    </layout>
</template>

<script>
    import Layout from "../../components/layout/Layout";

    export default {
        name: "Passport",

        components: {
            Layout,
        }
    }
</script>

<style scoped>

</style>
