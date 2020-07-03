<template>
    <b-card no-body class="balance-card mb-2">
        <b-card-text class="p-0 mb-0">
            <div class="balance-holder" @mouseover="hover" @mouseleave="unHover">
                <div class="balance-label-info">
                    <div class="balance-label">{{ balance.label.name }}</div>
                    <div class="balance-date">{{ balanceDate }}</div>
                </div>
                <div class="balance-amount">
                    <div class="pr-1">
                        <b-link v-if="hovered || editing" class="balance-link pr-3" @click="editBalance">EDIT</b-link>
                        <b-link v-if="hovered || editing" class="balance-link" @click="maybeDelete">DELETE</b-link>
                    </div>
                    <div :class="['pr-2', !isPositive(parseInt(balance.amount)) ? 'negative-balance' : 'positive-balance']">
                        <span v-if="isPositive(parseInt(balance.amount))">+</span><span class="whole-amount">{{ wholeAmount(formattedAmount(parseFloat(balance.amount))) }}</span><span class="cents">.{{ cents(formattedAmount(parseFloat(balance.amount))) }}</span>
                    </div>
                </div>
            </div>
        </b-card-text>
        <b-card-text v-if="editing">
            <hr class="m-0">
            <div class="editor pt-4 pb-4">
                <div class="field-editor">
                    <span class="field-label">LABEL</span>
                    <b-input
                        v-model="balanceForm.label"
                        size="sm"
                        class="label-field"
                        type="text"
                        name="balance-label"
                        id="balance-label"
                    />
                </div>
                <div class="field-editor">
                    <span class="field-label">DATE</span>
                    <b-input-group size="sm" class="datepicker-date" :model="`balance_date_` + balance.id">
                        <datetime
                            type="datetime"
                            v-model="balanceForm.date"
                            format="d MMM, y 'at' h:mm a"
                            :input-id="`balance_date_` + balance.id"
                            :input-class="['form-control', 'form-control-sm', 'date-field']"
                            use12-hour
                        />
                    </b-input-group>
                </div>
                <div class="field-editor">
                    <span class="field-label">AMOUNT</span>
<!--                    <b-form-group>-->
                        <b-input-group prepend="$" class="input-group-merge custom-input-group">
                            <b-form-input
                                v-model="balanceForm.amount"
                                size="sm"
                                class="amount-field"
                                type="text"
                                name="balance-amount"
                                id="balance-amount"
                            />
                        </b-input-group>
<!--                    </b-form-group>-->
                </div>
            </div>
            <hr class="m-0">
            <div class="editor-buttons pt-3 pb-3">
                <b-button variant="secondary" class="editor-button" @click="cancelEditing">CANCEL</b-button>
                <b-button variant="primary" class="editor-button ml-3" @click="updateBalance">UPDATE ENTRY</b-button>
            </div>
        </b-card-text>
    </b-card>
</template>

<script>
    import {Errors} from "form-backend-validation";

    export default {
        name: "BalanceItem",

        props: {
            balance: { type: [Object, Array] },
        },

        data() {
            return {
                editing: false,

                balanceForm: {
                    id: this.balance.id,
                    label: this.balance.label.name,
                    date: this.balance.date,
                    amount: this.balance.amount,
                },

                hovered: false,
            }
        },

        methods: {
            formattedAmount(amount) {
                return this.currency(amount)
            },
            isPositive(amount) {
                return amount >= 0
            },
            hover() {
                this.hovered = true
            },
            unHover() {
                this.hovered = false
            },
            editBalance() {
                this.editing = true
            },
            cancelEditing() {
                this.editing = false
                this.hovered = false
            },
            delete() {
                this.deleteBalance()
            },
            deleteBalance() {
                this.$bus.$emit('isloading', true)
                this.axios
                    .delete(
                        this.route(
                            'api.v1.balance.destroy',
                            [this.balanceForm.id]
                        ),
                        {
                            headers: this.tokenHeader()
                        }
                    )
                    .then(this.deleteBalanceSuccess)
                    .catch(error => this.errors = new Errors(error.response.data.errors))
                    .finally(() => {
                        this.$bus.$emit('isloading', false)
                    })
            },

            deleteBalanceSuccess() {
                this.updateBalances()
                this.$toasted.success("The balance was deleted successfully")
            },

            updateBalances() {
                this.$bus.$emit('updateBalances')
            },
            updateBalance() {
                const balance = {
                    id: this.balanceForm.id,
                    label: this.balanceForm.label,
                    amount: this.balanceForm.amount,
                    date: this.formatDate(this.balanceForm.date, 'YYYY-MM-DD HH:MM:SS'),
                }

                this.$bus.$emit('isloading', true)
                this.axios
                    .put(
                        this.route(
                            'api.v1.balance.update',
                            [this.balanceForm.id]
                        ),
                        balance,
                        {
                            headers: this.tokenHeader()
                        }
                    )
                    .then(this.updateBalanceSuccess)
                    .catch(error => this.errors = new Errors(error.response.data.errors))
                    .finally(() => {
                        this.$bus.$emit('isloading', false)
                    })
            },

            updateBalanceSuccess() {
                this.updateBalances()
                this.$toasted.success("The balance was updated successfully")
            },
        },

        computed: {
            balanceDate() {
                return this.$moment(this.balance.date).format('D MMM, YYYY * h:mm A').replace('*', 'at')
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import '~sass/helpers';
    .balance-card {
        width: 100%;
        box-shadow: 0 2px 1px rgba(0, 0, 0, 0.02);
        cursor: pointer;

        .balance-holder {
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            height: 2.4rem;

            .balance-label-info {
                display: flex;
                flex-direction: column;
                line-height: 1rem;
                padding-left: 0.7rem;

                .balance-label {
                    color: $black;
                    font-weight: 500;
                }

                .balance-date {
                    color: lighten($grey, 20%);
                    font-size: $xs-text;
                    line-height: 0.6rem;
                }
            }

            .balance-amount {
                width: 11rem;
                padding-right: 0.28rem;

                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;

                .negative-balance {
                    color: $black;
                    font-weight: 400;
                }

                .positive-balance {
                    color: $green;
                    font-weight: 400;
                }

                .balance-link {
                    font-size: $xs-text;
                    text-decoration: underline;
                }
            }
        }
    }
</style>
