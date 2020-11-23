<template>
    <div>
        <div class="br-mainpanel">
            <div class="pd-30">
                <h4 class="tx-gray-800 mg-b-5">Inventory Page</h4>
                <inventoryDataTable/>
            </div>
        </div>
    </div>
</template>

<script>
import inventoryDataTable from '../components/inventoryPage/inventoryDataTable';
export default {
    name: 'inventory',
    components: {
        inventoryDataTable
    }
}
</script>

<style scoped>

</style>