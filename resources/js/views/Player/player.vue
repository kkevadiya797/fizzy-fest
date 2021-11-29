<template>
    <component
        :is="selectedComponent"
        :currentProps="currentProps"
        @next="nextComponent"
        :style="{ 'background-image': 'url(' + imageUrl + ')' }"
    />
</template>

<script>
import enterPin from "./enterPin.vue";
import enterNameVue from "./enterName.vue";
import displayNamePin from "./displayNamePin.vue";
import showChoices from "./showChoices.vue";
import result from "./result.vue";
import waiting from "./waiting.vue";
import scoreboard from "./scoreboard.vue";
export default {
    data() {
        return {
            pin: "",
            game: [],
            participant: [],
            name: "",
            answer: false,
            selectedComponent: "enter-pin",
            currentQuestion: [],
            timeout: false,
            imageUrl: "",
            totalPoints: 0,
            showQuestion: null
        };
    },
    mounted() {
        Echo.channel("StartGame").listen("StartGame", e => {
            if (this.currentProps.game.id == e.gameID) {
                this.showQuestion = e.showQuestion;
            }
        });
    },
    components: {
        "enter-pin": enterPin,
        "enter-name": enterNameVue,
        "display-name-pin": displayNamePin,
        "show-choices": showChoices,
        "result": result,
        "waiting": waiting,
        "scoreboard": scoreboard
    },
    created() {
        this.imageUrl =
            "/assets/images/cover/game_cover_" +
            Math.floor(Math.random() * 5 + 1) +
            ".svg";
    },
    computed: {
        currentProps() {
            return {
                game: this.game,
                participant: this.participant,
                e: this.e,
                answer: this.answer,
                question: this.currentQuestion,
                timeout: this.timeout,
                totalPoints: this.totalPoints,
                showQuestion: this.showQuestion
            };
        }
    },
    methods: {
        nextComponent(event, value, value2, value3) {
            this.selectedComponent = event;
            if (this.selectedComponent == "enter-name") {
                this.game = value[0];
            } else if (this.selectedComponent == "display-name-pin") {
                this.participant = value[0];
            } else if (this.selectedComponent == "waiting") {
                this.participant = value;
                this.answer = value2;
                this.timeout = value3;
            } else if (this.selectedComponent == "result") {
                this.totalPoints = this.participant.totalPoints;
            } else if (this.selectedComponent == "show-choices") {
                this.currentQuestion = value;
            }
        },
        startGame(start) {
            this.gameStarted = start;
        },
        resultcame(participant, answer) {
            console.log("true");
            this.participant = participant;
            this.answer = answer;
            this.selectedComponent = "waiting";
        },
        sendQuestion(e) {
            this.e = e;
            this.selectedComponent = "show-choices";
        },
        allResult() {
            this.selectedComponent = "result";
        }
    }
};
</script>
