<template>
    <div class="balance-info">
        <b-row v-for="day in days" :key="day" class="days pb-4">
            <b-container>
                <b-row>
                    <div class="day-row">
                        <span v-if="dayIsToday(day)" class="date-header">TODAY</span>
                        <span v-if="dayIsYesterday(day)" class="date-header">YESTERDAY</span>
                        <span v-if="dayIsNotTodayOrYesterday(day)" class="date-header">
                        {{ $moment(day).format('ddd, D MMM').toUpperCase() }}
                    </span>
                        <div :class="['sum', isTodayOrPositive(day) ? 'today' : 'not-today']">
                            <span v-if="!isNegative(sumToday(day))">+</span><span class="whole-amount">{{ wholeAmount(formattedSum(day)) }}</span><span class="cents">.{{ cents(formattedSum(day)) }}</span>
                        </div>
                    </div>
                </b-row>
                <balance-items :balances="getDayBalances(day)"/>
            </b-container>
        </b-row>
    </div>
</template>

<script>
    import BalanceItems from "./BalanceItems";

    export default {
        name: "BalanceDays",

        components: {
            BalanceItems,
        },

        props: {
            balances: { type: [Array, Object] },
            days: { type: [Array, Object] },
        },

        data() {
            return {
                dayBalances: [],
                hoveredBalance: null,
            }
        },

        methods: {
            getDayBalances(day) {
                return this.balances.data.filter(balance => {
                    const balanceDate = this.$moment(balance.date).format('YYYY-MM-DD')
                    return this.$moment(balanceDate).isSame(day)
                })
            },
            sumToday(day) {
                let total = 0

                Object.entries(this.getDayBalances(day)).forEach(([key, val]) => {
                    total += parseFloat(val.amount);
                })

                return total
            },
            formattedSum(day) {
                return this.currency(this.sumToday(day))
            },
            isNegative(amount) {
                return amount < 0
            },
            isTodayOrPositive(day) {
                return this.dayIsToday(day) || this.sumToday(day) >= 0
            },
            dayIsToday(day) {
                return this.isToday(day)
            },
            dayIsYesterday(day) {
                return this.isYesterday(day)
            },
            dayIsNotTodayOrYesterday(day) {
                return !this.dayIsToday(day) && !this.dayIsYesterday(day)
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import '~sass/helpers';
    .balance-info {
        flex: 1;
        padding-left: 10rem;
        padding-right: 10rem;
        padding-top: 2rem;

        .days {
            font-size: $normal-text;
            font-weight: bold;
            color: lighten($grey, 5%);

            .day-row {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                width: 100%;
                padding-bottom: 0.6rem;

                .date-header {
                    font-size: $xs-text;
                }
            }

            .today {
                color: $green;
                font-weight: 400;
            }

            .not-today {
                color: $light-grey;
                font-weight: 400;
            }
        }
    }
</style>
