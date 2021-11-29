<template>
    <div class="container-fluid p-0 h-100" :style="{ 'background-image': 'url(' + imageUrl + ')' }">
        <div class="bg-white text-center py-2 px-5 position-relative card">
            <img
                class="fz-logo-img position-absolute"
                src="/assets/images/fizzyfest-logo.png"
                alt="fizzyfest logo"
            />
            <span class="h1 font-weight-normal">
                Join at
                <b class>www.fizzyfest.com/play</b> with game pin:
            </span>
            <h1 class="display-4 font-weight-600">{{ currentProps.game.pin }}</h1>
        </div>
        <div class="py-3 px-5 mx-2">
            <div class="d-flex justify-content-between align-items-center pb-3">
                <!-- <div class="custom-control custom-checkbox">
                    <input
                        type="checkbox"
                        class="custom-control-input"
                        id="show_question"
                        v-model="showQuestion"
                    />
                    <label class="custom-control-label text-white font-weight-600" for="show_question">
                        Show question
                    </label>
                </div>-->
                <span class="h2 m-0 text-white">{{ countPlayers }} Players</span>
                <button
                    @click="startGame"
                    class="btn btn-primary"
                    :class="{'btn-primary-disabled': countPlayers == 0}"
                    :disabled="countPlayers == 0"
                    :title="countPlayers == 0 ? 'No player arrived yet' : ''"
                >Start Game</button>
            </div>
            <div>
                <ul class="row list-unstyled">
                    <li
                        v-for="participant in currentProps.participants"
                        :key="participant.id"
                        class="col-lg-3 col-md-4 col-sm-6 col-12 my-3 text-center"
                    >
                        <span class="h3 m-0 text-white">{{ participant.name }}</span>
                    </li>
                </ul>
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
    computed: {
        countPlayers() {
            return this.currentProps.participants.length;
        }
    },
    data() {
        return {
            imageUrl: "",
            showQuestion: false
        };
    },
    created() {
        this.imageUrl =
            "/assets/images/cover/game_cover_" +
            Math.floor(Math.random() * 4 + 1) +
            ".svg";
    },
    methods: {
        startGame() {
            this.$emit("next", "Title", this.showQuestion);
        }
    }
};
</script>

<style scoped>
.btn {
    height: 54px;
    font-size: 1rem;
}
.btn-primary-disabled:disabled,
.btn-primary-disabled:hover {
    background-color: #5c5cc1;
    border-color: #5c5cc1;
    opacity: 1;
    cursor: not-allowed;
}
</style>