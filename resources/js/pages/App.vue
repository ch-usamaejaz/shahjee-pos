<template>
    <component v-bind:is="layout"></component>
</template>

<script>
    /* eslint-disable */
    import defaultLayout from "../layouts/defaultLayout";
    import authenticatedLayout from "../layouts/authenticatedLayout";


    export default {
        name: "App",
        components: {
            'Default': defaultLayout,
            'Authenticated': authenticatedLayout
        },
        computed: {
            layout () {
                return (this.$route.meta.layout === undefined) ? 'Default' : 'Authenticated';
            },
        },
    };
</script>

<style scoped>

</style>
