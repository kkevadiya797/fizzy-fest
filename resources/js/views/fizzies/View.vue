<template>
    <div>
        <div class="section-header">
            <h1>Fizzy details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'dashboard' }">Home</router-link>
                </div>
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'fizzies.index' }"
                        >fizzies</router-link
                    >
                </div>
                <div class="breadcrumb-item">Fizzy details</div>
            </div>
        </div>
        <div class="w-max-1200 m-auto">
            <div
                class="fz-cover-view border-radius-1"
                :style="{
                    'background-image': 'url(' + fizzyData.imageUrl + ')',
                }"
            >
                <h2 class="p-4 text-white">{{ fizzyData.title }}</h2>
                <div
                    class="dropdown d-inline position-absolute"
                    style="top: 25px; right: 25px;"
                >
                    <button
                        class="btn btn-icon btn-light"
                        type="button"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-cog"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right mt-1">
                        <a
                            class="dropdown-item has-icon"
                            data-toggle="modal"
                            data-target="#fizzyEdit"
                            @click="setEditFizzy()"
                        >
                            <i class="fas fa-edit"></i>Edit
                        </a>
                        <a
                            class="dropdown-item has-icon"
                            @click="fizzyStatus()"
                        >
                            <i
                                class="fas"
                                :class="
                                    !fizzyData.publish_at
                                        ? 'fa-plus'
                                        : 'fa-minus'
                                "
                            ></i>
                            {{
                                !fizzyData.publish_at
                                    ? "Published"
                                    : "Unpublished"
                            }}
                        </a>
                        <a class="dropdown-item has-icon" @click="deleteFizzy">
                            <i class="fas fa-trash"></i>Delete
                        </a>
                    </div>
                </div>
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
            <div class="card border-radius-1 border-top-0" style="z-index: -1;">
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
                            <span>
                                {{
                                    fizzyData.total_plays == 0
                                        ? "Play"
                                        : "Plays"
                                }}
                            </span>
                        </li>
                        <li class="d-inline-block">
                            <i class="fas fa-users mr-1"></i>
                            <span>
                                <b class="b">{{ fizzyData.total_players }}</b>
                            </span>
                            <span>
                                {{
                                    fizzyData.total_players == 0
                                        ? "Player"
                                        : "Players"
                                }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            <div>
                <div
                    class="section-header section-header-none d-flex justify-content-between"
                >
                    <h1 class="m-0" style="line-height: 42px;">Questions</h1>
                    <button
                        v-if="!showForm"
                        class="btn btn-primary"
                        @click="showForm = true"
                    >
                        Add new question
                    </button>
                </div>

                <div v-if="showForm" class="card p-0">
                    <div class="card-header">
                        <h4 class="mb-0">Add new question</h4>
                    </div>
                    <div class="card-body">
                        <validation-observer ref="form">
                            <form @submit.prevent="addQuestion">
                                <div>
                                    <div class="form-group">
                                        <label for="question">
                                            Question
                                            <span class="text-danger">*</span>
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
                                                    id="question"
                                                    placeholder="Question"
                                                    v-model="
                                                        newQuestion.question
                                                    "
                                                />
                                                <span>{{ errors[0] }}</span>
                                            </div>
                                        </validation-provider>
                                    </div>
                                    <div class="form-group m-0">
                                        <label for="option">
                                            Options
                                            <span class="text-danger">*</span>
                                            <span class="small text-muted"
                                                >At list 2 options
                                                required</span
                                            >
                                        </label>
                                        <div
                                            class="form-row"
                                            :class="{
                                                'form-error': hasError,
                                            }"
                                        >
                                            <div class="form-group col-md-6">
                                                <div class="input-group">
                                                    <div
                                                        class="input-group-prepend"
                                                    >
                                                        <div
                                                            class="input-group-text custom-control-fit"
                                                        >
                                                            <div
                                                                class="custom-control custom-checkbox custom-control-inline"
                                                            >
                                                                <input
                                                                    type="checkbox"
                                                                    id="option_1"
                                                                    v-model="
                                                                        newQuestion
                                                                            .options[0]
                                                                            .is_choice_right
                                                                    "
                                                                    value="0"
                                                                    class="custom-control-input"
                                                                    :disabled="
                                                                        !newQuestion
                                                                            .options[0]
                                                                            .choice
                                                                    "
                                                                />
                                                                <label
                                                                    class="custom-control-label"
                                                                    for="option_1"
                                                                ></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        name="option"
                                                        placeholder="Option 1"
                                                        v-model="
                                                            newQuestion
                                                                .options[0]
                                                                .choice
                                                        "
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="input-group">
                                                    <div
                                                        class="input-group-prepend"
                                                    >
                                                        <div
                                                            class="input-group-text custom-control-fit"
                                                        >
                                                            <div
                                                                class="custom-control custom-checkbox custom-control-inline"
                                                            >
                                                                <input
                                                                    type="checkbox"
                                                                    id="option_2"
                                                                    v-model="
                                                                        newQuestion
                                                                            .options[1]
                                                                            .is_choice_right
                                                                    "
                                                                    value="1"
                                                                    class="custom-control-input"
                                                                    :disabled="
                                                                        !newQuestion
                                                                            .options[1]
                                                                            .choice
                                                                    "
                                                                />
                                                                <label
                                                                    class="custom-control-label"
                                                                    for="option_2"
                                                                ></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        name="option"
                                                        placeholder="Option 2"
                                                        v-model="
                                                            newQuestion
                                                                .options[1]
                                                                .choice
                                                        "
                                                    />
                                                </div>
                                            </div>
                                            <span
                                                class="d-block w-100"
                                                style="
                                                    margin: -20px 0px 16px;
                                                    padding: 0 10px;
                                                "
                                                v-if="hasError"
                                                >{{ hasError }}</span
                                            >
                                            <div class="form-group col-md-6">
                                                <div class="input-group">
                                                    <div
                                                        class="input-group-prepend"
                                                    >
                                                        <div
                                                            class="input-group-text custom-control-fit"
                                                        >
                                                            <div
                                                                class="custom-control custom-checkbox custom-control-inline"
                                                            >
                                                                <input
                                                                    type="checkbox"
                                                                    id="option_3"
                                                                    v-model="
                                                                        newQuestion
                                                                            .options[2]
                                                                            .is_choice_right
                                                                    "
                                                                    value="2"
                                                                    class="custom-control-input"
                                                                    :disabled="
                                                                        !newQuestion
                                                                            .options[2]
                                                                            .choice
                                                                    "
                                                                />
                                                                <label
                                                                    class="custom-control-label"
                                                                    for="option_3"
                                                                ></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        name="option"
                                                        placeholder="Option 3 (Optional)"
                                                        v-model="
                                                            newQuestion
                                                                .options[2]
                                                                .choice
                                                        "
                                                        :disabled="
                                                            !(
                                                                newQuestion
                                                                    .options[0]
                                                                    .choice &&
                                                                newQuestion
                                                                    .options[1]
                                                                    .choice
                                                            )
                                                        "
                                                    />
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="input-group">
                                                    <div
                                                        class="input-group-prepend"
                                                    >
                                                        <div
                                                            class="input-group-text custom-control-fit"
                                                        >
                                                            <div
                                                                class="custom-control custom-checkbox custom-control-inline"
                                                            >
                                                                <input
                                                                    type="checkbox"
                                                                    id="option_4"
                                                                    v-model="
                                                                        newQuestion
                                                                            .options[3]
                                                                            .is_choice_right
                                                                    "
                                                                    value="3"
                                                                    class="custom-control-input"
                                                                    :disabled="
                                                                        !newQuestion
                                                                            .options[3]
                                                                            .choice
                                                                    "
                                                                />
                                                                <label
                                                                    class="custom-control-label"
                                                                    for="option_4"
                                                                ></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        name="option"
                                                        placeholder="Option 4 (Optional)"
                                                        v-model="
                                                            newQuestion
                                                                .options[3]
                                                                .choice
                                                        "
                                                        :disabled="
                                                            !newQuestion
                                                                .options[2]
                                                                .choice
                                                        "
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="timer">
                                                    Timer(s)
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </label>
                                                <validation-provider
                                                    mode="lazy"
                                                    rules="required"
                                                    #default="{ errors, classes }"
                                                >
                                                    <div
                                                        :class="classes"
                                                        class="input-group"
                                                    >
                                                        <div
                                                            class="input-group-prepend"
                                                        >
                                                            <div
                                                                class="input-group-text"
                                                            >
                                                                <i
                                                                    class="fas fa-clock"
                                                                ></i>
                                                            </div>
                                                        </div>
                                                        <select
                                                            id="timer"
                                                            name="Timer"
                                                            class="form-control"
                                                            v-model="
                                                                newQuestion.timer
                                                            "
                                                        >
                                                            <option value="5"
                                                                >5</option
                                                            >
                                                            <option value="10"
                                                                >10</option
                                                            >
                                                            <option
                                                                value="20"
                                                                selected
                                                                >20</option
                                                            >
                                                            <option value="30"
                                                                >30</option
                                                            >
                                                            <option value="60"
                                                                >60</option
                                                            >
                                                            <option value="90"
                                                                >90</option
                                                            >
                                                            <option value="120"
                                                                >120</option
                                                            >
                                                            <option value="240"
                                                                >240</option
                                                            >
                                                        </select>
                                                        <span>
                                                            {{ errors[0] }}
                                                        </span>
                                                    </div>
                                                </validation-provider>
                                            </div>
                                            <div class="form-group">
                                                <label for="points">
                                                    Points
                                                    <span class="text-danger"
                                                        >*</span
                                                    >
                                                </label>
                                                <validation-provider
                                                    mode="lazy"
                                                    rules="required"
                                                    #default="{ errors, classes }"
                                                >
                                                    <div
                                                        :class="classes"
                                                        class="input-group"
                                                    >
                                                        <div
                                                            class="input-group-prepend"
                                                        >
                                                            <div
                                                                class="input-group-text"
                                                            >
                                                                <i
                                                                    class="fas fa-trophy"
                                                                ></i>
                                                            </div>
                                                        </div>
                                                        <select
                                                            id="points"
                                                            name="points"
                                                            class="form-control"
                                                            v-model="
                                                                newQuestion.points
                                                            "
                                                        >
                                                            <option value="500"
                                                                >500</option
                                                            >
                                                            <option
                                                                value="1000"
                                                                selected
                                                                >1000</option
                                                            >
                                                            <option value="1500"
                                                                >1500</option
                                                            >
                                                            <option value="2000"
                                                                >2000</option
                                                            >
                                                        </select>
                                                        <span>
                                                            {{ errors[0] }}
                                                        </span>
                                                    </div>
                                                </validation-provider>
                                            </div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label
                                                class="form-control-label"
                                                for="cover-image"
                                            >
                                                Cover image
                                                <span class="font-weight-500"
                                                    >(Optional)</span
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
                                                        'opacity-0': !coverImage.url,
                                                    }"
                                                />
                                                <div>
                                                    <span
                                                        v-if="coverImage.file"
                                                        class="p-3 bg-white position-absolute right-0 bottom-0"
                                                        v-text="coverImage.name"
                                                    ></span>
                                                    <span v-else class="p-2">
                                                        Click here to upload
                                                        file
                                                    </span>
                                                </div>
                                                <input
                                                    class="d-flex position-absolute h-100 top-0 left-0 w-100 opacity-0"
                                                    type="file"
                                                    id="cover-image"
                                                    @change="
                                                        onFileChange($event)
                                                    "
                                                />
                                                <button v-if="coverImage.url" type="button" 
                                                    class="btn btn-icon btn-danger position-absolute top-0 right-0"
                                                    @click="QuestionImgDelete()"
                                                    >
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center buttons">
                                        <button
                                            type="submit"
                                            class="btn btn-primary"
                                            :class="{
                                                'btn-progress disabled': isProcessing,
                                            }"
                                        >
                                            Add question
                                        </button>
                                        <button
                                            class="btn btn-secondary"
                                            type="reset"
                                            @click="resetForm(false)"
                                        >
                                            Cancel
                                        </button>
                                    </div>

                                    <div class="text-center"></div>
                                </div>
                            </form>
                        </validation-observer>
                    </div>
                </div>
            </div>
            <fizzy-question
                v-for="(question, index) in fizzyData.questions"
                :key="index"
                :question="question"
                :index="index"
                :fizzyToken="$route.params.token"
                @deleteQuestion="deleteQuestion($event)"
            />
        </div>

        <!-- edit model -->
        <div
            class="modal fade"
            id="fizzyEdit"
            tabindex="-1"
            role="dialog"
            aria-labelledby="exampleModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Edit Fizzy
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <validation-observer ref="editForm">
                            <form @submit.prevent="onSubmit">
                                <!-- <div class="row"> -->
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label
                                            class="form-control-label"
                                            for="title"
                                        >
                                            Title
                                            <span class="text-danger">*</span>
                                        </label>
                                    </div>
                                    <div class="col-md-9">
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
                                                    v-model="
                                                        selectedFizzy.title
                                                    "
                                                />
                                                <span>{{ errors[0] }}</span>
                                            </div>
                                        </validation-provider>
                                    </div>
                                </div>
                                <!-- </div> -->
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label
                                            class="form-control-label"
                                            for="description"
                                        >
                                            Description
                                            <span class="font-weight-500"
                                                >(Optional)</span
                                            >
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <textarea
                                            class="form-control"
                                            id="description"
                                            placeholder="Description"
                                            v-model="selectedFizzy.description"
                                            rows="3"
                                        ></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <label
                                            class="form-control-label"
                                            for="cover-image"
                                        >
                                            Cover image
                                            <span class="font-weight-500"
                                                >(Optional)</span
                                            >
                                        </label>
                                    </div>
                                    <div class="col-md-9">
                                        <div
                                            class="mb-3 input-file-message line-height-0"
                                        >
                                            <img
                                                class="input-file-image"
                                                :src="coverImage.url"
                                                alt="fizzy-cover"
                                                :class="{
                                                    'opacity-0': !coverImage.url,
                                                }"
                                            />
                                            <div>
                                                <span
                                                    v-if="coverImage.file"
                                                    class="p-3 bg-white position-absolute right-0 bottom-0"
                                                    v-text="coverImage.name"
                                                ></span>
                                                <span v-else class="p-2">
                                                    Click here to upload file
                                                </span>
                                            </div>
                                            <input
                                                class="d-flex position-absolute h-100 top-0 left-0 w-100 opacity-0"
                                                type="file"
                                                id="cover-image"
                                                @change="onFileChange($event)"
                                            />
                                            <button v-if="fizzyData.hasImage" type="button" 
                                                class="btn btn-icon btn-danger position-absolute top-0 right-0"
                                                @click="fizzyImgDelete($route.params.token)"
                                                >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center buttons">
                                    <button
                                        id="clic"
                                        type="submit"
                                        class="btn btn-primary"
                                        :class="{
                                            'btn-progress disabled': isProcessing,
                                        }"
                                    >
                                        Save changes
                                    </button>
                                    <button
                                        class="btn btn-secondary"
                                        type="reset"
                                        data-dismiss="modal"
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
        Loading,
    },
    data() {
        return {
            user: null,
            fizzyData: {
                title: "",
                description: "",
                total_plays: 0,
                total_players: 0,
                hasImage: false,
                imageUrl: "",
                questions: [],
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
                    { choice: "", is_choice_right: false },
                ],
            },
            isProcessing: false,
            isLoading: false,
            hasError: false,
            coverImage: {
                file: null,
                name: "",
                url: "",
            },
            selectedFizzy: {
                title: "",
                description: "",
                token: "",
            },
            showForm: false,
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
        onFileChange(event) {
            this.coverImage.file = event.target.files[0];
            this.coverImage.name = this.coverImage.file.name;
            this.coverImage.url = URL.createObjectURL(this.coverImage.file);
        },
        async getFizzyDetail() {
            this.isLoading = true;
            try {
                let { data } = await axios.get(
                    `/api/fizzy/${this.$route.params.token}`
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
                    "/assets/images/cover/game_cover_" +
                    Math.floor(Math.random() * 4 + 1) +
                    ".svg";
                }
            } catch (e) {
                //page not found
                if (e.response.status === 404) {
                    this.$router.push({ name: "404" });
                }
            }
            this.isLoading = false;
        },
        async addQuestion() {
            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }

            // validate options
            if (
                !(
                    this.newQuestion.options[0].choice &&
                    this.newQuestion.options[1].choice
                )
            ) {
                this.hasError = "At list 2 option required";
                return;
            } else if (
                !this.newQuestion.options[0].is_choice_right &&
                !this.newQuestion.options[1].is_choice_right &&
                !this.newQuestion.options[2].is_choice_right &&
                !this.newQuestion.options[3].is_choice_right
            ) {
                this.hasError = "select at list 1 correct option";
                return;
            } else {
                this.hasError = false;
            }

            let questionData = new FormData();
            questionData.append("fizzy", JSON.stringify(this.newQuestion));
            questionData.append("fizzyToken", this.$route.params.token);
            questionData.append("image", this.coverImage.file);

            //api
            this.isProcessing = true;
            try {
                await axios.post("/api/fizzy/question/store", questionData);

                let getQuestion = await axios.post("/api/fizzy/question/show", {
                    token: this.$route.params.token,
                });
                this.fizzyData.questions.push(getQuestion.data);

                this.resetForm(true);
                this.showToast("success", "Question added successfully");
            } catch (e) {
                console.log(e);
                this.showToast("error", "Oops! something went wrong.");
            }
            this.isProcessing = false;
        },
        resetForm(showForm) {
            this.$nextTick(() => {
                this.$refs.form.reset();
            });

            this.newQuestion = {
                question: "",
                imageUrl: false,
                timer: 20,
                points: 1000,
                options: [
                    { choice: "", is_choice_right: false },
                    { choice: "", is_choice_right: false },
                    { choice: "", is_choice_right: false },
                    { choice: "", is_choice_right: false },
                ],
            };
            this.coverImage = { file: null, name: "", url: "" };
            this.showForm = showForm;
        },
        deleteQuestion(event) {
            this.fizzyData.questions.splice(event, 1);
        },
        setEditFizzy() {
            this.selectedFizzy.title = this.fizzyData.title;
            (this.selectedFizzy.description = this.fizzyData.description),
                (this.selectedFizzy.token = this.$route.params.token);
        },
        async onSubmit() {
            let success = await this.$refs.editForm.validate();

            if (!success) {
                return;
            }
            this.isProcessing = true;

            this.fizzyData.title = this.selectedFizzy.title;
            this.fizzyData.description = this.selectedFizzy.description;

            try {
                let fizzyData = new FormData();
                fizzyData.append("title", this.selectedFizzy.title);
                fizzyData.append("description", this.selectedFizzy.description);
                fizzyData.append("token", this.$route.params.token);
                fizzyData.append("image", this.coverImage.file);

                let { data } = await axios.post("/api/fizzy/edit", fizzyData, {
                    headers: { "content-type": "multipart/form-data" },
                });
                console.log(data);
            } catch (e) {
                console.log(e);
            }

            this.isProcessing = false;
            $("#fizzyEdit").modal("hide");

            this.showToast("success", "fizzy updated successfully");
            this.getFizzyDetail();
        },
        deleteFizzy() {
            this.$swal({
                title: "Are you sure?",
                text: "You will not be able to recover this Fizzy",
                icon: "warning",
                showCancelButton: true,
            }).then((res) => {
                if (res.value) {
                    try {
                        let datas = axios.post("/api/fizzy/delete", {
                            selectedFizzyToken: this.$route.params.token,
                        });
                        this.showToast("success", "fizzy deleted successfully");
                        this.$router.push({ name: "fizzies.index" });
                    } catch (e) {
                        console.log(e);
                    }
                }
            });
        },

        async fizzyStatus() {
            let newStatus = !this.fizzyData.publish_at;
            this.fizzyData.publish_at = newStatus;

            let data = await axios.post("/api/fizzy/status/edit", {
                fizzyToken: this.$route.params.token,
                status: newStatus,
            });

            this.showToast(
                "success",
                "fizzy " + (newStatus ? "Published" : "Unpublished")
            );
        },
        QuestionImgDelete(){
            this.coverImage = { file: null, name: "", url: "" };
        },
        async fizzyImgDelete(token){
            try {
                let  data  = await axios.post("/api/fizzy/img/delete", {
                    token: token
                });
                if(data.status)
                {
                    this.coverImage = { file: null, name: "", url: "" };
                }
            } catch (error) {
                console.log(error);
            }
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
