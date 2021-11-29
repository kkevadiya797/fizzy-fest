<template>
    <div>
        <div class="section-header justify-content-between d-flex">
            <div>
                <h1>Fizzies</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active">
                        <router-link :to="{ name: 'dashboard' }"
                            >Home</router-link
                        >
                    </div>
                    <div class="breadcrumb-item">Fizzies</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h4 class="m-0">Fizzy records</h4>
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
                    :data="fizzyData"
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
                                :id="row.fizzy_token"
                                v-model="selectedRows"
                                :value="row.fizzy_token"
                                @click="select"
                            />
                            <label
                                :for="row.fizzy_token"
                                class="custom-control-label"
                                >&nbsp;</label
                            >
                        </div>
                    </template>
                    <template slot="is_published" slot-scope="{ row }">
                        <div style="margin-left: 35px;">
                            <i
                                v-if="row.is_published"
                                class="fas fa-check text-success"
                            ></i>
                            <i v-else class="fas fa-times text-danger"></i>
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
                                name: 'fizzies.view',
                                params: { token: row.fizzy_token }
                            }"
                            class="fas fa-eye text-info-hover"
                            title="View"
                        >
                        </router-link>
                        <i
                            v-if="row.is_published"
                            class="fas fa-minus text-danger-hover"
                            title="Unpublish"
                            @click="fizzyStatus(row)"
                        ></i>
                        <i
                            v-else
                            class="fas fa-plus text-success-hover"
                            title="publish"
                            @click="fizzyStatus(row)"
                        ></i>
                        <i
                            class="fas fa-pen text-success-hover"
                            title="Edit"
                            data-toggle="modal"
                            data-target="#selectedFizzyEdit"
                            @click="setEditFizzy(row)"
                        ></i>
                        <i
                            class="fas fa-trash text-danger-hover"
                            title="Delete"
                            @click="showSwalDelete(row)"
                        ></i>
                    </div>
                </v-client-table>
            </div>
        </div>

        <!-- edit model -->
        <div
            class="modal fade"
            id="selectedFizzyEdit"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Edit Fizzy
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <validation-observer ref="form">
                            <form @submit.prevent="onSubmit">
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label
                                            class="form-control-label"
                                            for="title"
                                        >
                                            Title
                                            <span class="text-danger"> *</span>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <validation-provider
                                            mode="lazy"
                                            rules="required"
                                            #default="{ errors, classes }"
                                        >
                                            <div :class="classes">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="title"
                                                    name="Title"
                                                    placeholder="Title"
                                                    v-model="
                                                        selectedFizzy.title
                                                    "
                                                />
                                                <span>{{ errors[0] }}</span>
                                            </div>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label
                                            class="form-control-label"
                                            for="description"
                                            >Description
                                            <span class="font-weight-500">
                                                (Optional)</span
                                            >
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea
                                            class="form-control"
                                            id="description"
                                            placeholder="Description"
                                            v-model="selectedFizzy.description"
                                            rows="3"
                                        ></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label
                                            class="form-control-label"
                                            for="cover-image"
                                            >Cover image
                                            <span class="font-weight-500">
                                                (Optional)</span
                                            >
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div
                                            class="mb-3 input-file-message line-height-0"
                                        >
                                            <img
                                                class="input-file-image"
                                                :src="coverImage.url"
                                                alt="fizzy-cover"
                                                :class="{
                                                    'opacity-0': !coverImage.url
                                                }"
                                            />
                                            <div>
                                                <span
                                                    v-if="coverImage.file"
                                                    class="p-3 bg-white position-absolute right-0 bottom-0"
                                                    v-text="coverImage.name"
                                                ></span>
                                                <span
                                                    v-else
                                                    class="p-2 bg-white"
                                                    >Click here to upload
                                                    file</span
                                                >
                                            </div>
                                            <input
                                                class="d-flex position-absolute h-100 top-0 left-0 w-100 opacity-0"
                                                type="file"
                                                id="cover-image"
                                                @change="onFileChange($event)"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center buttons">
                                    <button
                                        id="clic"
                                        type="submit"
                                        class="btn btn-primary"
                                        :class="{
                                            'btn-progress disabled': isProcessing
                                        }"
                                    >
                                        Save changes
                                    </button>
                                    <button
                                        class="btn btn-secondary"
                                        type="reset"
                                        data-dismiss="modal"
                                        @click="resetForm"
                                    >
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </validation-observer>
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";
import { sweetAlert } from "../../mixins/sweet-alert.js";
import daterangepicker from "daterangepicker";

export default {
    mixins: [sweetAlert],
    data() {
        return {
            fizzyData: [],
            selectedFizzy: {
                title: "",
                description: "",
                token: ""
            },
            coverImage: {
                file: null,
                name: "",
                url: ""
            },

            //datatables
            columns: [
                "selected",
                "title",
                "type",
                "is_published",
                "no_of_questions",
                "total_plays",
                "total_players",
                "created_at",
                "actions"
            ],
            options: {
                skin: "table table-md",
                perPage: 10,
                headings: {
                    title: "title",
                    no_of_questions: "questions",
                    type: "type",
                    total_plays: "plays",
                    total_players: "players",
                    is_published: "is published",
                    created_at: "added on",
                    actions: ""
                },
                listColumns: {
                    type: [],
                    is_published: [
                        { id: "true", text: "Published" },
                        { id: "false", text: "Unpublished" }
                    ]
                },
                perPageValues: [],
                sortable: [
                    "title",
                    "type",
                    "is_published",
                    "no_of_questions",
                    "total_plays",
                    "total_players",
                    "created_at"
                ],
                filterByColumn: true,
                filterable: ["title", "type", "is_published", "created_at"],
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
                let fizzyType = await axios.get("/api/fizzy/type");

                let typelength = fizzyType.data.type;
                for (let index = 0; index < typelength.length; index++) {
                    this.options.listColumns.type.push({
                        text: typelength[index],
                        id: typelength[index]
                    });
                }

                let { data } = await axios.get("/api/fizzy");

                data.fizzy.forEach(field => {
                    this.fizzyData.push(field);
                    this.fizzyData[this.fizzyData.length - 1][
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
                text: "You will not be able to recover this Fizzy",
                icon: "warning",
                showCancelButton: true
            }).then(res => {
                if (res.value) {
                    try {
                        let datas = axios.post("/api/fizzy/delete", {
                            selectedFizzyToken: row.fizzy_token
                        });
                    } catch (error) {
                        console.log(error);
                    }
                    let dsf = this.fizzyData[
                        this.fizzyData.findIndex(
                            el => el.fizzy_token === row.fizzy_token
                        )
                    ];
                    let index = this.fizzyData.findIndex(
                        el => el.fizzy_token === row.fizzy_token
                    );
                    this.fizzyData.splice(index, 1);

                    this.showToast("success", "fizzy deleted successfully");
                }
            });
        },
        setEditFizzy(row) {
            let dsf = this.fizzyData[
                this.fizzyData.findIndex(
                    el => el.fizzy_token === row.fizzy_token
                )
            ];
            let index = this.fizzyData.findIndex(
                el => el.fizzy_token === row.fizzy_token
            );

            this.selectedFizzy = JSON.parse(JSON.stringify(row));
            this.selectedFizzy["index"] = index;

            if (row.hasImage) {
                this.coverImage.url =
                    "/storage/fizzy_cover/" + row.fizzy_token + ".jpg";
            } else {
                this.coverImage.url = row.hasImage;
            }
        },
        onFileChange(event) {
            this.coverImage.file = event.target.files[0];
            this.coverImage.name = this.coverImage.file.name;
            this.coverImage.url = URL.createObjectURL(this.coverImage.file);
        },
        resetForm() {
            this.$nextTick(() => {
                this.$refs.form.reset();
            });

            this.coverImage = { file: null, name: "", url: "" };
            this.fizzy = { title: "", description: "" };
        },
        async onSubmit() {
            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }
            this.isProcessing = true;

            this.fizzyData[
                this.selectedFizzy.index
            ].title = this.selectedFizzy.title;
            this.fizzyData[
                this.selectedFizzy.index
            ].description = this.selectedFizzy.description;

            try {
                let fizzyData = new FormData();
                fizzyData.append("title", this.selectedFizzy.title);
                fizzyData.append("description", this.selectedFizzy.description);
                fizzyData.append("token", this.selectedFizzy.fizzy_token);
                fizzyData.append("image", this.coverImage.file);

                let { data } = await axios.post("/api/fizzy/edit", fizzyData, {
                    headers: { "content-type": "multipart/form-data" }
                });
                console.log(data);
            } catch (error) {
                console.log(error);
            }

            this.isProcessing = false;
            $("#selectedFizzyEdit").modal("hide");

            this.showToast("success", "fizzy updated successfully");
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
                    } catch (error) {
                        console.log(error);
                    }
                }
            });
        },

        async fizzyStatus(row) {
            let index = this.fizzyData.findIndex(
                el => el.fizzy_token === row.fizzy_token
            );

            let newStatus = !this.fizzyData[index].is_published;

            this.fizzyData[index].is_published = newStatus;

            try {
                let data = await axios.post("/api/fizzy/status/edit", {
                    fizzyToken: row.fizzy_token,
                    status: newStatus
                });

                this.showToast(
                    "success",
                    "fizzy " + (newStatus ? "Published" : "Unpublished")
                );
            } catch (error) {
                console.log(error);
            }
        }
    }
};
</script>
