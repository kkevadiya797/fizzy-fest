<template>
    <div>
        <div class="p-4 d-flex justify-content-between">
            <div>
                <h2 class="text-brand m-0 text-white font-weight-normal">
                    FizzyFest
                </h2>
            </div>
        </div>
        <div class="form-login m-auto">
            <div class="px-3 py-5">
                <div class="card">
                    <validation-observer ref="form">
                        <form @submit.prevent="sendMail">
                            <div class="card-header d-block">
                                <h3 class="text-center">Change password</h3>
                            </div>
                            <div class="card-body pb-0">
                                <div class="form-group">
                                    <label for="new_password"
                                        >New password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <validation-provider
                                        mode="lazy"
                                        rules="required|min:6"
                                        #default="{ errors, classes }"
                                        vid="pwd"
                                    >
                                        <div :class="classes">
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="new_password"
                                                name="new password"
                                                placeholder="New password"
                                                v-model="newPassword"
                                                autofocus
                                            />
                                            <span
                                                class="small text-muted d-block"
                                                >* password requires at list 6
                                                letter
                                            </span>
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </validation-provider>
                                </div>
                                <div class="form-group">
                                    <label for="con_password"
                                        >Confirm password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <validation-provider
                                        mode="lazy"
                                        rules="required|min:6|confirmed:pwd"
                                        #default="{ errors, classes }"
                                    >
                                        <div :class="classes">
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="con_password"
                                                name="confirm password"
                                                placeholder="Confirm password"
                                                v-model="confirmPassword"
                                                autofocus
                                            />
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </validation-provider>
                                </div>
                            </div>
                            <div class="card-footer text-center pt-0">
                                <button type="submit" class="btn btn-primary">
                                    Reset password
                                </button>
                            </div>
                        </form>
                    </validation-observer>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";

export default {
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            email: ""
        };
    },
    methods: {
        sendMail() {
            this.$refs.form.validate().then(success => {
                if (!success) {
                    return;
                }
            });
        }
    }
};
</script>
