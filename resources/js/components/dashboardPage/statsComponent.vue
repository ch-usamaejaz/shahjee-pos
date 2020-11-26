<template>
    <div class="row row-sm">
        <div class="col-sm-6 col-xl-3">
            <div class="bg-teal rounded overflow-hidden">
                <div class="pd-25 d-flex align-items-center">
                    <i class="ion ion-earth tx-60 lh-0 tx-white op-7"></i>
                    <div class="mg-l-20">
                        <p class="tx-10 tx-spacing-1 tx-mont tx-medium tx-uppercase tx-white-8 mg-b-10">Today's Order</p>
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{statsData.today_orders}}</p>
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
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{statsData.today_sales}} PKR</p>
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
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{statsData.yesterday_sales}} PKR</p>
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
                        <p class="tx-24 tx-white tx-lato tx-bold mg-b-2 lh-1">{{statsData.increase_percentage != "" ? statsData.increase_percentage + '%' : 'N/A'}}</p>
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
        props: ['statsData']
    }
</script>

<style scoped>

</style>
