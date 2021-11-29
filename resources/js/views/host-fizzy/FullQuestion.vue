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
                    {{ timer }}
                </div>
                <div
                    class="fz-answer d-flex justify-content-center align-items-center card"
                >
                    <span>Answers</span>
                    {{ counter }}
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
                    <div class="fz-que-choice" :class="[choiceColor[index]]">
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
            timer: this.currentProps.currentQuestion.timer,
            counter: 0,
            imageUrl: null
        };
    },
    created() {
        let setTimer = setInterval(() => {
            this.timer -= 1;
            if (this.timer === 0) clearInterval(setTimer);
        }, 1000);
    },
    mounted() {
        if (this.currentProps.currentQuestion.hasImage) {
            this.imageUrl =
                "/storage/que_cover/" + this.$route.params.token + "-" + this.currentProps.currentQuestion.id + ".jpg";
        } else {
            this.imageUrl = "/assets/images/logo/fizzyfest-logo.png";
        } 

        Echo.channel("SendAnswer").listen("SendAnswer", e => {
            if (this.currentProps.game.id == e.participant[0].game_id) {
                this.counter = this.counter + 1;
            }
        });
    },
    watch: {
        async counter(value) {
            if (value == this.currentProps.participants.length) {
                this.timer = 0;

                await axios.post("/api/allResult", {
                    game: this.currentProps.game.id
                });

                this.$emit("next", "QuestionAnswer");
            }
        }
    },
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
