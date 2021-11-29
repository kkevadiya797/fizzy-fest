<template>
    <div>
        <div class="row mt-4 mb-2">
            <div
                v-for="(card, key) in cardDetails"
                :key="key"
                class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12"
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
                            {{ cardData[key] | numeral("0,0") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="row card">
            <div class="card-body col-md-12 col-lg-12 col-xl-10"></div>
            <div class="chart-desc col-md-12 col-lg-12 col-xl-2"></div>
        </div> -->
        <div class="card">
            <div class="row">
                <div class="col-lg-10 col-md-12 col-12 col-sm-12">
                    <div class="card-header">
                        <h4 class="m-0">This Month</h4>
                        <!-- <div class="card-header-action">
                            <select
                                id="timer"
                                name="Timer"
                                class="form-control"
                            >
                                <option value="0" selected="selected">
                                    This week
                                </option>
                                <option value="1">
                                    This month
                                </option>
                                <option value="2">
                                    This year
                                </option>
                            </select>
                        </div> -->
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
                                    <span class="media-body text-muted">
                                        {{ card.header }}
                                    </span>
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
        <div class="row mt-4 mb-2">
            <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-0">Latest fizzies</h4>
                        <div class="card-header-action">
                            <router-link
                                :to="{
                                    name: 'fizzies.index'
                                }"
                            >
                                <button
                                    class="btn icon-left btn-outline-primary"
                                >
                                    View all
                                </button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <v-client-table
                            :data="fizzyData"
                            :columns="fizzycolumns"
                            :options="fizzyOptions"
                            ref="table"
                        >
                        </v-client-table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="m-0">Latest reports</h4>
                        <div class="card-header-action">
                            <router-link
                                :to="{
                                    name: 'reports.index'
                                }"
                            >
                                <button
                                    class="btn icon-left btn-outline-primary"
                                >
                                    View all
                                </button>
                            </router-link>
                        </div>
                    </div>
                    <div class="card-body">
                        <v-client-table
                            :data="reportData"
                            :columns="reportcolumns"
                            :options="reportOptions"
                            ref="table"
                        >
                        </v-client-table>
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
import Chart from "./Chart.vue";
import moment from "moment";

export default {
    components: { Chart, Loading },
    data() {
        return {
            isLoading: false,
            cardData: {},
            cardDetails: {
                totalFizzies: {
                    header: "Fizzies",
                    color: "bg-primary",
                    icon: "fa-gamepad",
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
                },
                totalReoprts: {
                    header: "Reports",
                    color: "bg-warning",
                    icon: "fa-poll",
                    textColor: "text-warning"
                }
            },
            chartData: {
                monthlyCount: {}
            },
            data: {
                labels: [],
                datasets: [
                    {
                        label: "Fizzies",
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
                    },
                    {
                        label: "Reports",
                        backgroundColor: "#0000",
                        borderColor: "#ffa426",
                        pointBackgroundColor: "#fff",
                        pointHoverBackgroundColor: "#ffa426",
                        pointRadius: "4",
                        data: []
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            },

            //fizzy dataTables
            fizzyData: [],
            fizzycolumns: ["title", "no_of_questions", "created_at"],
            fizzyOptions: {
                // initialPage: 1,
                filterByColumn: false,
                skin: "table table-md",
                perPage: 10,
                perPageValues: [],
                headings: {
                    title: "title",
                    no_of_questions: "questions",
                    created_at: "added on"
                },
                filterable: false,
                templates: {
                    created_at(h, row) {
                        return moment(row.created_at).format("DD MMM YYYY");
                    }
                },
                columnsClasses: {
                    actions: "float-right"
                }
            },

            //report dataTables
            reportData: [],
            reportcolumns: ["title", "players", "created_at"],
            reportOptions: {
                // initialPage: 1,
                filterByColumn: false,
                skin: "table table-md",
                perPage: 10,
                perPageValues: [],
                headings: {
                    title: "title",
                    players: "players",
                    created_at: "played on"
                },
                filterable: false,
                templates: {
                    created_at(h, row) {
                        return moment(row.created_at).format("DD MMM YYYY");
                    }
                },
                columnsClasses: {
                    actions: "float-right"
                }
            }
        };
    },
    mounted() {
        this.loadData();
        this.getFizzies();
        this.getReports();
    },
    methods: {
        async loadData() {
            try {
                this.isLoading = true;

                await axios.get("/api/dashboard").then(({ data }) => {
                    this.cardData = data.cardData;
                    this.chartData.monthlyCount = data.monthlyCount;

                    for (let i = 0; i < data.chartData.fizzies.length; i++) {
                        this.data.labels.push(i + 1);
                    }

                    this.data.datasets[0].data = data.chartData.fizzies;
                    this.data.datasets[1].data = data.chartData.plays;
                    this.data.datasets[2].data = data.chartData.players;
                    this.data.datasets[3].data = data.chartData.reports;

                    this.$refs.chart.loadChart(this.data, this.options);
                });
            } catch (error) {
                console.log(error);
            }
            this.isLoading = false;
        },

        async getFizzies() {
            this.isLoading = true;
            try {
                let { data } = await axios.get("/api/dashboard/fizzy");

                this.fizzyData = data.fizzy;
            } catch (error) {
                console.log(error);
            }
            this.isLoading = false;
        },

        async getReports() {
            this.isLoading = true;
            try {
                let { data } = await axios.get("/api/dashboard/report");

                this.reportData = data.report;
            } catch (error) {
                console.log(error);
            }
            this.isLoading = false;
        }
    }
};
</script>
