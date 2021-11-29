<template>
    <div>
        <div class="card mt-4" v-for="(fizzys, key) in fizzies" :key="key">
            <div v-if="key == 'Latest'" class="card-slick">
                <VueSlickCarousel v-bind="settings">
                    <div
                        v-for="fizzy in fizzys"
                        :key="fizzy['fizzy_token']"
                        class="py-1 mr-3"
                    >
                        <div class="text-left card-hero">
                            <div class="fizzy-item py-3">
                                <div class="fizzy-image position-relative">
                                    <img
                                        alt="fizzy cover"
                                        :src="
                                            '/storage/fizzy_cover/' +
                                                fizzy['fizzy_token'] +
                                                '.jpg'
                                        "
                                        class="w-100 d-block h-auto"
                                    />
                                    <span class="fizzy-tag">New</span>
                                    <router-link
                                        :to="{
                                            name: 'discover.view',
                                            params: {
                                                token: fizzy['fizzy_token']
                                            }
                                        }"
                                    >
                                        <span
                                            class="fizzy-view"
                                            :class="setRandomBgColor()"
                                        >
                                            <i class="fas fa-play"></i>
                                        </span>
                                    </router-link>
                                </div>
                                <div>
                                    <h3 class="mb-2 text-dark">
                                        {{ fizzy["title"] }}
                                    </h3>
                                    <div class="font-weight-normal">
                                        <span class="badge badge-primary">
                                            {{ fizzy["type"] }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </VueSlickCarousel>
            </div>
            <div v-else-if="fizzys.length > 0" class="card-body d-lg-flex">
                <div class="card-header d-lg-none p-0 pb-3">
                    <h4 class="m-0">{{ key }}</h4>
                    <div class="card-header-action">
                        <router-link
                            v-if="key != 'Top picks'"
                            :to="{
                                name: 'discover.type',
                                params: { type: key }
                            }"
                        >
                            <button class="btn icon-left btn-outline-primary">
                                See more
                            </button>
                        </router-link>
                    </div>
                </div>
                <div
                    class="card-title d-none d-lg-block text-center position-relative m-0 p-4"
                    style="background-image: url('/assets/images/fizzy type/pupular-fizzies.png')"
                >
                    <h4 class="m-0 text-white">
                        {{ key }}
                    </h4>
                    <router-link
                        v-if="key != 'Top picks'"
                        :to="{ name: 'discover.type', params: { type: key } }"
                    >
                        <button class="btn icon-left btn-light">
                            See more
                        </button>
                    </router-link>
                </div>
                <div class="card-slick">
                    <VueSlickCarousel v-bind="settings">
                        <div
                            v-for="fizzy in fizzys"
                            :key="fizzy.fizzy_token"
                            class="py-1 mr-3"
                        >
                            <div class="text-center">
                                <div class="fizzy-item pb-3">
                                    <div class="fizzy-image position-relative">
                                        <img
                                            alt="fizzy cover"
                                            :src="
                                                '/storage/fizzy_cover/' +
                                                    fizzy.fizzy_token +
                                                    '.jpg'
                                            "
                                            class="w-100 d-block h-auto"
                                        />
                                        <span class="fizzy-tag"
                                            >Q.
                                            {{ fizzy.no_of_questions }}</span
                                        >
                                        <router-link
                                            :to="{
                                                name: 'discover.view',
                                                params: {
                                                    token: fizzy.fizzy_token
                                                }
                                            }"
                                        >
                                            <span
                                                class="fizzy-view"
                                                :class="setRandomBgColor()"
                                            >
                                                <i class="fas fa-play"></i>
                                            </span>
                                        </router-link>
                                    </div>
                                    <div>
                                        <h4 class="mb-1 text-dark">
                                            {{ fizzy.title }}
                                        </h4>
                                        <div class="text-warning mb-1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <div
                                            class="text-muted text-small justify-content-around d-flex font-weight-600"
                                        >
                                            <div class="text-success">
                                                <i class="fas fa-play mr-2"></i>
                                                {{
                                                    fizzy.plays | numeral("0 a")
                                                }}
                                            </div>
                                            <div>
                                                <i
                                                    class="fas fa-users mr-2"
                                                ></i>
                                                {{
                                                    fizzy.players
                                                        | numeral("0 a")
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </VueSlickCarousel>
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
import Loading from "vue-loading-overlay";

import VueSlickCarousel from "vue-slick-carousel";
import "vue-slick-carousel/dist/vue-slick-carousel.css";
import "vue-slick-carousel/dist/vue-slick-carousel-theme.css";

export default {
    components: { Loading, VueSlickCarousel },
    data() {
        return {
            settings: {
                arrows: true,
                dots: false,
                infinite: true,
                speed: 500,
                initialSlide: 0,
                slidesToScroll: 2,
                swipeToSlide: true,
                variableWidth: true
            },
            isLoading: false,
            fizzies: []
        };
    },
    created() {
        this.loadData();
    },
    methods: {
        async loadData() {
            this.isLoading = true;
            try {
                let { data } = await axios.get("/api/discover");

                this.fizzies = data;
            } catch (error) {
                console.log(error);
            }
            this.isLoading = false;
        },
        setRandomBgColor() {
            let bgColors = [
                "bg-primary",
                "bg-success",
                "bg-danger",
                "bg-info",
                "bg-warning"
            ];

            let randomnumber = Math.floor(Math.random() * bgColors.length);

            return bgColors[randomnumber];
        }
    }
};
</script>
