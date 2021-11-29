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
            Final Scoreboard
        </h1>

        <div class="position-relative">
            <div class="w-max-992 py-5 px-2 m-auto">
                <div class="fz-top-player mb-2">
                    <div v-if="participants[1]">
                        <img
                            src="/assets/images/cover/silver_trophy.png"
                            alt="first-rank"
                        />
                        <div>
                            <span>{{ participants[1].name }}</span>
                            <span>{{
                                participants[1].totalPoints
                            }}</span>
                        </div>
                    </div>
                    <div v-if="participants[0]">
                        <img
                            src="/assets/images/cover/gold_trophy.png"
                            alt="first-rank"
                        />
                        <div>
                            <span>{{ participants[0].name }}</span>
                            <span>{{
                                participants[0].totalPoints
                            }}</span>
                        </div>
                    </div>
                    <div v-if="participants[2]">
                        <img
                            src="/assets/images/cover/bronze_trophy.png"
                            alt="first-rank"
                        />
                        <div>
                            <span>{{ participants[2].name }}</span>
                            <span>{{
                                participants[2].totalPoints
                            }}</span>
                        </div>
                    </div>
                </div>
                <div
                    v-for="(player, key) in participants"
                    :key="key"
                >
                    <div
                        v-if="key > 2"
                        class="justify-content-between p-3 mb-2 fz-result"
                    >
                        <span>{{ ++key }} . {{ player.name }}</span>
                        <span>{{ player.totalPoints }}</span>
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
            participants: [],
            imageUrl: ""
        };
    },
    mounted() {
        this.imageUrl =
            "/assets/images/cover/game_cover_" +
            Math.floor(Math.random() * 5 + 1) +
            ".svg";

        axios.post('/api/getRank', {
            gameID: this.currentProps.game.id
        }).then(response => {
            this.participants = response.data;
        });
    }
};
</script>

<style scoped>
.fz-result {
    display: flex;
    font-size: 1.4rem;
    font-weight: 500;
    background-color: white;
    border-radius: 3px;
    box-shadow: 0 0 2rem 0 #5e5cb14d;
}
.fz-top-player {
    display: flex;
    justify-content: center;
    align-items: flex-end;
    margin-top: 150px;
    font-size: 1.4rem;
}
.fz-top-player > div {
    position: relative;
    background-color: white;
    width: 200px;
    border-radius: 3px;
    box-shadow: 0 0 2rem 0 #5e5cb14d;
}
.fz-top-player > div:nth-child(1) {
    height: 170px;
}
.fz-top-player > div:nth-child(2) {
    height: 200px;
    z-index: 1;
}
.fz-top-player > div:nth-child(3) {
    height: 140px;
}
.fz-top-player img {
    position: absolute;
    height: 150px;
    top: -150px;
    left: 50%;
    transform: translate(-50%, 0);
}
.fz-top-player > div > div {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: 500;
    text-align: center;
}
.fz-top-player span {
    display: block;
}
</style>
