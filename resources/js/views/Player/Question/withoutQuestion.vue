<template>
    <div>
        <div
            v-for="(choice, index) in currentProps.currentQuestion.choices"
            :key="index"
            :style="`height: ${100 / noOfOptions}%;`"
        >
            <button
                class="h-100 w-100 btn"
                :class="optoinClasses.bgColors[index]"
                @click="setAnswer(choice)"
            >
                <i
                    class="text-white fas"
                    :class="optoinClasses.icons[index]"
                ></i>
            </button>
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
            optoinClasses: {
                bgColors: [
                    "btn-danger",
                    "btn-warning",
                    "btn-success",
                    "btn-info"
                ],
                icons: ["fa-heart", "fa-star", "fa-circle", "fa-square"]
            }
        };
    },
    methods: {
        setAnswer(choice){
            this.$emit("next", choice);
        }
    },
    computed: {
        noOfOptions() {
            return this.currentProps.currentQuestion.choices.length;
        }
    }
};
</script>

<style scoped></style>
