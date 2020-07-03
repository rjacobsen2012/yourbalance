<template>
    <div>
        <b-row v-for="balance in balances" :key="balance.id">
            <balance-item :balance="balance"/>
        </b-row>
    </div>
</template>

<script>
    import BalanceItem from "./BalanceItem";

    export default {
        name: "BalanceItems",

        components: {
            BalanceItem,
        },

        props: {
            balances: { type: [Array, Object] },
        }
    }
</script>

<style scoped>

</style>
