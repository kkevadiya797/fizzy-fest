<template>
    <div class="fz-cover container-fluid p-0 h-100">
        <div class="fz-name-score bg-white text-center py-2 px-3 card fz-header-play m-0">
            <div class="fz-player-name m-0">
                <img
                    class="fz-logo-img position-absolute"
                    src="/assets/images/fizzyfest-logo.png"
                    alt="fizzyfest logo"
                />
                {{ currentProps.participant.name }}
            </div>
        </div>
        <div class="fz-message">
            <div>
                <span class="message-1"
                    >Hi! {{ currentProps.participant.name }}</span
                >
                <span class="message-2">See your nickname on screen</span>
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
        Echo.channel("StartGame").listen("StartGame", e => {
            if (this.currentProps.game.id == e.gameID) {
                this.$emit("next", "show-choices");
            }
        });
    }
};
</script>
