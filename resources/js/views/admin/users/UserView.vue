<template>
    <div>
        <div class="section-header">
            <h1>User details</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'dashboard' }">Home</router-link>
                </div>
                <div class="breadcrumb-item active">
                    <router-link :to="{ name: 'users.index' }"
                        >users</router-link
                    >
                </div>
                <div class="breadcrumb-item">User details</div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-8 mx-auto">
                <div class="card">
                    <!-- <form  class="needs-validation" novalidate> -->
                    <form @submit.prevent="" autocomplete="off">
                        <div class="card-header">
                            <h4>User details</h4>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label
                                            class="form-control-label"
                                            for="cover-image"
                                            >Profile image
                                        </label>
                                        <div
                                            class="mb-3 input-file-message line-height-0"
                                        >
                                            <img
                                                class="input-file-image"
                                                :src="coverImage.url"
                                                alt="fizzy-cover"
                                                :class="{
                                                    'opacity-0': !coverImage.url
                                                }"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            v-model="userDetails.userName"
                                            readonly
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Total Fizzy</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            v-model="userDetails.totalFizzies"
                                            readonly
                                        />
                                    </div>
                                    <div class="form-group ">
                                        <label for="email">Total Plays</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            v-model="userDetails.totalPlays"
                                            readonly
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label>Total Players</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            v-model="userDetails.totalPlayers"
                                            readonly
                                        />
                                    </div>
                                    <div class="form-group">
                                        <label>Total Reports</label>
                                        <input
                                            type="number"
                                            class="form-control"
                                            v-model="userDetails.totalReoprts"
                                            readonly
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <loading
            :active.sync="isLoading"
            :is-full-page="true"
            :color="'#3a3ab1'"
            :loader="'bars'"
        ></loading>
    </div>
</template>

<script>
import Loading from "vue-loading-overlay";

export default {
    components: {
        Loading
    },
    data() {
        return {
            coverImage: {
                file: null,
                name: "",
                url: ""
            },
            isLoading: false,
            userDetails: {}
        };
    },
    mounted() {
        this.getUserDetail()
    },
    methods: {
        async getUserDetail() {
            this.isLoading = true;
            try {
                let { data } = await axios.get(
                    `/api/user/${this.$route.params.id}`
                );
                this.userDetails = data;

                if (this.userDetails.hasImage) {
                    this.coverImage.url =
                        "/storage/avatar/" + this.$route.params.id + ".jpg";
                    this.coverImage.file = true;
                    this.coverImage.name = this.userDetails.userName;
                }
            } catch (e) {
                //page not found
                if (e.response.status === 404) {
                    this.$router.push({ name: "404" });
                }
            }
            this.isLoading = false;
        },
    }
};
</script>

<style scoped>

</style>
