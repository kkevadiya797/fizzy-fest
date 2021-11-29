<template>
    <div v-if="data != null">
        <!-- <div> -->
        <header id="home">
            <!-- Header Start -->
            <div class="header-area header-transparrent ">
                <div class="main-header header-sticky">
                    <div class="container">
                        <div class="row align-items-center">
                            <!-- Logo -->
                            <div class="col-xl-2 col-lg-2 col-md-1">
                                <div class="logo">
                                    <a><img
                                            src="/assets/images/logo/fizzyfest-logo.png"
                                            alt=""
                                            width="120px"
                                    /></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10 col-md-10">
                                <!-- Main-menu -->
                                <div
                                    class="main-menu f-right d-none d-lg-flex align-items-center"
                                >
                                    <nav>
                                        <ul id="navigation">
                                            <li>
                                                <a href="#home"> 
                                                    Home
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#work">
                                                    How it's work?
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#features">
                                                    Features
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#about-us">
                                                    About Us
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                <div
                                    class="header-right-btn pl-5"
                                >
                                    <a href="/play" class="btn header-btn btn-play">Play</a>
                                    <a href="/app/login" class="btn header-btn">Login</a>
                                </div>
                                </div>
                            </div>
                            <!-- Mobile Menu -->
                            <div class="col-12">
                                <div
                                    class="mobile_menu d-block d-lg-none"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Header End -->
        </header>

        <main>
            <!-- Slider Area Start-->
            <div
                v-if="data.components.hero.status"
                class="slider-area"
                style="overflow: hidden;">
                <div class="slider-active">
                    <div
                        class="single-slider slider-height d-flex align-items-center"
                        data-background="/assets/visitor/img/hero/h1_hero.png"
                    >
                        <div class="container">
                            <div class="row d-flex align-items-center">
                                <div class="col-lg-6 col-md-9 ">
                                    <div class="hero__caption">
                                        <h1
                                            data-animation="fadeInLeft"
                                            data-delay=".4s"
                                        >
                                            {{ data.contents.hero[0].title }}
                                        </h1>
                                        <p
                                            data-animation="fadeInLeft"
                                            data-delay=".6s"
                                        >
                                            {{
                                                data.contents.hero[0]
                                                    .description
                                            }}
                                        </p>
                                        <div
                                            class="hero__btn"
                                            data-animation="fadeInLeft"
                                            data-delay=".8s"
                                        >
                                            <a
                                                href="/app/register"
                                                class="btn btn-primary hero-btn"
                                                >Join us</a
                                            >
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div
                                        class="hero__img d-none d-lg-block"
                                        data-animation="fadeInRight"
                                        data-delay="1s"
                                    >
                                        <img
                                            :src="
                                                '/storage/web/hero/' +
                                                    data.contents.hero[0]
                                                        .image_url
                                            "
                                            alt="hero-img"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- fizzyfst at -->
            <div
                v-if="data.components.fizzyfest_at.status"
                class="we-create-area py-5"
            >
                <div
                    v-for="card in data.contents.fizzyfest_at"
                    :key="card.id"
                    class="container"
                >
                    <div class="row d-flex align-items-end">
                        <div class="col-lg-6 col-md-12 re-order">
                            <div class="we-create-img">
                                <img
                                    :src="
                                        '/storage/web/fizzyfest_at/' + card.image_url
                                    "
                                    :alt="card.image_url"
                                />
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12">
                            <div class="we-create-cap">
                                <h3>
                                    {{ card.title }}
                                </h3>
                                <p>
                                    {{ card.description }}
                                </p>
                                <a :href="card.link_url" class="btn btn-primary"
                                    >Learn more</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- how fizzyfest work -->
            <div class="what-we-do py-5 position-relative">
                <span id="work" class="position-absolute scroll-point"></span>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-tittle text-center mb-5">
                                <span>{{ data.components.fizzyfest_work.sub_title }}</span>
                                <h2>{{ data.components.fizzyfest_work.title }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div
                            v-for="card in data.contents.fizzyfest_work"
                            :key="card.id"
                            class="col-lg-4 col-md-6"
                        >
                            <div class="single-do text-center mb-30">
                                <div class="do-icon">
                                    <span :class="card.image_url"></span>
                                </div>
                                <div class="do-caption">
                                    <h4>{{ card.title }}</h4>
                                    <p class="m-0">
                                        {{ card.description }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- features -->
            <div class="py-5 bg-light position-relative">
                <span id="features" class="position-absolute scroll-point"></span>
                <div class="container">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-8">
                            <div class="section-tittle text-center mb-5">
                                <span>which feature you get here?</span>
                                <h2>Features we provide</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 feature-card">
                            <div class="single-do text-center m-auto">
                            <div class="do-icon">
                                <span class="flaticon-checklist text-success"></span>
                            </div>
                            <div class="do-caption">
                                <h4>Get Features</h4>
                                <p>Get all this feature in your account, here <a href="/register" class="text-info">Join us</a></p>
                            </div>
                        </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="py-3">
                                <ul class="feature-list">
                                    <li>User friendly dashboard, where users can easily get overall information</li>
                                    <li>Can create unlimeted quizzes</li>
                                    <li>Can host game</li>
                                    <li>Get game report</li>
                                    <li>Can access global quizzes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- user say about us -->
            <div class="testimonial-area py-5 position-relative">
                <span id="about-us" class="position-absolute scroll-point"></span>
                <div class="container">
                    <div class="testimonial-main">
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-8">
                                <div class="section-tittle text-center mb-5">
                                    <span>{{ data.components.client_say.sub_title }}</span>
                                    <h2>{{ data.components.client_say.title }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-10 col-md-9">
                                <div class="h1-testimonial-active">
                                    <div
                                        v-for="card in data.contents.client_say"
                                        :key="card.id"
                                        class="single-testimonial text-center"
                                    >
                                        <div class="testimonial-caption ">
                                            <div class="testimonial-top-cap">
                                                <p>
                                                    {{ card.description }}
                                                </p>
                                            </div>
                                            <div
                                                class="testimonial-founder d-flex align-items-center justify-content-center"
                                            >
                                                <div class="founder-img">
                                                    <img
                                                        :src="
                                                            '/storage/web/client_say/' +
                                                                card.image_url
                                                        "
                                                        :alt="card.image_url"
                                                    />
                                                </div>
                                                <div class="founder-text">
                                                    <span>{{
                                                        card.title
                                                    }}</span>
                                                    <p>{{ card.sub_title }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- have-project Start-->
            <div v-if="data.components.signup_card.status" class="have-project pt-5">
                <div class="container">
                    <div
                        v-for="card in data.contents.signup_card"
                        :key="card.id"
                        class="haveAproject"
                        :data-background="'/storage/web/signup_card/' + card.image_url"
                    >
                        <div class="row d-flex align-items-center">
                            <div class="col-xl-7 col-lg-9 col-md-12">
                                <div class="wantToWork-caption">
                                    <h2>{{ card.title }}</h2>
                                    <p>
                                        {{ card.description }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-3 col-md-12">
                                <div class="wantToWork-btn f-right">
                                    <a :href="card.link_url" class="btn btn-ans"
                                        >Sign Up</a
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- have-project End -->
        </main>

        <footer>
            <!-- Footer Start-->
            <div
                class="footer-main"
                data-background="/assets/visitor/img/shape/footer_bg.png"
            >
                <div class="footer-area footer-padding">
                    <div class="container">
                        <div class="row d-flex justify-content-between">
                            <div class="col-lg-3 col-md-4 col-sm-8">
                                <div class="single-footer-caption mb-50">
                                    <div class="single-footer-caption mb-30">
                                        <!-- logo -->
                                        <div class="footer-logo">
                                            <a>
                                            <img
                                                src="/assets/images/logo/fizzyfest-logo.png"
                                                alt=""
                                                width="120px"
                                            /></a>
                                        </div>
                                        <div class="footer-tittle">
                                            <div class="footer-pera">
                                                <p class="info1">
                                                    Sutex collage of computer<br/> 
                                                    applications and science,<br/>
                                                    Amroli - 395004
                                                </p>
                                                <p class="info2">
                                                    contact@fizzyfest.com
                                                </p>
                                            </div>
                                        </div>
                                        <div class="footer-social">
                                            <a href="#" class="facebook"
                                                ><i
                                                    class="fab fa-facebook-f"
                                                ></i
                                            ></a>
                                            <a href="#" class="twitter"
                                                ><i class="fab fa-twitter"></i
                                            ></a>
                                            <a href="#" class="instagram"
                                                ><i class="fab fa-instagram"></i
                                            ></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-5">
                                <div class="single-footer-caption mb-50">
                                    <div class="footer-tittle">
                                        <h4>Quick Links</h4>
                                        <ul>
                                            <li>
                                                <a href="#home">Home</a>
                                            </li>
                                            <li>
                                                <a href="#features">Features</a>
                                            </li>
                                            <li>
                                                <a href="#work">How It's Work?</a>
                                            </li>
                                            <li>
                                                <a href="#about-us">About Us</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4 col-sm-7">
                                <div class="single-footer-caption mb-50">
                                    <div class="footer-tittle">
                                        <h4>Support</h4>
                                        <ul>
                                            <li>
                                                <a href="#">Privacy Policy</a>
                                            </li>
                                            <li>
                                                <a href="#"
                                                    >Terms & Conditions</a
                                                >
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-4 col-sm-5">
                                <div class="single-footer-caption mb-50">
                                    <div class="footer-tittle">
                                        <h4>Core Features</h4>
                                        <ul>
                                            <li>
                                                <a href="#"> 24/7 Support</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- footer-bottom aera -->
                <div class="footer-bottom-area footer-bg">
                    <div class="container">
                        <div class="footer-border">
                            <div class="row d-flex align-items-center">
                                <div class="col-xl-12 ">
                                    <div class="footer-copy-right text-center">
                                        <p>
                                            Copyright &copy; 2020 All rights
                                            reserved by <a href="/home" class="text-primary">Fizzyfest</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End-->
        </footer>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: null
        };
    },
    mounted() {
        this.loadData();
    },
    methods: {
        async loadData() {
            let { data } = await axios.get("/api/theme/1");

            this.data = data;

            this.dynamicallyLoadScript("/assets/visitor/js/main.js");
        },
        dynamicallyLoadScript(url) {
            var script = document.createElement("script");
            script.src = url;

            document.head.appendChild(script);
        }
    }
};
</script>
