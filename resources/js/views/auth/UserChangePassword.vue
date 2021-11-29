<template>
    <div class="row login-cover">
        <div class="col-12 login-form">
            <div class="form-login m-auto px-3 py-5">
                <div class="mb-5 text-center">
                    <a href="/home">
                        <img
                            src="/assets/images/logo/fizzyfest-logo.png"
                            alt="fizzyfest-logo"
                            width="120px"
                        />
                    </a>
                </div>
                <div>
                    <p class="welcom mb-3 text-success">
                        Change password
                    </p>
                    <p class="welcom-info mb-4">
                        Here, We will change password for you.
                    </p>
                </div>
                <validation-observer ref="form">
                    <form @submit.prevent="submit">
                        <div class="py-3">
                            <div class="form-group">
                                <label for="email"
                                    >New Password
                                    <span class="text-danger"
                                        >*</span
                                    ></label
                                >
                                <validation-provider
                                    mode="lazy"
                                    rules="required|min:6"
                                    #default="{ errors, classes }"
                                    vid="pwd"
                                >
                                        <div
                                        :class="[
                                            classes,
                                            {
                                                'form-error':
                                                    hasError['message']
                                            }
                                        ]"
                                    >
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="newPassword"
                                            name="new password"
                                            v-model="userData.newPassword"
                                            placeholder="password"
                                            autofocus
                                            autocomplete="nope"
                                        />
                                            <span
                                            v-if="
                                                hasError['message'] &&
                                                    !errors[0]
                                            "
                                        >
                                            {{ hasError["message"] }}
                                        </span>
                                        <span v-else>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                            <div class="form-group mb-0">
                                <label for="password"
                                    >Confirm Password
                                    <span class="text-danger"
                                        >*</span
                                    ></label
                                >
                                <validation-provider
                                    mode="lazy"
                                    rules="required|min:6|confirmed:pwd"
                                    #default="{ errors, classes }"
                                >
                                    <div
                                        :class="[
                                            classes,
                                            {
                                                'form-error':
                                                    hasError['message']
                                            }
                                        ]"
                                    >
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="confirmPassword"
                                            name="confirm password"
                                            v-model="userData.confirmPassword"
                                            placeholder="confirm Password"
                                            autocomplete="newpassword"
                                        />
                                        <span
                                            v-if="
                                                hasError['message'] &&
                                                    !errors[0]
                                            "
                                        >
                                            {{ hasError["message"] }}
                                        </span>
                                        <span v-else>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                        </div>
                        <div class="text-center">
                            <button
                                type="submit"
                                class="btn btn-block btn-primary"
                                :class="{
                                    'btn-progress disabled': isProcessing
                                }"
                            >
                                Change password
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

export default {
    mixins: [sweetAlert],
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            userData:{
                newPassword:"",
                confirmPassword:"",
                id:this.$route.params.id
            },
            hasError: {},
            isProcessing: false
        };
    },
    methods: {
        async submit() {
            this.hasError = {};

            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }
        
            this.isProcessing = true;
            try {
                let { data } = await axios.post("/api/forgotpassword/store",this.userData);
                console.log(data);

                this.showToast('success','Change password successfully');
                this.$router.push({ name: "login" });

            } catch (e) {
                let res = e.response;
                if (res.status === 422 || res.status === 401) {
                    this.hasError = res.data.errors;
                }
            }
            this.isProcessing = false;
        }
    }
};
</script>

<style scoped>
.login-cover {
    height: 100vh;
    min-height: 550px;
    background-color: white;
}
.cover-hero {
    background-image: url("/assets/images/cover/cover_1200.svg");
}
.cover-hero::before {
    position: absolute;
    top: 0;
    left: -100px;
    content: "";
    background-image: url("/assets/images/cover/left_side.svg");
    width: 100px;
    height: 100px;
}
.cover-hero,
.cover-hero::before {
    background-color: #1eca91;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: right;
    transition: 0.3s;
}
.cover-hero::after {
    position: absolute;
    bottom: 0;
    left: 0;
    content: "";
    background-image: url("/assets/images/cover/right_side.svg");
    width: 100px;
    height: 100px;
    background-size: cover;
    background-repeat: no-repeat;
}
.bg-facebook,
.bg-facebook::before {
    background-color: #00a1ff;
}
.bg-twitter,
.bg-twitter::before {
    background-color: #00daff;
}
.bg-instagram,
.bg-instagram::before {
    background-color: #ff5c97;
}
.login-form {
    display: flex;
    align-content: center;
    flex-flow: nowrap;
}
h1 {
    font-size: 2rem;
}
.display-1 {
    font-size: 2.5rem;
    font-weight: 600;
    margin-top: 90px;
}
.display-p {
    font-size: 1.1rem;
    padding-top: 0 !important;
}
.display-1,
.display-p {
    color: white;
    letter-spacing: 1px;
    padding: 30px 60px;
}
.welcom {
    font-size: 18px;
    font-weight: 500;
}
.welcom-info {
    font-size: 14px;
    color: #b9c1d1 !important;
    line-height: 22px;
}
.follow-us {
    position: absolute;
    top: 65%;
    right: 0;
    width: 250px;
    height: 200px;
    background-image: url(/assets/images/cover/message.svg);
    background-repeat: no-repeat;
    background-size: contain;
}
.follow-us div {
    position: absolute;
    top: 80px;
    left: 60px;
}
.follow-us span {
    position: absolute;
    top: 40px;
    left: 50%;
    transform: translateX(-50%);
    font-weight: 500;
    color: #949eb2;
}
.follow-us a {
    display: inline-block;
    text-align: center;
    margin: 0 10px;
    transition: all 0.4s ease-out 0s;
}
.follow-us i {
    font-size: 20px;
    color: #949eb2;
}
.follow-us #facebook:hover i {
    color: #00a1ff;
}
.follow-us #twitter:hover i {
    color: #00daff;
}
.follow-us #instagram:hover i {
    color: #ff5c97;
}
footer {
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    color: white;
}
</style>
