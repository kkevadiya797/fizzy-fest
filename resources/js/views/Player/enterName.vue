<template>
    <div
        class="container-fluid p-0 h-100"
    >
        <div class="fz-name-score bg-white text-center py-2 px-5 position-relative card fz-header-play">
            <img
                class="fz-logo-img position-absolute"
                src="/assets/images/fizzyfest-logo.png"
                alt="fizzyfest logo"
            />
        </div>
        <div>
            <div class="form-login m-auto">
                <div class="px-3 py-5">
                    <div class="card">
                        <validation-observer ref="form">
                            <form @submit.prevent="send">
                                <div class="card-header d-block">
                                    <h3 class="text-center">Enter nickname</h3>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="form-group">
                                        <validation-provider
                                            mode="lazy"
                                            rules="required"
                                            #default="{ errors, classes }"
                                        >
                                            <div :class="classes">
                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    placeholder="Nickname"
                                                    name="nickname"
                                                    v-model="name"
                                                    autofocus
                                                />
                                                <span>{{ errors[0] }}</span>
                                            </div>
                                        </validation-provider>
                                    </div>
                                </div>
                                <div class="card-footer text-center pt-0">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                        :class="{
                                            'btn-progress disabled': isProcessing
                                        }"
                                    >
                                        Enter
                                    </button>
                                </div>
                            </form>
                        </validation-observer>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ValidationProvider, ValidationObserver } from "vee-validate";
import "../../vee-validation.js";

export default {
    props: {
        currentProps: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            name: "",
            isProcessing: false
        };
    },
    components: {
        ValidationProvider,
        ValidationObserver
    },
    methods: {
        async send() {
            let success = await this.$refs.form.validate();

            if (!success) {
                return;
            }

            this.isProcessing = true;
            try {
                await axios
                    .post("/api/setName/send", {
                        name: this.name,
                        game: this.currentProps.game
                    })
                    .then(response => {
                        this.$emit("next", "display-name-pin", response.data);
                    });
            } catch (error) {
                console.log(error);
            }
            this.isProcessing = false;
        }
    }
};
</script>

<style scoped>
.fz-logo-img {
    left: 50%;
    transform: translate(-50%, -50%);
}
</style>
