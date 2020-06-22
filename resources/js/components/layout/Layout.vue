<template>
    <div>
        <b-navbar class="top-nav navbar navbar-expand shadow-sm" type="light" variant="light">
            <b-navbar-brand>
                <div class="brand-name">
                    <div>
                        <img src="/images/yourbalance-1-default@logo.png" width="24"/>
                    </div>
                    <div class="app-logo-text">
                        <inertia-link v-if="!isHomePage" class="logo-link" href="/balance">
                            <span class="logo-a">{{ logoA }}</span><span class="logo-b">{{ logoB }}</span>
                        </inertia-link>
                        <span v-else>
                            <span class="logo-a">{{ logoA }}</span><span class="logo-b">{{ logoB }}</span>
                        </span>
                    </div>
                </div>
            </b-navbar-brand>

            <b-navbar-nav class="ml-auto notifications" type="light" variant="light">
                <b-nav-item-dropdown right>
                    <template slot="button-content">
                        <img :src="`https://secure.gravatar.com/avatar/${gravatar}?size=20`" class="user-gravatar rounded-full w-8 h-8 mr-3"/>{{ $user.name }}
                    </template>

                    <b-dropdown-item @click.prevent="logout">
                        Logout
                    </b-dropdown-item>
                </b-nav-item-dropdown>
            </b-navbar-nav>
        </b-navbar>

        <div class="layout-box">
            <div class="inner-layout-box">
                <div class="content" :style="contentStyles">
                    <slot />
                </div>
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
        mixins: [
            user,
        ],

        props: {
            loading: { type: Boolean, default: false },
            title: {
                type: String,
            },
            contentStyles: {
                type: [Object, String],
                default: () => ({})
            },
            budget: { type: [Object, Array] },
        },

        created() {
            this.$bus.$on('isloading', this.setLoading)
            // console.log(this.route('budget.edit', [this.budget.id]))
            // console.log(this.route('budget.edit', [this.budget.id]))
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
            setLoading(load) {
                this.isloading = load
            },

            updateTitle(title) {
                document.title = `${title} | ${this.$page.app.name}`
            },

            setBudget(budget) {
                this.budget = budget
            },

            forgetBudgetCookie() {
                return this.currentRoute('home') ||
                    this.currentRoute('budget.index') ||
                    this.currentRoute('budget.create') ||
                    this.currentRoute('welcome') ||
                    this.currentRoute('login') ||
                    this.currentRoute('register')
            },

            // readNotifications() {
            //     this.axios.post('notifications/read')
            //
            //     setTimeout(() => {
            //         for (let i in this.notifications) {
            //             this.notifications[i].read_at = this.$moment()
            //         }
            //
            //     }, 2000)
            // },
            //
            // loadNotifications() {
            //     this.axios
            //         .get('notifications')
            //         .then(response => this.notifications = response.data)
            // },

            logout() {
                this.axios
                    .post(this.route('logout'))
                    .finally(response => this.$inertia.visit('/'))
            },

            isPage(page) {
                return this.$page.currentRouteName === page
            },
        },

        computed: {
            slug() {
                return this.title.toLowerCase().replace(/[\s]+/, '-').replace(/\W+/, '')
            },

            gravatar() {
                return this.$user.gravatar
            },

            isHomePage() {
                return this.$page.currentRouteName === 'home'
            },

            isBudgetIndex() {
                return this.$page.currentRouteName === "budget.index" ||
                    this.$page.currentRouteName === 'home'
            },

            isBudgetShow() {
                return this.$page.currentRouteName === "budget.show"
            },

            viewBudget() {
                return this.budget
            },
            logoA() {
                return this.$page.app.name.split(' ')[0]
            },
            logoB() {
                return this.$page.app.name.split(' ')[1]
            },
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
            },

            loading: function (load) {
                this.isloading = this.load
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

    .notifications {
        .badge {
            font-size: 60% !important;
            margin-left: -1.25rem !important;
        }

        .notification-title {
            font-weight: normal;
        }

        .notification-unread {
            background-color: $body-bg;

            .notification-title {
                font-weight: bold;
            }
        }
    }

    .user-gravatar {
        border-radius: 50%;
        @include box-shadow;
    }

    .home {
        color: $link-blue;
        font-size: $small-text;
    }

    .brand-name {
        display: flex;
        flex-direction: row;
        justify-content: center;
    }
</style>
