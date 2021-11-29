<template>
    <loading
        class="loader-style"
        :active.sync="isLoading"
        :is-full-page="true"
        :color="'#3a3ab1'"
        :loader="'bars'"
    ></loading>
</template>

<script>
import Loading from "vue-loading-overlay";

export default {
    components: {
        Loading
    },
    data() {
        return {
            isLoading: true
        };
    },
    created() {
        this.setUser();
    },
    methods: {
        async setUser() {
            await this.$store.dispatch("getUser");

            this.$router.push({ path: this.$route.params.to });
        }
    }
};
</script>

<style scoped>
.loader-style {
    top: 0 !important;
    left: 0 !important;
}
</style>
