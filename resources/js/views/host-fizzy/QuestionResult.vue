<template>
    <div
        class="fz-cover"
        :style="{ 'background-image': 'url(' + imageUrl + ')' }"
    >
        <h1 class="fz-header bg-white card">
            <img
                class="fz-logo-img position-absolute"
                src="/assets/images/fizzyfest-logo.png"
                alt="fizzyfest logo"
            />
            Scoreboard
        </h1>
        <div class="position-relative">
            <button @click="nextQuestion" class="btn btn-primary fz-btn-next">
                Next
            </button>
            <div class="w-max-992 py-5 px-2 m-auto">
                <div
                    class="justify-content-between p-3 mb-2 fz-result"
                    v-for="(player, key) in participants"
                    :key="key"
                >
                    <span>{{ ++key }} . {{ player.name }}</span>
                    <span>{{ player.points }}</span>
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
            participants: null,
            imageUrl: ""
        };
    },
    created() {
        this.imageUrl =
            "/assets/images/cover/game_cover_" +
            Math.floor(Math.random() * 5 + 1) +
            ".svg";

        axios.post('/api/getRank', {
            gameID: this.currentProps.game.id,
            QuestionResult: true
        }).then(response => {
            this.participants = response.data;
        });
    },
    methods: {
        nextQuestion() {
            this.$emit("next");
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
.fz-result {
    display: flex;
    font-size: 1.4rem;
    font-weight: 500;
    box-shadow: 0 0 2rem 0 #5e5cb14d;
    border-radius: 3px;
    background-color: white;
}
</style>
