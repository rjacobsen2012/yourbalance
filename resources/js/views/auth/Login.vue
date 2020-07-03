<template>
    <guest-layout title="Login">
        <div class="guest-container">
            <b-card class="shadow-sm m-4 login-form">
                <b-form @submit.prevent="login">
                    <b-form-group model="email" :errors="errors">
                        <b-form-input class="custom-input" v-model.trim="form.email" type="text" placeholder="Email Address" />
                    </b-form-group>

                    <b-form-group model="password" :errors="errors">
                        <b-form-input class="custom-input" v-model="form.password" type="password" placeholder="Password" />
                    </b-form-group>

                    <div class="login-actions-holder">
                        <div class="login-actions">
                            <b-checkbox class="custom-input" v-model="form.remember" :unchecked-value="null">Remember Me</b-checkbox>
                            <b-button @click="triggerLoader" class="custom-btn" variant="primary" type="submit">LOGIN</b-button>
                        </div>
                    </div>
                </b-form>
            </b-card>
        </div>
    </guest-layout>
</template>

<script>
    import { Errors } from 'form-backend-validation'
    import GuestLayout from "../../components/layout/GuestLayout";

    export default {
        components: {GuestLayout},
        data() {
            return {
                loading: false,

                errors: new Errors(),

                form: {
                    email: '',
                    password: '',
                    remember: null,
                },
            }
        },

        methods: {
            login(event) {
                this.loading = true

                this.axios
                    .post('login', this.form, { baseURL: '/' })
                    .then(this.handleSuccess)
                    .catch(error => {
                        this.errors = new Errors(error.response.data.errors)
                        this.$toasted.error("Unable to log in")
                    })
                    .finally(() => this.loading = false)
            },

            handleSuccess(response) {
                if (response.data.success) {
                    this.token = response.data.access_token
                    localStorage.setItem('access_token', this.token)
                    window.location = this.route('balance.index');
                } else {
                    this.$toasted.error("Unable to log in")
                }
            },
        }
    }
</script>

<style lang="scss" scoped>
    .login-form {
        width: 20rem;
    }

    .login-actions-holder {
        display: flex;
        width: 100%;

        .login-actions {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            flex-grow: 1;
        }
    }
</style>
