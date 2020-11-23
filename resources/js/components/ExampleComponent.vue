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
            // console.log('Component mounted.')
            this.axios.post('update_item', {item_id : 3, item_price : 1000, item_name : 'Chapli Kebab'})
                .then (resp => {
                    console.log(resp);
                })
                .catch(err => {
                    console.log(err.message)
                })
        }
    }
</script>
