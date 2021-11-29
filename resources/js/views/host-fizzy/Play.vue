<template>
    <component
        :is="currentComponent"
        :currentProps="currentProps"
        @next="nextComponent"
    />
</template>

<script>
import { async } from "q";
import Host from "./Host.vue";
import Title from "./Title.vue";
import Question from "./Question.vue";
import FullQuestion from "./FullQuestion.vue";
import QuestionAnswer from "./QuestionAnswer.vue";
import QuestionResult from "./QuestionResult.vue";
import Scoreboard from "./Scoreboard.vue";

export default {
    data() {
        return {
            fizzyData: null,
            indexQuestion: 0,
            currentComponent: null,
            componentTimeout: 0,
            game: [],
            participants: [],
            showQuestion: null
        };
    },
    components: {
        Host,
        Title,
        Question,
        FullQuestion,
        QuestionAnswer,
        QuestionResult,
        Scoreboard
    },
    computed: {
        currentQuestion() {
            return this.fizzyData
                ? this.fizzyData.questions[this.indexQuestion]
                : null;
        },
        currentProps() {
            return {
                fizzyData: this.fizzyData,
                currentQuestion: this.currentQuestion,
                indexQuestion: this.indexQuestion,
                componentTimeout: this.componentTimeout,
                game: this.game,
                participants: this.participants,
                showQuestion: this.showQuestion
            };
        }
    },
    mounted() {
        Echo.channel("participate").listen("NewParticipate", e => {
            if (e.gameID == this.game.id) {
                axios
                    .get("/api/getParticipate/" + this.game.id)
                    .then(response => {
                        this.participants = response.data;
                    });
            }
        });
        Echo.channel("SendAnswer").listen("SendAnswer", e => {
            if (this.game.id == e.participant[0].game_id) {
                for (let i = 0; i < this.participants.length; i++) {
                    if (this.participants[i].id == e.participant[0].id) {
                        this.participants[i] = e.participant[0];
                    }
                }
            }
        });
    },
    created() {
        axios
            .get("/api/generatePin/" + this.$route.params.token)
            .then(response => {
                this.game = response.data[0];
            });
        this.loadData();
    },
    methods: {
        loadData() {
            this.setComponentWithTimeout(Host);
        },
        async nextComponent(event, value) {
            if (event) {
                this.currentComponent = event;
                if (this.currentComponent == "Title") {
                    this.showQuestion = value;
                    await axios
                        .post("/api/startGame", {
                            game: this.game.id,
                            token: this.$route.params.token,
                            userID: this.$route.params.id,
                            showQuestion: this.showQuestion
                        })
                        .then(response => {
                            this.fizzyData = response.data.fizzy;
                            // console.log(this.fizzyData.hasImage);
                        });

                    this.palyFizzy();
                }
            } else {
                if (this.indexQuestion + 1 != this.fizzyData.questions.length) {
                    this.indexQuestion++;
                    this.displayQuestion();
                } else {
                    this.currentComponent = Scoreboard;
                }
            }
        },
        palyFizzy() {
            this.setComponentWithTimeout(Title, 4);

            setTimeout(() => {
                this.displayQuestion();
            }, this.componentTimeout * 1000 + 100);
        },

        displayQuestion() {
            this.setComponentWithTimeout(Question, 5);

            setTimeout(() => {
                this.setComponentWithTimeout(
                    FullQuestion,
                    this.currentQuestion.timer
                );
                // console.log("Question choices");

                this.counter = 0;

                axios.post("/api/send/question", {
                    question: this.currentQuestion,
                    gameID: this.game.id
                });

                this.displayAnswer();
            }, this.componentTimeout * 1000 + 100);
        },
        displayAnswer() {
            // console.log("display answer");
        },
        setComponentWithTimeout(component, timeout = 0) {
            this.currentComponent = component;
            this.componentTimeout = timeout;
        }
    }
};
</script>

<style>
.fz-cover {
    display: flex;
    flex-flow: column;
    height: 100vh;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.fz-header {
    padding: 2rem;
    margin: 0;
    font-size: 2rem;
    text-align: center;
}

.fz-cover-img {
    width: 100%;
    height: 100%;
    color: #34395e;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;
}

.fz-cover-header {
    position: absolute;
    top: 50%;
    left: 50%;
    font-size: 2.3rem;
    font-weight: 700;
    transform: translate(-50%, -50%);
}

.fz-cover-footer {
    position: absolute;
    top: 32px;
    left: 20px;
    font-size: 1.3rem;
    font-weight: 700;
}

.fz-que-img {
    height: 60%;
    display: flex;
}

.fz-que-img img {
    height: auto;
    max-width: 500px;
    max-height: 96%;
    margin: auto;
}

.fz-que-choices {
    height: 40%;
}

.fz-que-choices .fz-que-choice {
    height: 100%;
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    cursor: pointer;
}
.fz-que-choice p {
    line-height: 2.5rem !important;
    font-size: 1.6rem !important;
}
.fz-que-choice i {
    width: 1.6rem;
    font-size: 1.6rem;
}

.progress {
    height: 1rem;
    display: table;
}

.progress-bar {
    height: 100%;
    width: 0;
}
</style>
