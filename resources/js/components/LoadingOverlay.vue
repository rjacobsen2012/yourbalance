<template>
    <transition name="fade" :duration="400">
        <template v-if="visible">
            <div class="overlay loading-overlay" :style="{ position: fixed ? 'fixed' : 'absolute' }">
                <div class="overlay overlay-bg" :style="{ opacity }" />
                <div class="loader-holder">
                    <div class="mt-2 px-4">
                        <loader width="30" fill="#000000"/>
                    </div>
                </div>
            </div>
        </template>
    </transition>
</template>

<script>
    import Loader from './icons/Loader'

    export default {
        components: { Loader },
        props: {
            visible: {
                type: Boolean,
                default: true,
            },
            opacity: {
                type: Number,
                default: 1,
            },
            fixed: {
                type: Boolean,
                default: true,
            },
        },
    }
</script>

<style lang="scss" scoped>
    @import '~sass/helpers';

    .loading-overlay {
        left: 0;
        top: 0;
        min-width: 100%;
        min-height: 100%;
        z-index: 9999;
    }

    .overlay {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        overflow: hidden;
        background-color: rgba(133, 144, 160, 0.2);
        z-index: 9997;
    }

    .overlay .loader {
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate3d(-50%, -50%, 1px);
        transform: translate3d(-50%, -50%, 1px);
        color: #1b3f6e;
        width: 75px;
    }

    .overlay-bg {
        background: linear-gradient(132deg, $pink, $purple);
    }

    .loader-holder {
        @include center;
        width: 100%;
        max-width: 130px;
        color: $blue;
        background-color: transparent;
        padding: 1.5rem;
        border-radius: 4px;
        z-index: 9998;
    }
</style>
