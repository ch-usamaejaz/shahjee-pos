<template>
    <div>
        <router-view :key="$route.fullPath"></router-view>
        <bottom-bar></bottom-bar>
    </div>
</template>

<script>

import bottomBar from "./components/bottomBar";
import topBar from "./components/topBar";

export default {
    name: "defaultLayout",
    components : {
        bottomBar,
        topBar
    }
}
</script>
<style scoped>
</style>
