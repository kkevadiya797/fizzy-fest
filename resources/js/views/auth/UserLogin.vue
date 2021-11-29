<template>
    <div class="row login-cover">
        <div class="col-12 col-md-5 login-form">
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
                    <!-- <h1 class="mb-3">Get Started</h1> -->
                    <p class="welcom mb-3 text-success">Hi, Welcome back!</p>
                    <p class="welcom-info mb-4">
                        It's nice to see you again, How was your day? Let's get
                        started.
                    </p>
                </div>
                <validation-observer ref="form">
                    <form @submit.prevent="login" autocomplete="off">
                        <div class="py-3">
                            <div class="form-group">
                                <label for="email"
                                    >Email
                                    <span class="text-danger">*</span></label
                                >
                                <validation-provider
                                    mode="lazy"
                                    rules="required|email"
                                    #default="{ errors, classes }"
                                >
                                    <div :class="classes">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="email"
                                            placeholder="Email"
                                            v-model="loginData.email"
                                            autofocus
                                            autocomplete="nope"
                                        />
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                            <div class="form-group">
                                <label for="password"
                                    >Password
                                    <span class="text-danger">*</span></label
                                >
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
                                                    hasError['message']
                                            }
                                        ]"
                                    >
                                        <input
                                            type="password"
                                            class="form-control"
                                            id="password"
                                            placeholder="Password"
                                            v-model="loginData.password"
                                            autocomplete="new-password"
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
                            <div class="mb-1 text-right">
                                <router-link :to="{ name: 'forgotpassword' }"
                                    >Forget password?</router-link
                                >
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
                                Login
                            </button>
                            <router-link
                                :to="{ name: 'register' }"
                                class="btn btn-block btn-outline-dark"
                                >Free register</router-link
                            >
                        </div>
                    </form>
                </validation-observer>
            </div>
        </div>
        <div class="col-7 cover-hero d-none d-md-block">
            <div>
                <h1 class="display-1">Welcome to Fizzyfest</h1>
                <p class="display-p">
                    Join us and create your fizzies and play with your
                    colleagues to improve your knowledge.
                </p>
            </div>
            <div class="follow-us">
                <span>Follow us</span>
                <div>
                    <a href="#" id="facebook"
                        ><i class="fab fa-facebook-f"></i
                    ></a>
                    <a href="#" id="twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" id="instagram"
                        ><i class="fab fa-instagram"></i
                    ></a>
                </div>
            </div>
            <footer>
                <p>
                    Copyright &copy; 2020 by Fizzyfest
                </p>
            </footer>
        </div>
    </div>
</template>

<script>
import Axios from "axios";
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
            loginData: {
                email: "",
                password: ""
            },
            hasError: {},
            isProcessing: false
        };
    },
    created() {
        $(function() {
            hoverIcon("facebook");
            hoverIcon("twitter");
            hoverIcon("instagram");
        });
        function hoverIcon(id) {
            $("#" + id).hover(function() {
                $(".cover-hero").toggleClass("bg-" + id);
            });
        }
    },
    methods: {
        async login() {
            this.hasError = {};

            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }

            this.isProcessing = true;
            try {
                let userLogin = await axios.post("/api/login", this.loginData);
                let userData = userLogin.data;

                if (userData.access_token) {
                    localStorage.setItem(
                        "auth/isAuthenticated",
                        userData.access_token
                    );

                    let isAuthenticated = localStorage.getItem(
                        "auth/isAuthenticated"
                    );

                    if (isAuthenticated) {
                        Axios.defaults.headers.common[
                            "Authorization"
                        ] = `Bearer ${isAuthenticated}`;
                    }

                    await this.$store.dispatch("getUser");

                    if (this.$store.getters.activeUserRole == 1) {
                        this.$router.push({ name: "admin.dashboard" });
                    } else {
                        this.$router.push({ name: "dashboard" });
                    }
                }
                if (userLogin.status === 200) {
                    this.showToast("success", "Login successfully");
                }
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
    background-color: #ffc05c;
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
