<template>
    <guest-layout title="Register">
        <div class="guest-container">
            <b-card class="shadow-sm m-4 register-form">
                <b-form @submit.prevent="register">
                    <b-form-group model="first_name" :errors="errors">
                        <b-form-input class="custom-input" size="sm" v-model.trim="form.first_name" type="text" placeholder="First Name" />
                    </b-form-group>

                    <b-form-group model="last_name" :errors="errors">
                        <b-form-input class="custom-input" size="sm" v-model.trim="form.last_name" type="text" placeholder="Last Name" />
                    </b-form-group>

                    <b-form-group model="email" :errors="errors">
                        <b-form-input class="custom-input" size="sm" v-model.trim="form.email" type="text" placeholder="Email Address" />
                    </b-form-group>

                    <b-form-group model="password" :errors="errors">
                        <b-form-input class="custom-input" size="sm" v-model="form.password" type="password" placeholder="Password" />
                    </b-form-group>

                    <b-form-group model="password_confirmation" :errors="errors">
                        <b-form-input class="custom-input" size="sm" v-model="form.password_confirmation" type="password" placeholder="Confirm Password" />
                    </b-form-group>

                    <div class="row">
                        <div class="col"></div>
                        <div class="col text-right">
                            <b-button @click="triggerLoader" class="custom-btn" variant="primary" type="submit">REGISTER</b-button>
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
                    first_name: '',
                    last_name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
            }
        },

        methods: {
            register(event) {
                this.loading = true

                this.axios
                    .post('register', this.form, { baseURL: '/' })
                    .then(this.handleSuccess)
                    .catch(error => this.errors = new Errors(error.response.data.errors))
                    .finally(() => this.loading = false)
            },

            handleSuccess(response) {
                if (response.data.success) {
                    this.token = response.data.access_token
                    localStorage.setItem('access_token', this.token)
                    window.location = '/balance';
                } else {
                    this.$toasted.error("Unable to log in")
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .register-form {
        width: 20rem;
    }
</style>
