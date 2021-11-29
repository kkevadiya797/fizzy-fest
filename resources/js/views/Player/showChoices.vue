<template>
    <div class="fz-cover container-fluid p-0 h-100">
        <div
            class="fz-name-score bg-white text-center py-2 px-3 card fz-header-play m-0"
        >
            <div class="fz-player-name m-0">
                <img
                    class="fz-logo-img position-absolute"
                    src="/assets/images/fizzyfest-logo.png"
                    alt="fizzyfest logo"
                />
                <span>{{ currentProps.participant.name }}</span>
                <span class="btn btn-dark btn-score">{{
                    currentProps.participant.totalPoints
                }}</span>
            </div>
        </div>
        <div v-if="currentQuestion == null" class="fz-message">
            <div>
                <span class="message-1">Look at the screen</span>
            </div>
        </div>
        <component
            :is="selectedComponent"
            :currentProps="currentComponentProps"
            @next="setAnswer"
            class="fz-options"
            v-else
        />
    </div>
</template>

<script>
import WithoutQuestion from "./Question/withoutQuestion.vue";
import WithQuestion from "./Question/withQuestion.vue";
export default {
    props: {
        currentProps: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            answer: false,
            participants: [],
            getAnswer: false,
            currentQuestion: null,
            timer: null,
            timeout: false,
            setTimer: null,
            optoinClasses: {
                bgColors: [
                    "btn-danger",
                    "btn-warning",
                    "btn-success",
                    "btn-info"
                ],
                icons: ["fa-heart", "fa-star", "fa-circle", "fa-square"]
            },
            selectedComponent: null
        };
    },
    components: {
        WithoutQuestion: WithoutQuestion,
        WithQuestion: WithQuestion
    },
    mounted() {
        Echo.channel("SendQuestion").listen("SendQuestionEvent", e => {
            if (this.currentProps.participant.game_id == e.gameID) {
                this.currentQuestion = e.question;
                this.timer = this.currentQuestion.timer;
            }
        });
        if (this.currentProps.question) {
            this.currentQuestion = this.currentProps.question;
            this.timer = this.currentQuestion.timer;
        }
    },
    created() {
        this.setTimer = setInterval(() => {
            this.timer -= 1;
            if (this.timer === 0) {
                clearInterval(this.setTimer);
                this.timeout = true;
                this.sendAnswer();
            }
        }, 1000);

        if (this.currentProps.showQuestion == false) {
            this.selectedComponent = "WithoutQuestion";
        } else {
            this.selectedComponent = "WithQuestion";
        }
    },
    computed: {
        noOfOptions() {
            return this.currentQuestion.choices.length;
        },
        currentComponentProps() {
            return {
                currentQuestion: this.currentQuestion,
                timer: this.timer
            };
        }
    },
    methods: {
        setAnswer(selectedChoice) {
            if (selectedChoice.is_choice_right) {
                this.answer = true;
            }
            this.sendAnswer();
        },
        async sendAnswer() {
            await axios
                .post("/api/send/answer", {
                    answer: this.answer,
                    participant: this.currentProps.participant.id,
                    question: this.currentQuestion,
                    timer: this.timer
                })
                .then(response => {
                    if (Object.keys(response.data).length != 0) {
                        this.answer = false;
                        this.participants = response.data.participant[0];
                        this.getAnswer = response.data.answer;
                        this.emitAnswer();
                    }
                });
        },
        emitAnswer() {
            clearInterval(this.setTimer);
            this.$emit(
                "next",
                "waiting",
                this.participants,
                this.getAnswer,
                this.timeout
            );
        }
    }
};
</script>
