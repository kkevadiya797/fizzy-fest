<template>
    <div>
        <div class="w-max-1200 m-auto">
            <div
                class="fz-cover-view mt-4 border-radius-1"
                :style="{
                    'background-image': 'url(' + fizzyData.imageUrl + ')'
                }"
            >
                <h2 class="p-4 text-white">{{ fizzyData.title }}</h2>
                <div
                    class="dropdown d-inline position-absolute"
                    style="top: 25px;right: 25px;"
                ></div>
                <div class="fz-play-div position-absolute">
                    <router-link
                        v-if="user"
                        :to="{
                            name: 'play',
                            params: {
                                token: this.$route.params.token,
                                id: user.id,
                            },
                        }"
                        target="_blank"
                    >
                        <button class="btn btn-warning fz-btn-play">
                            <i class="fas fa-play"></i>
                        </button>
                    </router-link>
                </div>
            </div>
            <div class="card border-radius-1 border-top-0" style="z-index:-1;">
                <div class="card-header">
                    <h4>Description</h4>
                </div>
                <div class="card-body py-0">
                    <p>
                        {{
                            fizzyData.description ||
                                "This is the game. The game that improves your knowledge."
                        }}
                    </p>
                </div>
                <hr />
                <div class="card-body pt-0">
                    <ul class="m-0 list-unstyled">
                        <li class="d-inline-block mr-4">
                            <i class="fas fa-play mr-1"></i>
                            <span>
                                <b class="b">{{ fizzyData.total_plays }}</b>
                            </span>
                            <span>{{
                                fizzyData.total_plays == 0 ? "Play" : "Plays"
                            }}</span>
                        </li>
                        <li class="d-inline-block">
                            <i class="fas fa-users mr-1"></i>
                            <span>
                                <b class="b">{{ fizzyData.total_players }}</b>
                            </span>
                            <span>{{
                                fizzyData.total_players == 0
                                    ? "Player"
                                    : "Players"
                            }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div>
                <div
                    class="section-header section-header-none d-flex justify-content-between"
                >
                    <h1 class="m-0" style="line-height: 42px;">Questions</h1>
                </div>
            </div>
            <fizzy-question
                v-for="(question, index) in fizzyData.questions"
                :key="index"
                :question="question"
                :index="index"
                :fizzyToken="$route.params.token"
            />
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
import Loading from "vue-loading-overlay";
import { sweetAlert } from "../../mixins/sweet-alert.js";
import FizzyQuestion from "./Question.vue";

export default {
    mixins: [sweetAlert],
    components: {
        FizzyQuestion,
        ValidationProvider,
        ValidationObserver,
        Loading
    },
    data() {
        return {
            user: '',
            fizzyData: {
                title: "",
                description: "",
                total_plays: 0,
                total_players: 0,
                hasImage: false,
                imageUrl: "",
                questions: []
            },
            newQuestion: {
                question: "",
                timer: 20,
                points: 1000,
                imageUrl: false,
                options: [
                    { choice: "", is_choice_right: false },
                    { choice: "", is_choice_right: false },
                    { choice: "", is_choice_right: false },
                    { choice: "", is_choice_right: false }
                ]
            },
            isProcessing: false,
            isLoading: false,
            hasError: false,
            coverImage: {
                file: null,
                name: "",
                url: ""
            }
        };
    },
    mounted() {
        this.getFizzyDetail();
    },
    created() {
        axios.get("/api/getUser").then((response) => {
            this.user = response.data;
        });
    },
    methods: {
        async getFizzyDetail() {
            this.isLoading = true;
            try {
                let { data } = await axios.get(
                    `/api/discover/${this.$route.params.token}`
                );

                this.fizzyData = data.fizzy;
                this.fizzyData.questions = data.fizzy.questions;

                if (this.fizzyData.hasImage) {
                    this.fizzyData.imageUrl =
                        "/storage/fizzy_cover/" +
                        this.$route.params.token +
                        ".jpg";
                } else {
                    this.fizzyData.imageUrl =
                        "/assets/images/cover/cover_992.svg";
                }
            } catch (e) {
                //page not found
                if (e.response.status === 404) {
                    this.$router.push({ name: "404" });
                }
            }
            this.isLoading = false;
        }
    }
};
</script>

<style scoped>
.input-file-message {
    padding: 4.24rem 1rem;
}

.fz-cover-view {
    position: relative;
    height: 300px;
    width: 100%;
    background-size: cover;
    background-position: center;
    box-shadow: 0 0 2rem 0 rgba(140, 144, 158, 0.23);
}
.border-radius-1 {
    border-radius: 0.5rem;
}
.card {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    padding-top: 10px;
    margin-top: -10px;
}
.b {
    color: #34395e;
}
.fz-play-div {
    background-color: #fff;
    border-radius: 100%;
    box-shadow: 0 0 2rem 0 rgba(140, 144, 158, 0.23);
    top: 100%;
    left: 50%;
    transform: translate(-50%, -38px);
}
.fz-btn-play {
    height: 60px;
    width: 60px;
    border-radius: 100%;
    margin: 8px;
}
.fz-btn-play i.fas {
    font-size: 18px !important;
    margin-top: 13px;
    margin-left: -3px !important;
}
</style>
