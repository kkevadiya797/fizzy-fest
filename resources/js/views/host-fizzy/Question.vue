<template>
    <div
        class="fz-cover overflow-hidden"
        :style="{ 'background-image': 'url(' + imageUrl + ')' }"
    >
        <div class="progress rounded-0 m-2">
            <div
                class="progress-bar bg-white"
                role="progressbar"
                aria-valuenow="10"
                aria-valuemin="0"
                aria-valuemax="100"
                :style="{
                    transition: `${currentProps.componentTimeout}s linear`
                }"
                :class="{ 'w-100': isLoaded }"
            ></div>
        </div>
        <div class="fz-cover-img position-relative">
            <div class="fz-cover-footer text-center">
                <span class="m-0 py-2 px-3 bg-white"
                    >{{ currentProps.indexQuestion + 1 }} of
                    {{ currentProps.fizzyData.questions.length }}</span
                >
            </div>
            <div class="fz-cover-header py-2 px-3 bg-white">
                {{ currentProps.currentQuestion.question }}
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
            isLoaded: false,
            imageUrl: ""
        };
    },
    created() {
        this.imageUrl =
            "/assets/images/cover/game_cover_" +
            Math.floor(Math.random() * 5 + 1) +
            ".svg";

        setTimeout(() => {
            this.isLoaded = true;
        });

        // console.log(this.currentProps);
    }
};
</script>

<style scoped>
.progress {
    background-color: transparent;
}
</style>
