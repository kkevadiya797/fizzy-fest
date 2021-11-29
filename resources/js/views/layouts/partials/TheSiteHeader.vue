<template>
    <div class="navbar-fixed">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar">
            <div class="sidebar-brand">
                <router-link
                    to="{ name: 'dashboard' }"
                    class="text-brand h2 m-0 text-white"
                >
                    <img
                        src="/assets/images/logo/fizzyfest-logo-white.png"
                        alt="fizzyfest-logo"
                    />
                </router-link>
            </div>
            <div class="mr-auto">
                <ul class="navbar-nav">
                    <li>
                        <a
                            href="#"
                            data-toggle="sidebar"
                            class="nav-link nav-link-lg"
                            ><i class="fas fa-bars"></i
                        ></a>
                    </li>
                </ul>
            </div>
            <ul class="navbar-nav navbar-right">
                <li>
                    <router-link
                        tag="button"
                        :to="{ name: 'fizzies.create' }"
                        class="btn btn-light btn-icon d-md-none"
                    >
                        <i class="fas fa-plus"></i>
                    </router-link>
                    <router-link
                        tag="button"
                        :to="{ name: 'fizzies.create' }"
                        class="btn btn-light d-none d-md-block"
                    >
                        Create
                    </router-link>
                </li>
                <li class="dropdown">
                    <a
                        href="#"
                        data-toggle="dropdown"
                        class="nav-link dropdown-toggle nav-link-lg nav-link-user"
                    >
                        <img
                            alt="image"
                            :src="user.imageUrl"
                            class="mr-1"
                            style="border-radius:3px;"
                        />
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mt-2">
                        <div class="dropdown-title">Welcome!</div>
                        <router-link
                            :to="{ name: 'settings.profile' }"
                            class="dropdown-item has-icon"
                        >
                            <i class="fas fa-user"></i> My profile
                        </router-link>
                        <div class="dropdown-divider"></div>
                        <a
                            class="dropdown-item has-icon text-danger"
                            @click="logout"
                        >
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {
                name: "Gippy Momo",
                imageUrl: "/assets/images/avatar/default-avatar.jpg"
            }
        };
    },
    methods: {
        logout() {
            localStorage.removeItem("auth/isAuthenticated");
            this.$store.commit("removeUser");
            this.$router.push({ name: "login" });
        }
    }
};
</script>

<style scoped>
.navbar-fixed > div,
.navbar-fixed > nav {
    position: fixed !important;
    z-index: 2;
}

.navbar-bg {
    height: 70px;
}

.navbar .sidebar-brand {
    display: none;
    position: absolute;
    left: 50%;
    transform: translate(-50%, 0%);
}

@media (max-width: 1024px) {
    .navbar .sidebar-brand {
        display: block;
    }
}
</style>
