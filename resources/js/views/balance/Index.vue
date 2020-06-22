<template>
    <layout title="Your Balances">
        <b-container fluid class="header-balance">
            <b-row class="header-content">
                <div class="left-header-content">
                    Your Balance
                    <b-button size="sm" variant="primary" class="add-entry ml-2 mb-1" @click="openAddModal">
                        <div class="button-content">
                            <icon name="plus" class="icon" width="10" height="10"/>
                            <span class="pl-2">ADD ENTRY</span>
                        </div>
                    </b-button>
                    <b-button size="sm" variant="primary" class="add-entry ml-2 mb-1">
                        <div class="button-content">
                            <icon name="plus" class="icon" width="10" height="10"/>
                            <span class="pl-2">IMPORT CSV</span>
                        </div>
                    </b-button>
                </div>
                <div class="right-header-content">
                    <span class="total-balance-title">TOTAL BALANCE</span>
                    <span class="total-balance">
                        <span class="whole-amount">{{ wholeAmount(formattedAmount(parseFloat(total_balance))) }}</span><span class="cents">.{{ cents(formattedAmount(parseFloat(total_balance))) }}</span>
                    </span>
                </div>
            </b-row>
        </b-container>
        <balance-days :balances="balances" :days="days"/>
        <b-container class="pagination">
            <b-row>
                <pagination :data="balances" :limit="2" @pagination-change-page="getBalances">
                    <span slot="prev-nav">&lt; Previous</span>
                    <span slot="next-nav">Next &gt;</span>
                </pagination>
            </b-row>
        </b-container>
        <b-modal id="balance-modal" header-bg-variant="info" body-bg-variant="info" hide-footer>
            <template v-slot:modal-title size="sm">Add Balance Entry</template>
            <div class="balance-modal-content">
                <b-card-text>
                    <div class="editor pt-4 pb-4">
                        <div class="field-editor">
                            <span class="field-label">LABEL</span>
                            <b-input
                                v-model="newBalanceForm.label"
                                size="sm"
                                class="label-field"
                                type="text"
                                name="balance-label"
                                id="balance-label"
                            />
                        </div>
                        <div class="field-editor">
                            <span class="field-label">DATE</span>
                            <b-input-group size="sm" class="datepicker-date" model="new_balance_date">
                                <datetime
                                    type="datetime"
                                    v-model="newBalanceForm.date"
                                    format="d MMM, y 'at' h:mm a"
                                    input-id="new_balance_date"
                                    :input-class="['form-control', 'form-control-sm', 'date-field']"
                                    use12-hour
                                />
                            </b-input-group>
                        </div>
                        <div class="field-editor">
                            <span class="field-label">AMOUNT</span>
                            <b-input-group prepend="$" class="input-group-merge custom-input-group">
                                <b-form-input
                                    v-model="newBalanceForm.amount"
                                    size="sm"
                                    class="amount-field"
                                    type="text"
                                    name="balance-amount"
                                    id="balance-amount"
                                />
                            </b-input-group>
                        </div>
                    </div>
                    <hr class="m-0">
                    <div class="editor-buttons pt-3 pb-3">
                        <b-button variant="secondary" class="editor-button" @click="closeAddModal">CANCEL</b-button>
                        <b-button variant="primary" class="editor-button ml-3" @click="addBalance">ADD ENTRY</b-button>
                    </div>
                </b-card-text>
            </div>
        </b-modal>
    </layout>
</template>

<script>
    import Layout from "../../components/layout/Layout";
    import User from '@mixins/user';
    import BalanceDays from "./components/BalanceDays";
    import {Errors} from "form-backend-validation";

    export default {
        mixins: [User],

        name: "Index",

        components: {
            Layout,
            BalanceDays,
        },

        data() {
            return {
                balances: {},
                perPageAmount: 10,
                days: {},
                total_balance: 0,
                loadedPage: 1,

                newBalanceForm: {
                    label: null,
                    date: null,
                    amount: null,
                },
            }
        },

        created() {
            this.getBalances()
            this.$bus.$on('updateBalances', this.refreshBalances)
        },

        methods: {
            formattedAmount(amount) {
                return this.currency(amount)
            },

            openAddModal() {
                this.$bvModal.show('balance-modal')
            },

            closeAddModal() {
                this.$bvModal.hide('balance-modal')
            },

            getBalances(page = 1) {
                this.loadedPage = page
                this.$bus.$emit('isloading', true)
                this.axios.get(
                    this.route('api.v1.balance.index', [],
                        'page=' + page + '&perPage=' + this.perPageAmount), {
                        headers: this.tokenHeader()
                    }
                ).then(response => {
                    this.balances = response.data.balances
                    this.days = response.data.days
                    this.total_balance = response.data.total_balance
                    this.$bus.$emit('isloading', false)
                })
            },

            refreshBalances() {
                this.getBalances(this.loadedPage)
            },

            addBalance() {
                const balance = {
                    label: this.newBalanceForm.label,
                    date: this.formatDate(this.newBalanceForm.date, 'YYYY-MM-DD HH:MM:SS'),
                    amount: this.newBalanceForm.amount,
                }
                this.$bus.$emit('isloading', true)

                this.axios
                    .post(
                        this.route('api.v1.balance.store', []),
                        balance,
                        {
                            headers: this.tokenHeader()
                        }
                    )
                    .then(this.addSuccess)
                    .catch(error => this.errors = new Errors(error.response.data.errors))
                    .finally(() => {
                        this.$bus.$emit('isloading', false)
                    })
            },

            addSuccess() {
                this.refreshBalances()
                this.closeAddModal()
                this.$toasted.success("The balance was added successfully")
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import '~sass/helpers';
    .header-balance {
        width: 100%;
        background-color: $dark-blue;
        color: $white;
        flex: 1;
        padding: 1.1rem 10rem 0.8rem 10rem;
        min-width: 50rem;

        .header-content {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;

            .left-header-content {
                .add-entry {
                    font-size: $xs-text;
                }

                .icon {
                    font-size: $xs-text;
                }

                .button-content {
                    display: flex;
                    flex-direction: row;
                    justify-content: space-evenly;
                    align-items: center;
                    font-size: $xs-text;
                }
            }

            .right-header-content {
                display: flex;
                flex-direction: column;
                align-items: flex-end;

                .total-balance-title {
                    font-size: $xs-text;
                    color: lighten($grey, 5%);
                }

                .total-balance {
                    color: $green;
                    font-size: $xl-text;

                    .cents {
                        font-size: $small-text;
                    }
                }
            }
        }
    }

    .pagination {
        width: 100%;
        flex: 1;
        padding: 1.1rem 10rem 0.8rem 10rem;
        min-width: 50rem;
    }

    .balance-modal-content {

    }
</style>
