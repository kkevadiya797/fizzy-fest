<template>
    <div>
        <div class="section-header">
            <h1>User Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'dashboard' }">Home</router-link>
                </div>
                <div class="breadcrumb-item">User Profile</div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <!-- <form  class="needs-validation" novalidate> -->
                    <validation-observer ref="form">
                        <form
                            @submit.prevent="changeUserDetails"
                            autocomplete="off"
                        >
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body pb-0">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label
                                                class="form-control-label"
                                                for="cover-image"
                                                >Profile image
                                                <span class="font-weight-500">
                                                    (Optional)</span
                                                >
                                            </label>
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
                                                    <span v-else class="p-2"
                                                        ><i
                                                            class="fas fa-upload"
                                                        ></i
                                                    ></span>
                                                </div>
                                                <input
                                                    class="d-flex position-absolute h-100 top-0 left-0 w-100 opacity-0"
                                                    type="file"
                                                    id="cover-image"
                                                    @change="
                                                        onFileChange($event)
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Username<span class="text-danger"> *</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="userDetails.username"
                                                required
                                                readonly
                                            />
                                        </div>
                                        <div class="form-group">
                                            <label for="name">Name<span class="text-danger"> *</span></label>
                                            <validation-provider
                                                mode="lazy"
                                                rules="required"
                                                #default="{ errors, classes }"
                                            >
                                                <div
                                                    :class="[
                                                        classes,
                                                        {
                                                            'form-error':
                                                                hasError[
                                                                    'message'
                                                                ]
                                                        }
                                                    ]"
                                                >
                                                    <div :class="classes">
                                                        <input
                                                            type="text"
                                                            name="name"
                                                            id="name"
                                                            class="form-control"
                                                            v-model="
                                                                userDetails.name
                                                            "
                                                        />
                                                        <span
                                                            v-if="
                                                                hasError[
                                                                    'message'
                                                                ] && !errors[0]
                                                            "
                                                        >
                                                            {{
                                                                hasError[
                                                                    "message"
                                                                ]
                                                            }}
                                                        </span>
                                                        <span v-else>{{
                                                            errors[0]
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </validation-provider>
                                        </div>
                                        <div class="form-group ">
                                            <label for="email">Email<span class="text-danger"> *</span></label>
                                            <validation-provider
                                                mode="lazy"
                                                rules="required|email"
                                                #default="{ errors, classes }"
                                            >
                                                <div
                                                    :class="[
                                                        classes,
                                                        {
                                                            'form-error':
                                                                hasError[
                                                                    'message'
                                                                ]
                                                        }
                                                    ]"
                                                >
                                                    <div :class="classes">
                                                        <input
                                                            type="text"
                                                            name="email"
                                                            id="email"
                                                            class="form-control"
                                                            v-model="
                                                                userDetails.email
                                                            "
                                                            readonly
                                                        />
                                                        <span
                                                            v-if="
                                                                hasError[
                                                                    'message'
                                                                ] && !errors[0]
                                                            "
                                                        >
                                                            {{
                                                                hasError[
                                                                    "message"
                                                                ]
                                                            }}
                                                        </span>
                                                        <span v-else>{{
                                                            errors[0]
                                                        }}</span>
                                                    </div>
                                                </div>
                                            </validation-provider>
                                        </div>
                                        <div class="form-group">
                                            <label>User Type<span class="text-danger"> *</span></label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                v-model="userDetails.type"
                                                readonly
                                            />
                                        </div>
                                        <div class="text-center pb-4">
                                            <button
                                                class="btn btn-primary"
                                                type="submit"
                                                :class="{
                                                    'btn-progress disabled': isProcessing
                                                }"
                                            >
                                                Save Changes
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </validation-observer>
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";
import { sweetAlert } from "../../mixins/sweet-alert.js";
import Loading from "vue-loading-overlay";

export default {
    mixins: [sweetAlert],
    components: {
        ValidationProvider,
        ValidationObserver,
        Loading
    },
    data() {
        return {
            coverImage: {
                file: null,
                name: "",
                url: ""
            },
            userDetails: "",
            hasError: {},
            isProcessing: false,
            isLoading: false
        };
    },
    mounted() {
        this.userDetail();
    },
    methods: {
        onFileChange(event) {
            this.coverImage.file = event.target.files[0];
            this.coverImage.name = this.coverImage.file.name;
            this.coverImage.url = URL.createObjectURL(this.coverImage.file);
        },
        async userDetail() {
            this.hasError = {};

            this.isLoading = true;
            try {
                let userData = await axios.get("/api/detail");
                this.userDetails = userData.data.userDetail;

                if (this.userDetails.hasImage) {
                    this.coverImage.url =
                        "/storage/avatar/" + this.userDetails.id + ".jpg";
                    this.coverImage.file = true;
                    this.coverImage.name = this.userDetails.username;
                }
            } catch (e) {
                console.log(e);
            }
            this.isLoading = false;
        },

        async changeUserDetails() {
            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }

            this.isProcessing = true;
            try {
                let userData = new FormData();
                userData.append("name", this.userDetails.name);
                userData.append("image", this.coverImage.file);

                let { data } = await axios.post("/api/changeDetail", userData, {
                    headers: { "content-type": "multipart/form-data" }
                });

                this.$refs.form.reset();
                this.showToast("success", "Profile updated successfully");
            } catch (e) {
                let res = e.response;
                if (res.status === 422) {
                    this.hasError = res.data.errors;
                }
            }
            this.isProcessing = false;
        }
    }
};
</script>

<style scoped>
.input-file-message {
    position: relative;
    z-index: 999;
    padding: 5rem 1rem;
    transition: all 0.15s ease;
    text-align: center;
    color: #b9c1d1;
    border: 1px dashed #dbe3ef;
    border-radius: 0.375rem;
    background-color: #fdfdff;
    order: -1;
    overflow: hidden;
}

.input-file-message input {
    cursor: pointer;
}

.input-file-image {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    max-width: 600px;
    z-index: -1;
}
</style>
