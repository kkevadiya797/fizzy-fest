<template>
    <div>
        <div class="section-header">
            <h1>Create fizzy</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'dashboard' }">Home</router-link>
                </div>
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'fizzies.index' }"
                        >Fizzies</router-link
                    >
                </div>
                <div class="breadcrumb-item">Create fizzy</div>
            </div>
        </div>
        <div class="card w-max-1200 m-auto">
            <div class="card-header">
                <h4 class="mb-0">Fizzy summary</h4>
            </div>
            <div class="card-body">
                <validation-observer ref="form">
                    <form @submit.prevent="onSubmit">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label
                                        class="form-control-label"
                                        for="title"
                                    >
                                        Title
                                        <span class="text-danger"> *</span>
                                    </label>
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
                                                v-model="fizzy.title"
                                            />
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </validation-provider>
                                </div>
                                <div class="form-group">
                                    <label
                                        class="form-control-label"
                                        for="description"
                                        >Description
                                        <span class="font-weight-500">
                                            (Optional)</span
                                        >
                                    </label>
                                    <textarea
                                        class="form-control"
                                        id="description"
                                        placeholder="Description"
                                        v-model="fizzy.description"
                                        rows="3"
                                    ></textarea>
                                </div>
                                <div class="form-group">
                                    <label
                                        class="form-control-label"
                                        for="description"
                                        >Type
                                        <span class="text-danger"> *</span>
                                    </label>
                                    <validation-provider
                                        mode="lazy"
                                        rules="required"
                                        #default="{ errors, classes }"
                                    >
                                        <div :class="classes">
                                            <vue-simple-suggest
                                                :list="simpleSuggestionList"
                                                :styles="autoCompleteStyle"
                                                v-model="fizzy.type"   
                                                :filter-by-query="true"
                                            >
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="type"
                                                    name="type"
                                                    placeholder="Fizzy type"
                                                    autocomplete="off"
                                                />
                                            </vue-simple-suggest>
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </validation-provider>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label
                                        class="form-control-label"
                                        for="cover-image"
                                        >Cover image
                                        <span class="font-weight-500">
                                            (Optional)</span
                                        >
                                    </label>
                                    <div
                                        class="input-file-message line-height-0"
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
                                            <span v-else class="p-2"
                                                >Click here to upload file</span
                                            >
                                        </div>
                                        <input
                                            class="d-flex position-absolute h-100 top-0 left-0 w-100 opacity-0"
                                            type="file"
                                            id="cover-image"
                                            @change="onFileChange($event)"
                                        />
                                        <button v-if="coverImage.url" type="button" 
                                            class="btn btn-icon btn-danger position-absolute top-0 right-0"
                                            @click="fizzyImgDelete()"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center buttons">
                            <button
                                type="submit"
                                class="btn btn-primary"
                                :class="{
                                    'btn-progress disabled': isProcessing
                                }"
                            >
                                Create
                            </button>
                            <button
                                class="btn btn-secondary"
                                type="reset"
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
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";
import { sweetAlert } from "../../mixins/sweet-alert.js";
import VueSimpleSuggest from "vue-simple-suggest";
import "vue-simple-suggest/dist/styles.css";

export default {
    mixins: [sweetAlert],
    components: {
        ValidationProvider,
        ValidationObserver,
        VueSimpleSuggest
    },
    data() {
        return {
            autoCompleteStyle: {
                vueSimpleSuggest: "position-relative",
                inputWrapper: "",
                defaultInput: "form-control",
                suggestions: "position-absolute list-group z-1000",
                suggestItem: "list-group-item"
            },
            simpleSuggestionList: [],
            fizzy: {
                title: "",
                description: "",
                type: ""
            },
            coverImage: {
                file: null,
                name: "",
                url: ""
            },
            isProcessing: false
        };
    },
    mounted() {
        this.getType();
    },
    methods: {
        async getType() {
            try {
                let { data } = await axios.get("/api/fizzy/type");

                this.simpleSuggestionList = data.type;
            } catch (error) {
                console.log(error);
            }
        },
        // simpleSuggestionList() {
        //     return ["IT", "Computer Graphics", "General"];
        // },
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
            try {
                let fizzyData = new FormData();
                fizzyData.append("title", this.fizzy.title);
                fizzyData.append("description", this.fizzy.description);
                fizzyData.append("type", this.fizzy.type);
                fizzyData.append("image", this.coverImage.file);

                let newFizzy = await axios.post("/api/fizzy/store", fizzyData, {
                    headers: { "content-type": "multipart/form-data" }
                });
                this.showToast("success", "fizzy created successfully");

                this.$router.push({
                    name: "fizzies.view",
                    params: { token: newFizzy.data.token }
                });
            } catch (e) {
                console.log(e);
            }
            this.isProcessing = false;
        },
        fizzyImgDelete(){
            this.coverImage = { file: null, name: "", url: "" };
        }
    }
};
</script>
