<template>
    <div class="main-wrapper main-wrapper-1">
        <site-header />
        <site-sidebar />
        <div class="main-content" style="min-height: 523px;">
            <section class="section">
                <transition name="fade" appear mode="out-in">
                    <router-view />
                </transition>
            </section>
        </div>
    </div>
</template>

<script>
import SiteHeader from "./partials/TheSiteHeader.vue";
import SiteSidebar from "./partials/TheSiteSideBar.vue";

export default {
    components: {
        SiteHeader,
        SiteSidebar
    },
    created() {
        this.dynamicallyLoadScript("/assets/js/fizzy-fest-scripts.js");
    },
    methods: {
        dynamicallyLoadScript(url) {
            var script = document.createElement("script");
            script.src = url;

            document.head.appendChild(script);
        }
    }
};
</script>

<style scoped>
.fade-enter {
    opacity: 0;
}
.fade-enter-active {
    transition: opacity 0.3s;
}
.fade-leave-active {
    transition: 0.3s;
    opacity: 0;
}
</style>
