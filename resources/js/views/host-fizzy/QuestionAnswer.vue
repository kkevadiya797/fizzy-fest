<template>
    <div class="fz-cover">
        <h1 class="fz-header bg-white card">
            {{ currentProps.currentQuestion.question }}
        </h1>
        <div class="fz-cover-img bg-white">
            <div class="fz-que-img pt-3 pb-2 position-relative">
                <button
                    @click="nextQuestion"
                    class="btn btn-primary fz-btn-next"
                >
                    Next
                </button>
                <img :src="imageUrl" alt="img" />
            </div>
            <div class="fz-que-choices row m-0 text-white p-1">
                <div
                    class="col-md-6 col-12 p-1 bg-white"
                    v-for="(choice, index) in currentProps.currentQuestion
                        .choices"
                    :key="index"
                >
                    <div
                        class="fz-que-choice"
                        :class="[
                            choiceColor[index],
                            { 'fz-option-wrong': !choice.is_choice_right }
                        ]"
                    >
                        <i class="fas mr-3" :class="iconClasses[index]"></i>
                        <p class="m-0 ml-2">
                            {{ choice.choice }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        currentProps: {
            type: Object
        }
    },
    data() {
        return {
            iconClasses: ["fa-heart", "fa-star", "fa-circle", "fa-square"],
            choiceColor: ["bg-danger", "bg-warning", "bg-success", "bg-info"],
            imageUrl: null,
        };
    },
    methods: {
        nextQuestion() {
            this.$emit("next", "QuestionResult");
        }
    },
    mounted() {
        if (this.currentProps.currentQuestion.hasImage) {
            this.imageUrl =
                "/storage/que_cover/" + this.$route.params.token + "-" + this.currentProps.currentQuestion.id + ".jpg";
        } else {
            this.imageUrl = "/assets/images/logo/fizzyfest-logo.png";
        }
    }
};
</script>

<style scoped>
.fz-btn-next {
    position: absolute;
    height: 54px;
    font-size: 1rem;
    top: 50px;
    right: 50px;
}
.fz-option-wrong {
    opacity: 0.3;
}
</style>
