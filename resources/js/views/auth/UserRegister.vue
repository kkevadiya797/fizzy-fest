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
                    <p class="welcom mb-3 text-info">Hi, there. New here?</p>
                    <p class="welcom-info mb-4">
                        Here, register here for free and create fizzy and play
                        with your friends.
                    </p>
                </div>
                <validation-observer ref="form">
                    <form @submit.prevent="register" autocomplete="off">
                        <div class="py-3">
                            <div class="form-group">
                                <label for="username">
                                    Username
                                    <span class="text-danger">*</span>
                                </label>
                                <validation-provider
                                    mode="lazy"
                                    rules="required|alpha_dash"
                                    #default="{ errors, classes }"
                                >
                                    <div
                                        :class="[
                                            classes,
                                            {
                                                'form-error':
                                                    hasError['username']
                                            }
                                        ]"
                                    >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="username"
                                            placeholder="Username"
                                            v-model="registerData.username"
                                            autofocus
                                            autocomplete="nope"
                                        />
                                        <span
                                            v-if="
                                                hasError['username'] &&
                                                    !errors[0]
                                            "
                                        >
                                            {{ hasError["username"][0] }}
                                        </span>
                                        <span v-else>{{ errors[0] }}</span>
                                        <span class="small text-muted d-block"
                                            >* username may contain
                                            alpha-numeric letter as well as
                                            dashes and underscores
                                        </span>
                                    </div>
                                </validation-provider>
                            </div>
                            <div class="form-group">
                                <label for="email"
                                    >Email
                                    <span class="text-danger">*</span>
                                </label>
                                <validation-provider
                                    mode="lazy"
                                    rules="required|email"
                                    #default="{ errors, classes }"
                                >
                                    <div
                                        :class="[
                                            classes,
                                            {
                                                'form-error': hasError['email']
                                            }
                                        ]"
                                    >
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="email"
                                            name="email"
                                            placeholder="Email"
                                            v-model="registerData.email"
                                            autofocus
                                        />
                                        <span
                                            v-if="
                                                hasError['email'] && !errors[0]
                                            "
                                        >
                                            {{ hasError["email"][0] }}
                                        </span>
                                        <span v-else>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-sm-6">
                                    <label for="pwd"
                                        >Password
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
                                                id="pwd"
                                                name="password"
                                                placeholder="Password"
                                                v-model="registerData.password"
                                                autocomplete="new-password"
                                            />
                                            <span>{{ errors[0] }}</span>
                                            <span
                                                class="small text-muted d-block"
                                                >* password requires at list 6
                                                letter
                                            </span>
                                        </div>
                                    </validation-provider>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="con-pwd"
                                        >Confirm password
                                        <span class="text-danger"
                                            >*</span
                                        ></label
                                    >
                                    <validation-provider
                                        mode="lazy"
                                        rules="required|min:6|confirmed:pwd"
                                        #default="{ errors, classes }"
                                    >
                                        <div :class="classes">
                                            <input
                                                type="password"
                                                class="form-control"
                                                id="con-pwd"
                                                name="password"
                                                placeholder="Confirm password"
                                                v-model="
                                                    registerData.confirmPassword
                                                "
                                                autocomplete="new-password"
                                            />
                                            <span>{{ errors[0] }}</span>
                                        </div>
                                    </validation-provider>
                                </div>
                            </div>
                            <div class="form-group m-0">
                                <label for="user-type"
                                    >User Type
                                    <span class="text-danger">* </span>
                                </label>
                                <validation-provider
                                    mode="lazy"
                                    rules="required"
                                    #default="{ errors, classes }"
                                >
                                    <div :class="classes">
                                        <select
                                            id="user-type"
                                            name="user type"
                                            class="form-control"
                                            :class="{
                                                default: !registerData.userType
                                            }"
                                            v-model="registerData.userType"
                                        >
                                            <option value="" hidden selected
                                                >Select user type</option
                                            >
                                            <option value="Student"
                                                >Student</option
                                            >
                                            <option value="Teacher"
                                                >Teacher</option
                                            >
                                            <option value="Parent"
                                                >Parent</option
                                            >
                                            <option value="Socially"
                                                >Socially</option
                                            >
                                            <option value="At Work"
                                                >At Work</option
                                            >
                                        </select>
                                        <span>{{ errors[0] }}</span>
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
                                Register
                            </button>
                            <router-link
                                :to="{ name: 'login' }"
                                class="btn btn-block btn-outline-dark"
                                >Have an account?</router-link
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
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";

export default {
    components: {
        ValidationProvider,
        ValidationObserver
    },
    data() {
        return {
            registerData: {
                username: "",
                email: "",
                password: "",
                confirmPassword: "",
                userType: ""
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
        async register() {
            this.hasError = {};

            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }

            this.isProcessing = true;
            try {
                let data = await axios.post("/api/register", this.registerData);

                if (data.status === 200) {
                    this.$swal({
                        title: "Mail send successfully",
                        text: "Please check your mail And varifiy your account",
                        icon: "success",
                        showConfirmButton: true
                    });
                    this.resetForm();
                }
            } catch (e) {
                let res = e.response;
                if (res.status === 422) {
                    this.hasError = res.data.errors;
                }
            }
            this.isProcessing = false;
        },
        resetForm() {
            this.$nextTick(() => {
                this.$refs.form.reset();
            });
            this.registerData = {
                username: "",
                email: "",
                password: "",
                confirmPassword: "",
                userType: ""
            };
        }
    }
};
</script>

<style scoped>
select.default {
    color: #b9c1d1;
}

select option {
    color: #000;
}

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
