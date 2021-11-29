<template>
    <div>
        <div class="section-header justify-content-between d-flex">
            <div>
                <h1>Reports</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <router-link :to="{ name: 'dashboard' }"
                            >Home</router-link
                        >
                    </div>
                    <div class="breadcrumb-item">Reports</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="m-0">Game reports</h4>
                <div class="card-header-action">
                    <div v-if="selectedRows.length > 0" class="dropdown">
                        <button
                            class="btn btn-link dropdown-toggle"
                            type="button"
                            data-toggle="dropdown"
                        >
                            Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right mt-1">
                            <a
                                @click="deleteSelectedRows"
                                class="dropdown-item has-icon"
                            >
                                <i class="fas fa-trash"></i>Delete
                            </a>
                        </div>
                    </div>
                    <button
                        @click="showFilter = !showFilter"
                        class="btn icon-left btn-outline-primary"
                    >
                        Filter
                        <i
                            class="fas"
                            :class="{
                                'fa-filter': !showFilter,
                                'fa-times': showFilter
                            }"
                        ></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <v-client-table
                    :data="reportData"
                    :columns="columns"
                    :options="options"
                    :class="{ 'show-filter': showFilter }"
                    ref="table"
                >
                    <template slot="h__selected">
                        <div class="custom-checkbox custom-control">
                            <input
                                type="checkbox"
                                data-checkboxes="mygroup"
                                data-checkbox-role="dad"
                                class="custom-control-input"
                                id="checkbox-all"
                                v-model="allSelected"
                                @click="selectAll()"
                            />
                            <label
                                for="checkbox-all"
                                class="custom-control-label"
                                >&nbsp;</label
                            >
                        </div>
                    </template>
                    <template slot="selected" slot-scope="{ row }">
                        <div class="custom-checkbox custom-control">
                            <input
                                type="checkbox"
                                data-checkboxes="mygroup"
                                class="custom-control-input"
                                :id="row.id"
                                v-model="selectedRows"
                                :value="row.id"
                                @click="select"
                            />
                            <label :for="row.id" class="custom-control-label"
                                >&nbsp;</label
                            >
                        </div>
                    </template>
                    <div
                        slot="actions"
                        slot-scope="{ row }"
                        class="d-flex icons"
                    >
                        <router-link
                            tag="i"
                            :to="{
                                name: 'reports.view',
                                params: { id: row.id }
                            }"
                            class="fas fa-eye text-info-hover"
                            title="View"
                        >
                        </router-link>
                        <i
                            class="fas fa-trash text-danger-hover"
                            title="Delete"
                            @click="showSwalDelete(row)"
                        ></i>
                    </div>
                </v-client-table>
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";
import { sweetAlert } from "../../mixins/sweet-alert.js";

export default {
    mixins: [sweetAlert],
    data() {
        return {
            reportData: [],
            //datatables
            columns: [
                "selected",
                "id",
                "title",
                "players",
                "created_at",
                "actions"
            ],
            options: {
                // initialPage: 1,
                skin: "table table-md",
                perPage: 10,
                perPageValues: [],
                headings: {
                    id: "id",
                    title: "title",
                    players: "players",
                    created_at: "played on",
                    actions: ""
                },
                columnsDisplay: {
                    id: "mobile"
                },
                sortable: ["title", "players", "created_at"],
                filterByColumn: true,
                filterable: ["title", "created_at"],
                dateColumns: ["created_at"],
                dateFormat: "DD MMM YYYY",
                datepickerOptions: {
                    showDropdowns: true,
                    autoUpdateInput: true
                },
                columnsClasses: {
                    selected: "th-selected",
                    title: "th-min-300",
                    created_at: "white-space",
                    actions: "float-right"
                }
            },
            showFilter: false,
            isLoading: false,
            isProcessing: false,
            allSelected: false,
            selectedRows: []
        };
    },
    components: {
        Loading,
        ValidationProvider,
        ValidationObserver
    },
    computed: {
        countFizzies: function() {
            if (this.fizzyData) {
                return this.fizzyData.length;
            } else {
                return 0;
            }
        }
    },
    mounted() {
        this.getFizzies();
    },
    methods: {
        async getFizzies() {
            this.isLoading = true;
            try {
                let { data } = await axios.get("/api/report");

                data.report.forEach(field => {
                    this.reportData.push(field);
                    this.reportData[this.reportData.length - 1][
                        "created_at"
                    ] = moment(field.created_at);
                });
            } catch (e) {
                console.log(e);
            }
            this.isLoading = false;
        },
        showSwalDelete(row) {
            this.$swal({
                title: "Are you sure?",
                text: "You will not be able to recover this report",
                icon: "warning",
                showCancelButton: true
            }).then(res => {
                if (res.value) {
                    try {
                        let datas = axios.post("/api/report/delete", {
                            reportId: row.id
                        });
                    } catch (e) {
                        console.log(e);
                    }
                    let dsf = this.reportData[
                        this.reportData.findIndex(el => el.id === row.id)
                    ];
                    let index = this.reportData.findIndex(
                        el => el.id === row.id
                    );
                    this.reportData.splice(index, 1);

                    this.showToast("success", "report deleted successfully");
                }
            });
        },
        selectAll: function() {
            this.selectedRows = [];

            let data = this.$refs.table.filteredData;

            if (this.allSelected == false) {
                data.forEach(datas => {
                    this.selectedRows.push(datas.id);
                });
            }
        },
        select: function() {
            this.allSelected = false;
        },
        deleteSelectedRows() {
            let data = this.$refs.table.filteredData;
            this.$swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Fizzy",
                icon: "warning",
                showCancelButton: true
            }).then(res => {
                if (res.value) {
                    try {
                        let datas = axios.post("/api/report/delete", {
                            selectedAllReportId: this.selectedRows
                        });

                        this.selectedRows.forEach(id => {
                            let index = this.reportData.findIndex(
                                el => el.id === id
                            );
                            this.reportData.splice(index, 1);
                        });
                        this.showToast(
                            "success",
                            "fizzies deleted successfully"
                        );

                        this.allSelected = false;
                        this.selectedRows = [];
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        }
    }
};
</script>

<style scoped>
.VuePagination {
    text-align: center;
}

.vue-title {
    text-align: center;
    margin-bottom: 10px;
}

.vue-pagination-ad {
    text-align: center;
}

.glyphicon.glyphicon-eye-open {
    width: 16px;
    display: block;
    margin: 0 auto;
}

th:nth-child(3) {
    text-align: center;
}

.VueTables__child-row-toggler {
    width: 16px;
    height: 16px;
    line-height: 16px;
    display: block;
    margin: auto;
    text-align: center;
}

.VueTables__child-row-toggler--closed::before {
    content: "+";
}

.VueTables__child-row-toggler--open::before {
    content: "-";
}
</style>
