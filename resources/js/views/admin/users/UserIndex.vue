<template>
    <div>
        <div class="section-header justify-content-between d-flex">
            <div>
                <h1>Users</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <router-link :to="{ name: 'dashboard' }"
                            >Home</router-link
                        >
                    </div>
                    <div class="breadcrumb-item">Users</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="m-0">User records</h4>
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
                    :data="userData"
                    :columns="columns"
                    :options="options"
                    :class="{ 'show-filter': showFilter }"
                    ref="table"
                >
                    <template slot="status" slot-scope="{ row }">
                        <div
                            :class="[
                                row.status == 'Verified'
                                    ? 'badge badge-success'
                                    : row.status == 'Removed'
                                    ? 'badge badge-danger'
                                    : 'badge badge-warning'
                            ]"
                        >
                            {{ row.status }}
                        </div>
                    </template>

                    <div
                        slot="actions"
                        slot-scope="{ row }"
                        class="d-flex icons"
                    >
                        <i
                            class="fas fa-certificate text-success-hover"
                            title="Verify user"
                            v-if="row.status == 'Unverified'"
                            @click="userVerifiy(row)"
                        ></i>
                        <router-link
                            tag="i"
                            :to="{
                                name: 'users.view',
                                params: { id: row.id }
                            }"
                            class="fas fa-eye text-info-hover"
                            title="View"
                        >
                        </router-link>
                        <i
                            v-if="
                                row.status == 'Verified' ||
                                    row.status == 'Unverified'
                            "
                            class="fas fa-lock text-danger-hover"
                            title="Block"
                            @click="showSwalDelete(row)"
                        ></i>
                        <i
                            v-else
                            class="fas fa-unlock text-success-hover"
                            title="Unblock"
                            @click="userActivate(row)"
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
import "../../../vee-validation.js";
import { sweetAlert } from "../../../mixins/sweet-alert.js";

export default {
    mixins: [sweetAlert],
    data() {
        return {
            userData: [],
            coverImage: {
                file: null,
                name: "",
                url: ""
            },

            //datatables
            columns: [
                "selected",
                "username",
                "email",
                "status",
                "created_at",
                "actions"
            ],
            options: {
                // initialPage: 1,
                filterByColumn: true,
                listColumns: {
                    status: [
                        {
                            text: "Verified",
                            id: "Verified"
                        },
                        {
                            text: "Unverified",
                            id: "Unverified"
                        },
                        {
                            text: "Removed",
                            id: "Removed"
                        }
                    ]
                },
                skin: "table table-md",
                perPage: 10,
                perPageValues: [],
                headings: {
                    username: "username",
                    email: "email",
                    status: "status",
                    created_at: "added on",
                    actions: ""
                },
                sortable: ["username", "email", "created_at", "status"],
                filterable: ["username", "email", "created_at", "status"],
                dateColumns: ["created_at"],
                dateFormat: "DD MMM YYYY",
                datepickerOptions: {
                    showDropdowns: true,
                    autoUpdateInput: true
                },
                columnsClasses: {
                    selected: "d-none",
                    username: "th-min-200",
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
    mounted() {
        this.getusers();
    },
    methods: {
        async getusers() {
            this.isLoading = true;
            try {
                let { data } = await axios.get("/api/getAllUser");

                data.users.forEach(field => {
                    this.userData.push(field);
                    this.userData[this.userData.length - 1][
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
                title: "Blocking user!",
                text: "This user will not allow to sign in system",
                icon: "info",
                showCancelButton: true
            }).then(res => {
                if (res.value) {
                    try {
                        let datas = axios.post("/api/user/delete", {
                            id: row.id
                        });
                    } catch (e) {
                        console.log(e);
                    }
                    let index = this.userData.findIndex(el => el.id === row.id);

                    this.userData[index].status = "Removed";
                    this.showToast("success", "user blocked successfully");
                }
            });
        },
        userActivate(row) {
            this.$swal({
                title: "Unblocking user!",
                text: "This user will allow to sign in system",
                icon: "info",
                showCancelButton: true
            }).then(res => {
                if (res.value) {
                    try {
                        let datas = axios.post("/api/user/activate", {
                            id: row.id
                        });
                    } catch (e) {
                        console.log(e);
                    }
                    let index = this.userData.findIndex(el => el.id === row.id);

                    this.userData[index].status = "Verified";
                    this.showToast("success", "user unblocked successfully");
                }
            });
        },
        userVerifiy(row) {
            this.$swal({
                title: "Verifying user!",
                text: "This user will verified and allow to sign in system",
                icon: "info",
                showCancelButton: true
            }).then(res => {
                if (res.value) {
                    try {
                        let datas = axios.post("/api/user/verifiy", {
                            id: row.id
                        });
                    } catch (e) {
                        console.log(e);
                    }
                    let index = this.userData.findIndex(el => el.id === row.id);

                    this.userData[index].status = "Verified";
                    this.showToast("success", "user verified successfully");
                }
            });
        },
        selectAll: function() {
            this.selectedRows = [];

            let data = this.$refs.table.filteredData;

            if (this.allSelected == false) {
                data.forEach(datas => {
                    this.selectedRows.push(datas.fizzy_token);
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
                        let datas = axios.post("/api/fizzy/delete", {
                            selectedAllFizzyToken: this.selectedRows
                        });

                        this.selectedRows.forEach(fizzyToken => {
                            let index = this.fizzyData.findIndex(
                                el => el.fizzy_token === fizzyToken
                            );
                            this.fizzyData.splice(index, 1);
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
