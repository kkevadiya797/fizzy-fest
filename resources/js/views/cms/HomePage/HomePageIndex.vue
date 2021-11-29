<template>
    <div>
        <div class="row">
            <div class="col-12 col-sm-3 border-right">
                <ul class="nav nav-pills flex-column" role="tablist">
                    <li
                        v-for="(tab, index) in contents"
                        :key="tab"
                        class="nav-item"
                        @click="currentTabComponent = tab"
                    >
                        <a
                            v-if="data.components[tab].status"
                            class="nav-link"
                            :class="{ active: index == 0 }"
                            style="text-transform: capitalize;"
                            :id="tab + '-tab'"
                            data-toggle="tab"
                            :href="'#' + tab"
                            role="tab"
                            :aria-controls="tab"
                            :aria-selected="index == 0 ? true : false"
                        >
                            <i class="mr-2" :class="iconClass[tab]"></i>
                            {{ tab | tabName }}
                        </a>
                    </li>
                </ul>
            </div>
            <div v-if="data" class="col-12 col-sm-9">
                <div class="tab-content no-padding" id="myTab2Content">
                    <div class="tab-pane fade show active">
                        <transition name="fade" appear mode="out-in">
                            <component
                                :is="currentTabComponent"
                                :componentType="currentTabComponent"
                                :content="data.contents[currentTabComponent]"
                                :onSubmit="onSubmit"
                                :resetForm="resetForm"
                                :onFileChange="onFileChange"
                                :deleteContent="deleteContent"
                                :tableOptions="tableOptions"
                            ></component>
                        </transition>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { sweetAlert } from "../../../mixins/sweet-alert.js";
// home-page
import Hero from "./components/Hero.vue";
import FizzyfestWork from "./components/FizzyfestWork.vue";
import FizzyfestAt from "./components/FizzyfestAt.vue";
import ClientSay from "./components/ClientSay.vue";
import SignupCard from "./components/SignupCard.vue";

export default {
    mixins: [sweetAlert],
    components: {
        hero: Hero,
        fizzyfest_work: FizzyfestWork,
        fizzyfest_at: FizzyfestAt,
        client_say: ClientSay,
        signup_card: SignupCard
    },
    data() {
        return {
            data: null,
            contents: [],
            iconClass: {
                hero: "fas fa-image",
                fizzyfest_work: "fas fa-gamepad",
                fizzyfest_at: "fas fa-map-marker-alt",
                client_say: "fas fa-quote-right",
                signup_card: "fas fa-marker"
            },
            currentTabComponent: "hero",
            // data table
            tableOptions: {
                headings: {
                    title: "title",
                    sub_title: "sub title",
                    description: "description",
                    image_url: "image",
                    link_url: "link",
                    status: "status",
                    created_at: "Added on",
                    actions: ""
                },
                sortable: [
                    "title",
                    "sub_title",
                    "description",
                    "status",
                    "created_at"
                ],
                columnsClasses: {
                    title: "th-min-200",
                    description: "th-min-300",
                    actions: "float-right"
                },
                skin: "table table-md",
                perPageValues: [],
                perPage: 10,
                filterable: false
            }
        };
    },
    mounted() {
        this.loadData();
    },
    filters: {
        tabName(value) {
            return value.replace(/_/g, " ");
        }
    },
    methods: {
        async loadData() {
            let { data } = await axios.get("/api/theme/1");
            this.data = data;

            this.contents = Object.keys(data.contents);
        },
        deleteContent(content, row) {
            this.$swal({
                title: "Are you sure?",
                text: "You will not be able to recover this content",
                icon: "warning",
                showCancelButton: true
            }).then(async response => {
                if (response.value) {
                    try {
                        await axios.post("/api/theme/delete/" + row.id, {
                            componentType: this.componentType
                        });
                        let index = content.findIndex(el => el.id === row.id);
                        content.splice(index, 1);
                        this.showToast("success", "Content deleted");
                    } catch (error) {
                        console.log(error);
                        this.showToast("error", "Oops! something went wrong");
                    }
                }
            });
        },
        onFileChange(themeData, event) {
            themeData.fileData = event.target.files[0];
            themeData.image_url = themeData.fileData.name;
        },
        resetForm(themeData) {
            themeData.id = null;
            themeData.title = "";
            themeData.sub_title = "";
            themeData.description = "";
            themeData.status = false;
            themeData.image_url = "";
            themeData.link_url = "";
            themeData.fileData = null;
        },
        async onSubmit(componentType, themeData, content, hasEdit, reset) {
            try {
                let data = new FormData();

                data.append("componentType", componentType);

                Object.entries(themeData).forEach(el => {
                    data.append(el[0], themeData[el[0]]);
                });
                await axios
                    .post("/api/theme/store", data, {
                        headers: { "content-type": "multipart/form-data" }
                    })
                    .then(({ data }) => {
                        if (hasEdit === false) {
                            themeData.id = data;
                            content.push(
                                Object.fromEntries(
                                    Object.entries(themeData).filter(function(
                                        el
                                    ) {
                                        return el[0] != "fileData";
                                    })
                                )
                            );
                        } else {
                            content.splice(
                                hasEdit,
                                1,
                                Object.fromEntries(
                                    Object.entries(themeData).filter(function(
                                        el
                                    ) {
                                        return el[0] != "fileData";
                                    })
                                )
                            );
                        }
                        this.showToast(
                            "success",
                            hasEdit === false
                                ? "Content added"
                                : "Content edited"
                        );
                    });
                reset();
            } catch (error) {
                console.log(error);
                this.showToast("error", "Oops! something went wrong");
            }
        }
    }
};
</script>

<style scoped>
.border-right {
    border-right: 1px solid #f9f9f9 !important;
}
.fade-enter {
    opacity: 0;
}
.fade-enter-active {
    transition: opacity 0.15s;
}
.fade-leave-active {
    transition: 0.15s;
    opacity: 0;
}
</style>
