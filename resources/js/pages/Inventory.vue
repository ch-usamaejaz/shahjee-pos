<template>
    <div>
        <div class="br-mainpanel">
            <div class="pd-30">
                <h4 class="tx-gray-800 mg-b-5">Inventory</h4>
                <printButton/>
            </div>
        </div>
    </div>
</template>

<script>
import printButton from "../components/printButton";
export default {
    name: 'inventory',
    components: {
        printButton
    }
}
</script>

<style scoped>

</style>