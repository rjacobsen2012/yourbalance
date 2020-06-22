<template>
    <guest-layout title="Login">
        <b-card class="shadow-sm m-4 login-form">
            <b-form @submit.prevent="login">
                <b-form-group model="email" :errors="errors">
                    <b-form-input v-model.trim="form.email" type="text" placeholder="Email Address" />
                </b-form-group>

                <b-form-group model="password" :errors="errors">
                    <b-form-input v-model="form.password" type="password" placeholder="Password" />
                </b-form-group>

                <div class="row">
                    <div class="col">
                        <b-checkbox v-model="form.remember" :unchecked-value="null">Remember Me</b-checkbox>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <b-button @click="triggerLoader" variant="primary" type="submit">Login</b-button>
                    </div>
                </div>
            </b-form>
        </b-card>
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
        display: flex;
        flex-direction: column;
        justify-content: center;

        width: 100%;

        .container {
            max-width: 600px;
            width: 100%;
            margin: auto;
            padding: 1rem;
        }
    }
</style>
