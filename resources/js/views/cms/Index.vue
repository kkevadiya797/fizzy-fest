<template>
    <div>
        <div id="top" class="section-header justify-content-between d-flex">
            <div>
                <h1>CMS</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <router-link :to="{ name: 'dashboard' }"
                            >Home</router-link
                        >
                    </div>
                    <div class="breadcrumb-item">CMS</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-pills" id="myTab3" role="tablist">
                    <li v-for="page in pages" :key="page" class="nav-item">
                        <a
                            class="nav-link"
                            :id="page + 'PageIndex-tab'"
                            data-toggle="tab"
                            :href="'#' + page"
                            role="tab"
                            :aria-controls="page"
                            aria-selected="true"
                            @click="currentTabComponent = page + 'PageIndex'"
                        >
                            {{ page }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="myTabContent2">
                    <component
                        :is="currentTabComponent"
                        class="tab-pane fade show active"
                        :id="currentTabComponent"
                        role="tabpanel"
                        :aria-labelledby="currentTabComponent + '-tab'"
                    ></component>
                </div>
            </div>
        </div>
        <loading
            :active.sync="isLoading"
            :is-full-page="true"
            :color="'#3a3ab1'"
            :loader="'bars'"
        ></loading>
    </div>
</template>

<script>
// home-page
import Loading from "vue-loading-overlay";
import HomePageIndex from "./HomePage/HomePageIndex.vue";

export default {
    components: {
        HomePageIndex,
        Loading
    },
    data() {
        return {
            pages: ["Home"],
            currentTabComponent: "HomePageIndex",
            isLoading: false
        };
    }
};
</script>
