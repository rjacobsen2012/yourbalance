<template>
    <div>
        <b-navbar class="top-nav navbar navbar-expand shadow-sm" type="light" variant="light">
            <b-navbar-brand>
                <div class="brand-name">
                    <div>
                        <img src="/images/yourbalance-1-default@logo.png" width="24"/>
                    </div>
                    <div class="app-logo-text">
                        <inertia-link v-if="!pageEquals('guest')" href="/">
                            <span class="logo-a">{{ logoA }}</span><span class="logo-b">{{ logoB }}</span>
                        </inertia-link>
                        <span v-else>
                            <span class="logo-a">{{ logoA }}</span><span class="logo-b">{{ logoB }}</span>
                        </span>
                    </div>
                </div>
            </b-navbar-brand>

            <b-navbar-nav class="ml-auto notifications">
                <b-nav-item v-if="!pageEquals('login')" href="/login" class="link">Login</b-nav-item>
                <b-nav-item v-if="!pageEquals('register')" href="/register" class="link">Register</b-nav-item>
            </b-navbar-nav>
        </b-navbar>

        <div class="layout-box">
            <div class="inner-layout-box">
                <b-container fluid class="w-full">
                    <b-row class="content" :style="contentStyles">
                        <slot />
                    </b-row>
                </b-container>
            </div>
        </div>
        <loading-overlay :visible="isloading" :opacity="0.3"/>
    </div>
</template>

<script>
    import user from '@mixins/user'
    import LoadingOverlay from "../LoadingOverlay";

    export default {
        components: {
            LoadingOverlay,
        },

        mixins: [user],

        props: {
            loading: { type: Boolean, default: false },
            title: {
                type: String,
            },
            contentStyles: {
                type: [Object, String],
                default: () => ({})
            }
        },

        created() {
            if (!this.currentRoute('login')) {
                this.$bus.$on('isloading', this.setLoading)
            }
        },

        data() {
            return {
                isloading: false,
                // notifications: [],
                // notificationInterval: null,
            }
        },

        mounted() {
            // this.loadNotifications()

            // this.notificationInterval = setInterval(this.loadNotifications, 60000)
        },

        beforeDestroy() {
            // clearInterval(this.notificationInterval)
        },

        methods: {
            updateTitle(title) {
                document.title = `${title} | ${this.$page.app.name}`
            },

            logout() {
                this.axios
                    .post(this.route('logout'))
                    .finally(response => this.$inertia.visit('/login'))
            },

            pageEquals(page) {
                return this.$page.currentRouteName === page
            }
        },

        computed: {
            slug() {
                return this.title.toLowerCase().replace(/[\s]+/, '-').replace(/\W+/, '')
            },
            logoA() {
                return this.$page.app.name.split(' ')[0]
            },
            logoB() {
                return this.$page.app.name.split(' ')[1]
            }
            // unreadNotifications() {
            //     return _.filter(this.notifications, n => n.read_at === null).length
            // },
        },

        watch: {
            title: {
                immediate: true,
                handler: 'updateTitle',
            },

            '$page.flash': {
                handler() {
                    if (this.$page.flash.message.length) {
                        this.$toasted.info(this.$page.flash.message)
                    }

                    if (this.$page.flash.error.length) {
                        this.$toasted.error(this.$page.flash.message)
                    }

                    if (this.$page.flash.success.length) {
                        this.$toasted.success(this.$page.flash.message)
                    }
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~sass/helpers';

    .copyright {
        color: #a5b4c7;
        position: absolute;
        bottom: 4rem;
        left: -1.75rem;
        transform: rotate(-90deg);
        white-space: nowrap;
    }

    .nav-icon {
        width: 1.8rem;
        height: 1.8rem;
        line-height: 1.8rem;
    }

    .home {
        color: $link-blue;
        font-size: $small-text;
    }

    .brand-name {
        display: flex;
        flex-direction: row;
        justify-content: center;
        font-size: $normal-text;
    }
</style>
