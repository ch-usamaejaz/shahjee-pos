<template>
    <div class="row row-sm">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-teal rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                    <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">Today's Order</p>
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{todayOrders}}</p>
                        <!-- <span class="tx-11 tx-roboto tx-white-6">24% higher yesterday</span> -->
                    </div>
                </div>
            </div>
        </div><!-- col-3 -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-sm-t-0">
            <div class="bg-danger rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                    <i class="ion ion-bag tx-60 lh-0 tx-white op-7"></i>
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">Today Sales</p>
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{todaySales}}</p>
                        <!-- <span class="tx-11 tx-roboto tx-white-6">$390,212 before tax</span> -->
                    </div>
                </div>
            </div>
        </div><!-- col-3 -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="bg-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                    <i class="ion ion-monitor tx-60 lh-0 tx-white op-7"></i>
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">Yesterday Sales</p>
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{yesterdaySales}}</p>
                        <!-- <span class="tx-11 tx-roboto tx-white-6">23% average duration</span> -->
                    </div>
                </div>
            </div>
        </div><!-- col-3 -->
        <div class="col-sm-6 col-xl-3 mg-t-20 mg-xl-t-0">
            <div class="bg-br-primary rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                    <i class="ion ion-clock tx-60 lh-0 tx-white op-7"></i>
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">More than yesterday</p>
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{moreThanYesterday}}%</p>
                        <!-- <span class="tx-11 tx-roboto tx-white-6">65.45% on average time</span> -->
                    </div>
                </div>
            </div>
        </div><!-- col-3 -->
    </div>

</template>

<script>
    export default {
        name: "statsComponent",
        data(){
            return{
                todaySales: '',
                yesterdaySales: '',
                todayOrders: '',
                moreThanYesterday: ''
            }
        },
        mounted(){
            this.getStatsData()
        },

        methods: {
            getStatsData(){
                let url = 'https://jsonplaceholder.typicode.com/posts';
                this.axios.get(url).then((response) => {
                    this.todaySales = response.data[1].id;
                    this.yesterdaySales = response.data[5].id;
                    this.todayOrders = response.data[55].id;
                    this.moreThanYesterday = response.data[98].id;
                }, (error)=>{
                    console.log(error);
                })
            }
        }
    }
</script>

<style scoped>

</style>
