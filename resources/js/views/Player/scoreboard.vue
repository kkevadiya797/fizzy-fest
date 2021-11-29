<template>
    <div class="fz-cover container-fluid p-0 h-100">
        <!-- Your Rank is :- {{ rank }} -->
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
        <div class="fz-message position-relative">
            <div v-if="rank <= 3" class="fz-top-player mb-2 px-md-5">
                <div v-if="rank == 1">
                    <img
                        src="/assets/images/cover/gold_trophy.png"
                        alt="first-place"
                    />
                    <span class="message-1">1<sup>st</sup> place</span>
                </div>
                <div v-else-if="rank == 2">
                    <img
                        src="/assets/images/cover/silver_trophy.png"
                        alt="second-place"
                    />
                    <span class="message-1">2<sup>nd</sup> place</span>
                </div>
                <div v-else>
                    <img
                        src="/assets/images/cover/bronze_trophy.png"
                        alt="third-place"
                    />
                    <span class="message-1">3<sup>td</sup> place</span>
                </div>
                <span class="message-2 px-md-5 px-3">
                    &quot;{{ getQuote() }}&quot;
                </span>
            </div>
            <div v-else class="px-md-5">
                <span class="message-1 px-md-5">
                    {{ currentProps.participant.totalPoints }} points
                </span>
                <span class="message-2 px-md-5 px-3">
                    &quot;{{ getQuote() }}&quot;
                </span>
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
            rank: null,
            quoteList: [
                { person: "Devendra", quote: "Learn or leave." },
                {
                    person: "Winston S. Churchill",
                    quote:
                        "Success is not final; failure is not fatal: It is the courage to continue that counts."
                },
                {
                    person: "Herman Melville",
                    quote:
                        "It is better to fail in originality than to succeed in imitation."
                }
            ]
        };
    },
    created() {
        axios
            .post("/api/getRank", {
                gameID: this.currentProps.participant.game_id,
                participant: this.currentProps.participant.id
            })
            .then(response => {
                this.rank = response.data;
            });
    },
    methods: {
        getQuote() {
            let range = this.quoteList.length;
            return this.quoteList[Math.floor(Math.random() * range)].quote;
        }
    }
};
</script>

<style>
.fz-top-player img {
    height: 150px;
}
</style>
