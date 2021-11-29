<template>
    <div class="my-4">
        <div class="card">
            <div class="card-header">
                <h4 class="m-0">Latest Fizzies</h4>
            </div>
            <div class="card-body d-lg-flex" v-if="currentFizzy != null">
                <div class="card-slick">
                    <VueSlickCarousel v-bind="settings">
                        <div
                            v-for="fizzy in currentFizzy['Latest']"
                            :key="fizzy['fizzy_token']"
                            class="py-1 mr-3"
                        >
                            <div class="text-center">
                                <div class="fizzy-item pb-3">
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
                                        <span class="fizzy-tag"
                                            >Q.
                                            {{ fizzy["no_of_questions"] }}</span
                                        >
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
                                    <div class="px-2">
                                        <div class="font-weight-600 mb-1">
                                            {{ fizzy["title"] }}
                                        </div>
                                        <div>
                                            <p class="mb-1 text-muted font-weight-normal">{{ fizzy["description"] }}</p>
                                        </div>
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
                                                    fizzy["plays"]
                                                        | numeral("0 a")
                                                }}
                                            </div>
                                            <div>
                                                <i
                                                    class="fas fa-users mr-2"
                                                ></i>
                                                {{
                                                    fizzy["players"]
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

        <div class="card">
            <div class="card-header">
                <h4>Filter by Channel</h4>
            </div>
            <div class="card-body">
                <button
                    class="btn mr-2"
                    v-for="(type, key) in fizzyTypes"
                    :key="key"
                    :class="(currentFizzyType == type ? 'btn-primary' : 'btn-secondary')"
                    @click="currentFizzyType = type"
                >
                    {{ type}} ({{currentFizzy[type].length}})
                </button>
            </div>
            <div class="d-flex flex-wrap m-auto justify-content-center" v-if="currentFizzy != null">
                <div
                    class="fizzy-item pb-3 mx-2"
                    v-for="(fizzy, key) in currentFizzy[currentFizzyType]"
                    :key="key"
                >
                    <div class="text-center bg-white">
                        <div class="fizzy-item pb-3">
                            <div class="fizzy-image position-relative">
                                <img
                                    alt="fizzy cover"
                                    :src="
                                        '/storage/fizzy_cover/' +
                                            fizzy['fizzy_token'] +
                                            '.jpg'
                                    "
                                    class="w-100 d-block h-auto fz-card-img"
                                />
                                <span class="fizzy-tag">Q.{{ fizzy["no_of_questions"] }}</span>
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
                            <div class="px-3">
                                <div class="font-weight-600 mb-1">
                                    {{ fizzy["title"] }}
                                </div>
                                <div>
                                    <p class="mb-1 text-muted font-weight-normal">{{ fizzy["description"] }}</p>
                                </div>
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
                                            fizzy["plays"]
                                                | numeral("0 a")
                                        }}
                                    </div>
                                    <div>
                                        <i
                                            class="fas fa-users mr-2"
                                        ></i>
                                        {{
                                            fizzy["players"]
                                                | numeral("0 a")
                                        }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
            isLoading: false,
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
            fizzyTypes: [],
            fizzies: [],
            currentFizzy: null,
            currentFizzyType: 'Top picks', 
        };
    },

    created() {
        this.getFizzies();
    },

    methods: {
        async getFizzies() {
            try {
                let test = await axios.get("/api/discover");

                for (const key in test.data) {
                    if (key != 'Latest') {
                        this.fizzyTypes.push(key);
                    }
                }
                this.currentFizzy = test.data;
                
            } catch (error) {
                console.log(error);
            }
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

<style scoped>
.wrapper {
    width: 100%;
    display: inline-block;
    padding: 13px 0;
    border-radius: 3px;
    box-shadow: 0 3px 8px 0 rgba(0, 0, 0, 0.18);
}
.d-main-artical{
    transition: transform .2s;
    cursor: pointer;
}

.d-main-artical:hover{
     transform: scale(0.9);
}
.col-12{
    padding-right: 0px;
}
.fz-card-img {
    min-height: 100%;
    min-width: 100%;
    max-width: 100%;
}
</style>
