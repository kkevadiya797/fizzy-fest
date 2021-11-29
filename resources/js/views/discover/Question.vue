<template>
    <div>
        <div  class="card card-primary">
            <div class="card-header">
                <h4 class="pr-5">
                    {{ index + 1 }}.
                    <span>{{ question.question }}</span>
                </h4>
            </div>
            <div
                class="fz-option-image bg-light mx-auto"
                v-if="question.imageUrl"
                :style="{ backgroundImage: 'url(' + getQuestionUrl + ')' }"
            ></div>
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
                let imgUrl = "/storage/que_cover/" +
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
};
</script>
