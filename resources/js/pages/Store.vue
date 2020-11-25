<template>
    <div>
        <div class="br-mainpanel">
            <div class="pd-30">
                <h4 class="tx-gray-800 mg-b-5">Store Page</h4>
                <store-data-table/>
            </div>
        </div>
    </div>
</template>

<script>
    import storeDataTable from "../components/storePage/storeDataTable";
    export default {
        name: "store",
        components:{
            storeDataTable
        }
}
</script>

<style scoped>

</style>
