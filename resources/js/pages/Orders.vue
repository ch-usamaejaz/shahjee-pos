<template>
    <div>
        <div class="br-mainpanel">
            <div class="pd-30">
                <h4 class="tx-gray-800 mg-b-5">Orders Page</h4>
                <ordersDataTable/>
            </div>
        </div>
    </div>
</template>

<script>
import ordersDataTable from '../components/ordersPage/ordersDataTable';
    export default {
        name: "orders",
        components:{
            ordersDataTable
        }
}
</script>

<style scoped>

</style>
