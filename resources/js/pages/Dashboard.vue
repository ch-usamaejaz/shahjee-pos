<template>
    <div class="br-mainpanel">
        <div class="pd-30">
            <h4 class="tx-gray-800 mg-b-5">Dashboard</h4>
            <!-- <p class="mg-b-0">Do big things with Bracket, the responsive bootstrap 4 admin template.</p> -->
            <div class=" mg-t-5">
                <stats-component/>
            </div>
            <div class="mg-t-5">
                <chartComponent/>
            </div>
        </div>
    </div>
</template>

<script>
import bottomBar from '../layouts/components/bottomBar';
import statsComponent from "../components/dashboardPage/statsComponent";
import chartComponent from "../components/dashboardPage/chartComponent";
export default {
    name: 'dashboard',
    components: {
        bottomBar,
        statsComponent,
        chartComponent
    }
}
</script>

<style scoped>

</style>
