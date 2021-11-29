<template>
    <div>
        <div class="section-header">
            <h1>Report details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'dashboard' }">Home</router-link>
                </div>
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'reports.index' }"
                        >reports</router-link
                    >
                </div>
                <div class="breadcrumb-item">Report details</div>
            </div>
        </div>
        <div id="reportData" class="w-max-1200 m-auto">
            <div class="mb-4 printBlock d-none justify-content-between printFlex">
                <a href="/home">
                    <img src="/assets/images/logo/fizzyfest-logo.png" alt="fizzyfest-logo" width="100px">
                </a>
                <a href="/home">https://fizzyfest.com</a>
            </div>
            <div
                class="fz-cover-view border-radius-1 position-relative"
                :style="{
                    'background-image': 'url(' + coverImage + ')'
                }"
            >
                <h2 class="p-4 text-white">{{ title }}</h2>
                <h6 class="p-4 text-white">
                    <span>Hosted by : </span>{{ hostedBy }}
                </h6>
                <h6 class="p-4 text-white">
                    <span>Played on : {{ created_at }}</span>
                </h6>
                <button id="printBtn" class="btn icon-right btn-primary printHide" @click="print">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
            <div class="card border-radius-1 border-top-0" style="z-index:-1;">
                <div class="card-body">
                    <div class="text-center">
                        <h6>Players ({{ this.reportData.length }})</h6>
                    </div>
                    <v-client-table
                        :data="reportData"
                        :columns="columns"
                        :options="options"
                        ref="table"
                    >
                    </v-client-table>
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

export default {
    components: {
        Loading
    },
    data() {
        return {
            coverImage: "",
            reportData: [],
            columns: ["rank", "name", "totalPoints"],
            options: {
                // initialPage: 1,
                filterByColumn: true,
                skin: "table table-md",
                perPageValues: [],
                headings: {
                    rank: "rank",
                    name: "name",
                    totalPoints: "total Point"
                },
                sortable: ["rank", "name", "totalPoints"],
                filterable: false,
                columnsClasses: {
                    actions: "float-right"
                }
            },
            showFilter: false,
            title: "",
            hostedBy: "",
            created_at: "",
            isLoading: false
        };
    },
    mounted() {
        this.getReportDetail();
    },
    created() {
        this.coverImage =
            "/assets/images/cover/game_cover_" +
            Math.floor(Math.random() * 4 + 1) +
            ".svg";
    },
    methods: {
        async getReportDetail() {
            this.isLoading = true;
            try {
                let { data } = await axios.get(
                    `/api/report/${this.$route.params.id}`
                );

                this.reportData = data.report;
                this.title = data.reportTitle;
                this.hostedBy = data.hostName;
                this.created_at = data.created_at;
            } catch (e) {
                //page not found
                if (e.response.status === 404) {
                    this.$router.push({ name: "404" });
                }
            }
            this.isLoading = false;
        },
        print() {
            let head = $('head').html();
            let data = $('#reportData').html();
            
            let newWin = window.open();

            newWin.document.open();
            newWin.document.write('<html>');
            newWin.document.write('<head>' + head +'</head>');
            newWin.document.write('<body onload="window.print()">'+ data +'</body>');
            newWin.document.write('</html>');
            newWin.document.close();
            
            setTimeout(function(){newWin.close();},3);
        }
    }
};
</script>

<style scoped>
.card-header {
    display: block;
}

.fz-cover-view {
    position: relative;
    height: 200px;
    width: 100%;
    background-size: cover;
    background-position: center;
    box-shadow: 0 0 2rem 0 rgba(140, 144, 158, 0.23);
}
.border-radius-1 {
    border-radius: 0.5rem;
}
.card {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    padding-top: 10px;
    margin-top: -10px;
}
.b {
    color: #34395e;
}
.fz-play-div {
    border-radius: 100%;
    box-shadow: 0 0 2rem 0 rgba(140, 144, 158, 0.23);
    top: 100%;
    left: 50%;
    transform: translate(-50%, -38px);
}
.fz-btn-play {
    height: 60px;
    width: 60px;
    border-radius: 100%;
    margin: 8px;
}
.fz-btn-play i.fas {
    font-size: 18px !important;
    margin-top: 13px;
    margin-left: -3px !important;
}
.fz-play-div {
    top: 260px;
    background-color: white;
    box-shadow: none;
}
h6 {
    font-size: 0.9rem !important;
}
.p-4 {
    padding-bottom: 0px !important;
}
#printBtn {
    position: absolute;
    top: 24px;
    right: 24px;
}
@media print {
    body {
        background-color: #fff!important;
    }
    .printBlock {
        display: block!important;
    }
    .printFlex {
        display: flex!important;
    }
    .printHide {
        display: none!important;
    }
}
</style>
