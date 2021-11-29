<template>
    <div class="fz-cover bg-white overflow-hidden" v-if="currentProps.fizzyData">
        <h1 class="fz-header">
            {{ currentProps.fizzyData.title }}
        </h1>
        <div class="progress rounded-0">
            <div
                class="progress-bar"
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
        <div
            class="fz-cover-img position-relative"
            :style="{ 'background-image': 'url(' + imageUrl + ')' }"
        >
            <div class="fz-cover-header py-2 px-3 bg-white">Are you ready?</div>
            <div class="fz-cover-footer text-center">
                <span class="m-0 py-2 px-3 bg-white"
                    > {{ currentProps.fizzyData.questions.length }} 
                    {{
                        currentProps.fizzyData.questions.length === 1
                            ? "Question"
                            : "Questions"
                    }} </span
                >
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
            imageUrl: null,
        };
    },
    mounted() {
        setTimeout(() => {
            this.isLoaded = true;
        }, 100);

        if(this.currentProps.fizzyData){
            if (this.currentProps.fizzyData.hasImage) {
                this.imageUrl =
                    "/storage/fizzy_cover/" + this.$route.params.token + ".jpg";
            } else {
                this.imageUrl = "/assets/images/cover/cover_992.svg";
            }
        }
    }
};
</script>
