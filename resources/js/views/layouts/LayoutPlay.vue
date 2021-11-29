<template>
    <div class="game-cover">
        <router-view />
    </div>
</template>

<style scoped>
.game-cover {
    height: 100vh;
    min-height: 500px;
}
.game-cover > div:first-child {
    overflow: auto;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
}
</style>
