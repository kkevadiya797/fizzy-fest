<template>
    <div>
        <div class="row mt-4 mb-2">
            <div
                v-for="(card, key) in cardDetails"
                :key="key"
                class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12"
            >
                <div class="card card-statistic-1">
                    <div class="card-icon" :class="card.color">
                        <i class="fas" :class="card.icon"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total {{ card.header }}</h4>
                        </div>
                        <div class="card-body">
                            {{ cardData[key] | numeral("0 a") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="row">
                <div class="col-lg-10 col-md-12 col-12 col-sm-12">
                    <div class="card-header">
                        <h4 class="m-0">This Month</h4>
                    </div>
                    <div class="card-body">
                        <chart ref="chart" />
                    </div>
                </div>
                <div class="col-lg-2 col-md-12 col-12 col-sm-12">
                    <div class="card-header">
                        <h4>Overview</h4>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled list-unstyled-border row">
                            <li
                                v-for="(card, key) in cardDetails"
                                :key="key"
                                class="media fz-media col-lg-12 col-sm-3 col-6"
                            >
                                <div
                                    class="media-body text-right"
                                    style="height: 65px;"
                                >
                                    <span class="media-body text-muted">{{
                                        card.header
                                    }}</span>
                                    <div
                                        class="media-title"
                                        :class="card.textColor"
                                    >
                                        {{
                                            chartData.monthlyCount[key]
                                                | numeral("0,0")
                                        }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" v-if="popularFizzies.length > 0">
            <div class="card-header">
                <h4 class="m-0">Top picks</h4>
            </div>
            <div class="card-body d-lg-flex">
                <div class="card-slick">
                    <VueSlickCarousel v-bind="settings">
                        <div
                            v-for="fizzy in popularFizzies"
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
                                                name: 'fizzies.view',
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
import Chart from "./Chart.vue";
import moment from "moment";

import VueSlickCarousel from "vue-slick-carousel";
import "vue-slick-carousel/dist/vue-slick-carousel.css";
import "vue-slick-carousel/dist/vue-slick-carousel-theme.css";

export default {
    components: { Chart, Loading, VueSlickCarousel },
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
            cardData: {},
            cardDetails: {
                totalUsers: {
                    header: "Users",
                    color: "bg-primary",
                    icon: "fa-users",
                    textColor: "text-dark"
                },
                totalPlays: {
                    header: "Plays",
                    color: "bg-danger",
                    icon: "fa-play",
                    textColor: "text-danger"
                },
                totalPlayers: {
                    header: "Players",
                    color: "bg-success",
                    icon: "fa-users",
                    textColor: "text-success"
                }
            },
            chartData: {
                monthlyCount: {}
            },
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Users",
                        backgroundColor: "#0000",
                        borderColor: "#000",
                        pointBackgroundColor: "#fff",
                        pointHoverBackgroundColor: "#000",
                        pointRadius: "4",
                        data: []
                    },
                    {
                        label: "Plays",
                        backgroundColor: "#0000",
                        borderColor: "#fc544b",
                        pointBackgroundColor: "#fff",
                        pointHoverBackgroundColor: "#fc544b",
                        pointRadius: "4",
                        data: []
                    },
                    {
                        label: "Players",
                        backgroundColor: "#0000",
                        borderColor: "#1eca91",
                        pointBackgroundColor: "#fff",
                        pointHoverBackgroundColor: "#1eca91",
                        pointRadius: "4",
                        data: []
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            },
            popularFizzies: []
        };
    },
    created() {
        this.loadData();
    },
    methods: {
        async loadData() {
            try {
                this.isLoading = true;

                await axios.get("/api/dashboard/global").then(({ data }) => {
                    this.cardData = data.cardData;
                    this.chartData.monthlyCount = data.monthlyCount;

                    for (let i = 0; i < data.chartData.users.length; i++) {
                        this.data.labels.push(i + 1);
                    }

                    this.data.datasets[0].data = data.chartData.users;
                    this.data.datasets[1].data = data.chartData.plays;
                    this.data.datasets[2].data = data.chartData.players;

                    this.$refs.chart.loadChart(this.data, this.options);
                });
            } catch (error) {
                console.log(error);
            }

            await this.getPopularFizzies();

            this.isLoading = false;
        },
        async getPopularFizzies() {
            try {
                let { data } = await axios.get("/api/dashboard/popularFizzies");

                this.popularFizzies = data;
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
