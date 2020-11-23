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
            // this.axios.post('create_new_item', {item_price : 1000, item_name : 'Kheer'})
            //     .then (resp => {
            //         console.log(resp);
            //     })
            //     .catch(err => {
            //         console.log(err.message)
            //     })
        }
    }
</script>
