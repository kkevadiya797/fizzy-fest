<template>
    <div class="fz-cover">
        <h1 class="fz-header bg-white card">
            {{ currentProps.currentQuestion.question }}
        </h1>
        <div class="fz-cover-img bg-white">
            <div class="fz-que-img pt-3 pb-2 position-relative">
                <div
                    class="fz-timer d-flex justify-content-center align-items-center card"
                >
                    <span>Timer</span>
                    {{ currentProps.timer }}
                </div>
                <img :src="imageUrl" alt="img" />
            </div>
            <div class="fz-que-choices row m-0 text-white p-1">
                <div
                    class="col-md-6 col-12 p-1"
                    v-for="(choice, index) in currentProps.currentQuestion
                        .choices"
                    :key="index"
                >
                    <button
                        class="fz-que-choice"
                        :class="optoinClasses.bgColors[index]"
                        @click="setAnswer(choice)"
                    >
                        <i
                            class="fas mr-3"
                            :class="optoinClasses.icons[index]"
                        ></i>
                        <p class="m-0 ml-2">
                            {{ choice.choice }}
                        </p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        currentProps: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            optoinClasses: {
                bgColors: [
                    "btn-danger",
                    "btn-warning",
                    "btn-success",
                    "btn-info"
                ],
                icons: ["fa-heart", "fa-star", "fa-circle", "fa-square"]
            },
            imageUrl: null 
        };
    },
    methods: {
        setAnswer(choice) {
            this.$emit("next", choice);
        }
    },
    mounted() {
        if (this.currentProps.currentQuestion.hasImage) {
            this.imageUrl =
                "/storage/fizzy_cover/" + this.$route.params.token + "-" + this.currentProps.currentQuestion.id + ".jpg";
        } else {
            this.imageUrl = "/assets/images/cover/cover_992.svg";
        }
    }
};
</script>

<style scoped>
.fz-timer,
.fz-answer {
    position: absolute;
    background-color: white;
    height: 80px;
    width: 80px;
    border-radius: 40px;
    font-size: 1.7rem;
    font-weight: 600;
    top: 50%;
    transform: translate(0, -50%);
}
.fz-timer {
    left: 50px;
}
.fz-answer {
    right: 50px;
}
.fz-timer > span,
.fz-answer > span {
    position: absolute;
    top: -50px;
}
</style>
