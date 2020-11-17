<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="orders"
            :options.sync="options"
            :server-items-length="totalOrders"
            :loading="loading"
            class="elevation-1"
        ></v-data-table>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                totalOrders: 0,
                orders: [],
                loading: true,
                options: {},
                headers: [
                    { text: 'Order#', align: 'start', value: 'id'},
                    { text: 'Order Value ', value: 'order_total', sortable: false },
                    { text: 'Order Status', value: 'order_status', sortable: false}
                ],
            }
        },
        watch: {
            options: {
                handler () {
                    this.getDataFromApi()
                },
                deep: true,
            },
        },
        methods: {
            getDataFromApi () {
                this.loading = true;
                this.options['user_id'] = 1;
                let items = this.getOrders (this.options);
            },
            getOrders (order_data) {
                this.axios.post('/get_user_orders', order_data)
                    .then(response => {
                        this.orders = response.data.orders;
                        this.totalOrders = response.data.orders.length;
                        this.loading = false
                    }).catch (error => {
                        console.log(error.message)
                })
            }
        }
    }
</script>
