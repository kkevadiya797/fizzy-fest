<template>
    <div>
        <div class="section-header">
            <h1>Delete account</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'dashboard' }">Home</router-link>
                </div>
                <div class="breadcrumb-item">Delete account</div>
            </div>
        </div>
        <div class="col-12 col-md-8 mx-auto p-0">
            <div class="card my-3">
                <div class="card-header">
                    <h4>Delete Account</h4>
                </div>
                <div class="card-body">
                    <validation-observer ref="form">
                        <form @submit.prevent="showAlert" autocomplete="off">
                            <div>
                                <p>
                                    If we can't change your mind, we'd
                                    appreciate you letting us know why you're
                                    leaving us.
                                </p>
                            </div>
                            <div class="form-group">
                                <label>Reason<span class="text-danger"> *</span></label>
                                <validation-provider
                                    mode="lazy"
                                    rules="required"
                                    #default="{ errors, classes }"
                                >
                                    <div :class="classes">
                                        <select
                                            id="reason"
                                            name="reason"
                                            class="form-control"
                                            v-model="reason"
                                        >
                                            <option value="" disabled selected>select a reason</option>
                                            <option
                                                >I have anther account i want to
                                                use</option
                                            >
                                            <option
                                                >I want a clean profile / start
                                                fresh</option
                                            >
                                            <option
                                                >I throught i needed an account
                                                to play</option
                                            >
                                            <option
                                                >It's missing a features i
                                                need</option
                                            >
                                            <option>It doesn't work!</option>
                                            <option>I just don't use</option>
                                            <option
                                                >I wasn't what i throught it
                                                was</option
                                            >
                                            <option>other</option>
                                        </select>

                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                            <div class="text-center buttons">
                                <button type="submit" class="btn btn-primary">
                                    Delete my account
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Cancel
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
import { sweetAlert } from "../../mixins/sweet-alert.js";
import { ValidationProvider, ValidationObserver } from "vee-validate";

export default {
    mixins: [sweetAlert],
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            reason: ""
        };
    },
    methods: {
        async showAlert() {
            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }
            this.$swal({
                title: "Are you sure?",
                text: "You will not be able to recover this account",
                icon: "info",
                showCancelButton: true
            }).then(res => {
                if (res.value) {
                    try {
                        let data = axios.post("/api/deleteAccount", {
                            reason: this.reason
                        });

                        this.showToast(
                            "success",
                            "Deleted account successfully"
                        );
                        localStorage.removeItem("auth/isAuthenticated");
                        this.$router.push({ name: "login" });
                    } catch (error) {
                        console.log(error);
                    }

                }
            });
        }
    }
};
</script>
