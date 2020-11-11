<template>
    <section id="container">
        <div>
            <div>
                <top-bar/>
                <nav-bar/>
                <RouterView :key="$route.fullPath" />
                <bottom-bar/>
            </div>
        </div>
    </section>
</template>
<script>
import topBar from "../layouts/components/topBar";
import bottomBar from "../layouts/components/bottomBar"
import navBar from "./components/navBar";
export default {
    name: "authenticatedLayout",
    components:{
        topBar,
        navBar,
        bottomBar
    }
}
</script>
<style scoped>
</style>
