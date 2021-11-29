<template>
    <div class="px-3 py-2">
        <div class="mb-5">
            <validation-observer ref="form">
                <form @submit.prevent="submit">
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="title">
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
                                            v-model="themeData.title"
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
                                    <span class="text-danger"> *</span>
                                </label>
                                <validation-provider
                                    mode="lazy"
                                    rules="required"
                                    #default="{ errors, classes }"
                                >
                                    <div :class="classes">
                                        <textarea
                                            class="form-control"
                                            id="description"
                                            name="Description"
                                            placeholder="Description"
                                            v-model="themeData.description"
                                            rows="3"
                                        ></textarea>
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label
                                    class="form-control-label"
                                    for="hero-image"
                                    >Hero image
                                    <span class="text-danger"> *</span>
                                </label>
                                <div class="custom-file">
                                    <input
                                        type="file"
                                        class="custom-file-input form-control"
                                        id="hero-image"
                                        name="Hero image"
                                        @change="
                                            onFileChange(themeData, $event)
                                        "
                                    />
                                    <label
                                        class="custom-file-label font-weight-light"
                                        for="customFile"
                                    >
                                        {{
                                            themeData.image_url
                                                ? themeData.image_url
                                                : "Choose file"
                                        }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-control-label" for="link">
                                    Link
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
                                            id="link"
                                            name="Link"
                                            placeholder="Link"
                                            v-model="themeData.link_url"
                                        />
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        :id="'status-' + componentType"
                                        v-model="themeData.status"
                                    />
                                    <label
                                        class="custom-control-label"
                                        :for="'status-' + componentType"
                                        >Active</label
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-center buttons">
                        <button
                            v-if="hasEdit === false"
                            class="btn btn-primary"
                            :class="{
                                'btn-progress disabled': isProcessing
                            }"
                        >
                            Add
                        </button>
                        <button
                            v-else
                            type="submit"
                            class="btn btn-primary"
                            :class="{
                                'btn-progress disabled': isProcessing
                            }"
                        >
                            Edit
                        </button>
                        <button
                            class="btn btn-secondary"
                            type="reset"
                            @click="reset()"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </validation-observer>
        </div>

        <div>
            <h4>Component contents</h4>
            <v-client-table
                :data="content"
                :columns="tableColumns"
                :options="tableOptions"
                ref="table"
            >
                <template slot="image_url" slot-scope="{ row }">
                    <img
                        :src="
                            '/storage/web/' +
                                componentType +
                                '/' +
                                row.image_url
                        "
                        alt="hero-image"
                    />
                </template>
                <template slot="status" slot-scope="{ row }">
                    <div style="margin-left: 25px;">
                        <i
                            v-if="row.status"
                            class="fas fa-check text-success"
                        ></i>
                        <i v-else class="fas fa-times text-danger"></i>
                    </div>
                </template>
                <div slot="actions" slot-scope="{ row }" class="d-flex icons">
                    <a href="#top">
                        <i
                            class="fas fa-pen text-success-hover"
                            title="Edit"
                            data-toggle="modal"
                            data-target="#selectedFizzyEdit"
                            @click="editContent(row)"
                        ></i>
                    </a>
                    <i
                        class="fas fa-trash text-danger-hover"
                        title="Delete"
                        @click="deleteContent(content, row)"
                    ></i>
                </div>
            </v-client-table>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../../../vee-validation.js";
import { sweetAlert } from "../../../../mixins/sweet-alert.js";
import "vue-simple-suggest/dist/styles.css";

export default {
    mixins: [sweetAlert],
    components: {
        ValidationProvider,
        ValidationObserver
    },
    props: {
        componentType: { require: true },
        content: { require: true, type: Array },
        onSubmit: { require: true },
        resetForm: { require: true },
        onFileChange: { require: true },
        deleteContent: { require: true },
        tableOptions: { require: true, type: Object }
    },
    data() {
        return {
            themeData: {
                id: null,
                title: "",
                sub_title: "",
                description: "",
                status: false,
                image_url: "",
                link_url: "",
                fileData: null
            },
            isProcessing: false,
            hasEdit: false,
            // data table
            tableColumns: [
                "image_url",
                "title",
                "description",
                "link_url",
                "status",
                "actions"
            ]
        };
    },
    methods: {
        reset() {
            this.$nextTick(() => {
                this.$refs.form.reset();
            });

            this.resetForm(this.themeData);
            this.hasEdit = false;
        },
        async submit() {
            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }
            this.isProcessing = true;
            this.onSubmit(
                this.componentType,
                this.themeData,
                this.content,
                this.hasEdit,
                this.reset
            );
            this.isProcessing = false;
        },
        editContent(row) {
            this.hasEdit = this.content.findIndex(el => el.id === row.id);
            this.themeData = { ...this.content[this.hasEdit] };
        }
    }
};
</script>
