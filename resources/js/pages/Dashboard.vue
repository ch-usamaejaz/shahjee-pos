<template>
    <div class="br-mainpanel">
        <div class="pd-30">
            <h4 class="tx-gray-800 mg-b-5">Dashboard</h4>
            <div class=" mg-t-5">
                <stats-component v-if="!stats.error" :stats-data="stats"/>
            </div>
<!--            <div class="mg-t-5">-->
<!--                <chartComponent/>-->
<!--            </div>-->
        </div>
    </div>
</template>

<script>
import bottomBar from '../layouts/components/bottomBar';
import statsComponent from "../components/dashboardPage/statsComponent";
export default {
    name: 'dashboard',
    data () {
        return {
            stats : {}
        }
    },
    components: {
        bottomBar,
        statsComponent
    },
    mounted() {
        this.getStatsData();
    },
    methods : {
        getStatsData() {
            this.axios.get('get_dashboard_data')
                .then (resp => {
                    if(resp.error) {return}
                    this.stats = resp.data.data;
                })
                .catch(err => {
                    this.showErrorAlert(err.message)
                })
        }
    }
}
</script>

<style scoped>

</style>
