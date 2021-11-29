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
        <div class="fz-message">
            <div v-if="currentProps.timeout">
                <span class="message-1">Time's up</span>
                <span class="message-1"><i class="fas fa-clock"></i></span>
                <span class="message-2">Do your best next time.</span>
            </div>
            <div v-else-if="currentProps.answer">
                <span class="message-1">Correct</span>
                <span class="message-1"><i class="fas fa-check"></i></span>
                <span class="message-2"
                    >+ {{ currentProps.participant.points }}</span
                >
                <span class="message-2">Congratulations!</span>
            </div>
            <div v-else>
                <span class="message-1">Incorrect</span>
                <span class="message-1"><i class="fas fa-times"></i></span>
                <span class="message-2">Do your best next time.</span>
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
    mounted() {
        Echo.channel("SendQuestion").listen("SendQuestionEvent", e => {
            if (this.currentProps.participant.game_id == e.gameID) {
                this.$emit("next", "show-choices", e.question);
            }
        });

        Echo.channel("scoreBoard").listen("ScoreBoardEvent", e => {
            if(this.currentProps.participant.game_id == e.gameID) {
                this.$emit("next", "scoreboard");
            }
        });
    }
};
</script>
