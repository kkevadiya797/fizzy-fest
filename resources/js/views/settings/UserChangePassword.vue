<template>
    <div>
        <div class="section-header">
            <h1>Change Ppassword</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'dashboard' }">Home</router-link>
                </div>
                <div class="breadcrumb-item">Change password</div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card m-0 mb-3">
                    <div class="card-header">
                        <h4>Change password</h4>
                    </div>
                    <div class="card-body">
                        <validation-observer ref="form">
                            <form
                                @submit.prevent="changeUserPassword"
                                autocomplete="off"
                            >
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="old_pwd"
                                                >Old Password<span class="text-danger"> *</span></label
                                            >
                                            <validation-provider
                                                mode="lazy"
                                                rules="required|min:6"
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
                                                            type="password"
                                                            name="old password"
                                                            placeholder="Old password"
                                                            id="old_pwd"
                                                            class="form-control"
                                                            v-model="
                                                                oldPassword
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
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="new_pwd"
                                                >New Password<span class="text-danger"> *</span></label
                                            >
                                            <validation-provider
                                                mode="lazy"
                                                rules="required|min:6"
                                                #default="{ errors, classes }"
                                                vid="pwd"
                                            >
                                                <div :class="[classes]">
                                                    <div :class="classes">
                                                        <input
                                                            type="password"
                                                            name="new password"
                                                            placeholder="New password"
                                                            id="new_pwd"
                                                            class="form-control"
                                                            v-model="
                                                                newPassword
                                                            "
                                                        />
                                                        <span>{{
                                                            errors[0]
                                                        }}</span>
                                                        <span
                                                            class="small text-muted d-block"
                                                            >* password requires
                                                            at list 6 letter
                                                        </span>
                                                    </div>
                                                </div>
                                            </validation-provider>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="con_pwd"
                                                >Confirm Password<span class="text-danger"> *</span></label
                                            >
                                            <validation-provider
                                                mode="lazy"
                                                rules="required|min:6|confirmed:pwd"
                                                #default="{ errors, classes }"
                                            >
                                                <div :class="classes">
                                                    <input
                                                        type="password"
                                                        name="confirm password"
                                                        id="con_pwd"
                                                        placeholder="Confirm password"
                                                        class="form-control"
                                                        v-model="
                                                            confirmPassword
                                                        "
                                                    />
                                                    <span>{{ errors[0] }}</span>
                                                </div>
                                            </validation-provider>
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
                                        Save password
                                    </button>
                                    <button
                                        type="reset"
                                        class="btn btn-secondary"
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
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";
import { sweetAlert } from "../../mixins/sweet-alert.js";

export default {
    mixins: [sweetAlert],
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            oldPassword: null,
            newPassword: null,
            confirmPassword: null,
            isProcessing: false,
            hasError: {}
        };
    },
    methods: {
        async changeUserPassword() {
            this.hasError = {};

            let success = await this.$refs.form.validate();
            if (!success) {
                return;
            }

            this.isProcessing = true;
            try {
                let userData = await axios.post("/api/changePassword", {
                    oldPassword: this.oldPassword,
                    newPassword: this.newPassword,
                    confirmPassword: this.confirmPassword
                });
            } catch (e) {
                let res = e.response;
                if (res.status === 422 || res.status === 400) {
                    this.hasError = res.data.errors;
                }
            }
            this.resetForm();

            this.showToast("success", "Password changed successfully");
            
            this.isProcessing = false;
        },
        resetForm() {
            this.$nextTick(() => {
                this.$refs.form.reset();
            });

            this.oldPassword = "";
            this.newPassword = "";
            this.confirmPassword = "";
        }
    }
};
</script>
