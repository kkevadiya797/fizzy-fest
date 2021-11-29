<template>
    <div>
        <div v-if="!isEditable" class="card card-primary">
            <div class="card-header">
                <h4 class="pr-5">
                    {{ index + 1 }}.
                    <span>{{ question.question }}</span>
                </h4>
            </div>
            <div
                class="dropdown d-inline position-absolute"
                style="top: 16px;right: 25px;"
            >
                <button
                    class="btn btn-icon btn-primary"
                    type="button"
                    id="dropdownMenuButton2"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    <i class="fas fa-ellipsis-h"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-right mt-1">
                    <a
                        class="dropdown-item has-icon"
                        @click="isEditable = true"
                    >
                        <i class="fas fa-edit"></i>Edit</a
                    >
                    <a
                        class="dropdown-item has-icon"
                        @click="deleteQuestion(question.id)"
                        ><i class="fas fa-trash"></i>Delete</a
                    >
                </div>
            </div>
            <div
                class="fz-option-image mx-auto"
                v-if="question.imageUrl"
                :style="{ backgroundImage: 'url(' + getQuestionUrl + ')' }"
            >
            </div>
            <div class="card-body">
                <div class="row">
                    <fizzy-option
                        v-for="(option, index) in question.options"
                        :key="index"
                        :option="option"
                        :index="index"
                    />
                </div>
            </div>
        </div>

        <div v-else class="card card-primary p-0">
            <div class="card-header">
                <h4 class="mb-0">Edit question (Q.{{ index + 1 }})</h4>
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
                                            v-model="newQuestion.question"
                                        />
                                        <span>{{ errors[0] }}</span>
                                    </div>
                                </validation-provider>
                            </div>
                            <div class="form-group m-0">
                                <label for="option"
                                    >Options
                                    <span class="text-danger">*</span>
                                    <span class="small text-muted"
                                        >At list 2 options required
                                    </span>
                                </label>
                                <div
                                    class="form-row"
                                    :class="{
                                        'form-error': hasError
                                    }"
                                >
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div
                                                    class="input-group-text custom-control-fit"
                                                >
                                                    <div
                                                        class="custom-control custom-checkbox custom-control-inline"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            :id="
                                                                'option1_' +
                                                                    newQuestion.id
                                                            "
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
                                                            :for="
                                                                'option1_' +
                                                                    newQuestion.id
                                                            "
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
                                                    newQuestion.options[0]
                                                        .choice
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div
                                                    class="input-group-text custom-control-fit"
                                                >
                                                    <div
                                                        class="custom-control custom-checkbox custom-control-inline"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            :id="
                                                                'option2_' +
                                                                    newQuestion.id
                                                            "
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
                                                            :for="
                                                                'option2_' +
                                                                    newQuestion.id
                                                            "
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
                                                    newQuestion.options[1]
                                                        .choice
                                                "
                                            />
                                        </div>
                                    </div>
                                    <span
                                        class="d-block w-100"
                                        style="margin: -20px 0px 16px;padding: 0 10px;"
                                        v-if="hasError"
                                        >{{ hasError }}</span
                                    >
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div
                                                    class="input-group-text custom-control-fit"
                                                >
                                                    <div
                                                        class="custom-control custom-checkbox custom-control-inline"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            :id="
                                                                'option3_' +
                                                                    newQuestion.id
                                                            "
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
                                                            :for="
                                                                'option3_' +
                                                                    newQuestion.id
                                                            "
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
                                                    newQuestion.options[2]
                                                        .choice
                                                "
                                                :disabled="
                                                    !(
                                                        newQuestion.options[0]
                                                            .choice &&
                                                        newQuestion.options[1]
                                                            .choice
                                                    )
                                                "
                                            />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div
                                                    class="input-group-text custom-control-fit"
                                                >
                                                    <div
                                                        class="custom-control custom-checkbox custom-control-inline"
                                                    >
                                                        <input
                                                            type="checkbox"
                                                            :id="
                                                                'option4_' +
                                                                    newQuestion.id
                                                            "
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
                                                            :for="
                                                                'option4_' +
                                                                    newQuestion.id
                                                            "
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
                                                    newQuestion.options[3]
                                                        .choice
                                                "
                                                :disabled="
                                                    !newQuestion.options[2]
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
                                        <label for="timer"
                                            >Timer(s)
                                            <span class="text-danger">* </span>
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
                                                    v-model="newQuestion.timer"
                                                >
                                                    <option value="5">5</option>
                                                    <option value="10"
                                                        >10</option
                                                    >
                                                    <option value="20" selected
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
                                                <span>{{ errors[0] }}</span>
                                            </div>
                                        </validation-provider>
                                    </div>
                                    <div class="form-group">
                                        <label for="points"
                                            >Points
                                            <span class="text-danger">* </span>
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
                                                    v-model="newQuestion.points"
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
                                                <span>{{ errors[0] }}</span>
                                            </div>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label
                                        class="form-control-label"
                                        for="cover-image"
                                        >Cover image
                                        <span class="font-weight-500">
                                            (Optional)</span
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
                                                'opacity-0': !coverImage.url
                                            }"
                                        />
                                        <div>
                                            <span
                                                v-if="coverImage.file"
                                                class="p-3 bg-white position-absolute right-0 bottom-0"
                                                v-text="coverImage.name"
                                            ></span>
                                            <span v-else class="p-2 bg-white"
                                                >Click here to upload file</span
                                            >
                                        </div>
                                        <input
                                            class="d-flex position-absolute h-100 top-0 left-0 w-100 opacity-0"
                                            type="file"
                                            id="cover-image"
                                            @change="onFileChange($event)"
                                        />
                                        <button v-if="question.imageUrl" type="button" 
                                        class="btn btn-icon btn-danger position-absolute top-0 right-0"
                                            @click="QuestionImgDelete(getQuestionUrl)"
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
                                        'btn-progress disabled': isProcessing
                                    }"
                                >
                                    Save changes
                                </button>
                            </div>

                            <div class="text-center"></div>
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
import FizzyOption from "./QuestionOption.vue";
import { sweetAlert } from "../../mixins/sweet-alert.js";

export default {
    mixins: [sweetAlert],
    components: {
        FizzyOption,
        ValidationProvider,
        ValidationObserver
    },
    props: {
        question: {
            type: Object,
            required: true
        },
        index: {
            type: Number,
            required: true
        },
        fizzyToken: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            isEditable: false,
            newQuestion: this.question,
            isProcessing: false,
            hasError: false,
            coverImage: {
                file: null,
                name: "",
                url: ""
            }
        };
    },
    computed: {
        getQuestionUrl() {
            if (this.question.imageUrl === true) {
                let imgUrl =
                    "/storage/que_cover/" +
                    this.fizzyToken +
                    "-" +
                    this.question.id +
                    ".jpg";

                this.coverImage.url = imgUrl;
                return imgUrl;
            } else {
                return this.question.imageUrl;
            }
        }
    },
    methods: {
        onFileChange(event) {
            this.coverImage.file = event.target.files[0];
            this.newQuestion.imageUrl = URL.createObjectURL(
                this.coverImage.file
            );
            this.coverImage.name = this.coverImage.file.name;
            this.coverImage.url = URL.createObjectURL(this.coverImage.file);
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
            questionData.append("question", JSON.stringify(this.newQuestion));
            questionData.append("fizzyToken", this.$route.params.token);
            questionData.append("image", this.coverImage.file);

            //api
            this.isProcessing = true;

            try {
                await axios.post("/api/fizzy/question/update", questionData);

                this.isEditable = false;
                this.showToast("success", "Question edit successfully");
            } catch (error) {
                console.log(error);
                this.showToast("error", "Oops! something went wrong.");
            }
            this.isProcessing = false;
        },
        deleteQuestion(id) {
            this.$swal({
                title: "Are you sure?",
                text: "You will not be able to recover this question",
                icon: "question",
                showCancelButton: true
            }).then(({ value }) => {
                if (value) {
                    try {
                        axios.post(`/api/fizzy/question/delete/${id}`, {
                            token: this.$route.params.token
                        });

                        this.$emit("deleteQuestion", this.index);

                        this.showToast(
                            "success",
                            "Question deleted successfully"
                        );
                    } catch (error) {
                        console.log(error);
                        this.showToast("error", "Oops! something went wrong.");
                    }
                }
            });
        },

        async QuestionImgDelete(imgUrls) {
            if (this.question.imageUrl === true) {
                let imgUrl = this.fizzyToken + "-" + this.question.id;
                try {
                    let  data  = await axios.post("/api/fizzy/img/delete", {
                        imgUrl: imgUrl
                    });
                    if(data.status)
                    {
                        this.coverImage = { file: null, name: "", url: "" };
                        this.question.imageUrl = '';
                    }
                } catch (error) {
                    console.log(error);
                }
            }
        }
    }
};
</script>

<style scoped>
/* option image */
.card .fz-option-image {
    width: 300px;
    height: 200px;
    background-size: contain;
    background-position: center;
    background-repeat: no-repeat;
}

.input-file-message {
    padding: 4.24rem 1rem;
}
</style>
