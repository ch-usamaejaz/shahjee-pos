<template>
    <div class="container">
        <v-alert
            color="red"
            dense
            dismissible
            prominent
            type="error"
        >Fuck you</v-alert>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
