<template>
    <layout title="Your Balances">
        <div class="header-balance">
            <b-row class="header-content">
                <b-container>
                    <b-row>
                        <div class="header-row">
                            <div class="left-header-content">
                                <span class="main-text">Your Balance</span>
                                <b-button size="sm" variant="primary" class="add-entry" @click="openAddModal">
                                    <div class="button-content">
                                        <icon name="plus" class="icon" width="10" height="10"/>
                                        <span class="import-text">ADD ENTRY</span>
                                    </div>
                                </b-button>
                                <b-button size="sm" variant="primary" class="add-entry-b">
                                    <div class="button-content">
                                        <img src="/images/upload.png" class="upload-image"/>
                                        <span class="import-text">IMPORT CSV</span>
                                    </div>
                                </b-button>
                            </div>
                            <div class="right-header-content">
                                <span class="total-balance-title">TOTAL BALANCE</span>
                                <span class="total-balance">
                                <span class="whole-amount">{{ wholeAmount(formattedAmount(parseFloat(total_balance))) }}</span><span class="cents">.{{ cents(formattedAmount(parseFloat(total_balance))) }}</span>
                            </span>
                            </div>
                        </div>
                    </b-row>
                </b-container>
            </b-row>
        </div>
        <balance-days :balances="balances" :days="days"/>
        <div class="pagination-holder">
            <pagination :data="balances" :limit="2" @pagination-change-page="getBalances">
                <span slot="prev-nav">PREV</span>
                <span slot="next-nav">NEXT</span>
            </pagination>
        </div>
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
                perPageAmount: 100,
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
        background-color: $dark-blue;
        color: $white;

        flex: 1;
        padding: 1rem 10rem;

        .header-content {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;

            .header-row {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                padding-top: 0.2rem;
                padding-bottom: 0.1rem;

                .left-header-content {
                    display: flex;
                    flex-direction: row;
                    justify-content: center;

                    .main-text {
                        font-size: 12pt;
                    }

                    .add-entry {
                        font-size: $xs-text;
                        margin-left: 0.6rem;
                        padding: 0 0.5rem 0 0.20rem;
                        height: 1.4rem;
                    }

                    .add-entry-b {
                        font-size: $xs-text;
                        margin-left: 0.8rem;
                        padding: 0 0.5rem 0 0.2rem;
                        height: 1.4rem;
                    }

                    .icon {
                        font-size: $xs-text;
                    }

                    .button-content {
                        display: flex;
                        flex-direction: row;
                        justify-content: space-evenly;
                        align-items: center;
                        font-size: 5.5pt;
                        padding-left: 0.1rem;
                        /*padding-top: 1px;*/

                        .upload-image {
                            width: 0.8rem;
                            height: 0.8rem;
                        }

                        .import-text {
                            padding-left: 0.3rem;
                            padding-top: 0.1rem;
                        }
                    }
                }

                .right-header-content {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                    padding-right: 0.8rem;

                    .total-balance-title {
                        font-size: $xs-text;
                        color: lighten($grey, 5%);
                    }

                    .total-balance {
                        line-height: 1.5rem;
                        color: $green;
                        font-size: 15pt;
                        font-weight: 200;

                        .cents {
                            font-size: $small-text;
                        }
                    }
                }
            }
        }
    }

    .pagination-holder {
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        font-size: 7pt !important;
        font-weight: bold;
    }

    .balance-modal-content {

    }
</style>
