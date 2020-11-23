<template>
    <div>
        <template>
            <v-card
                class="mx-auto text-center"
                color="rgb(2, 76, 196)"
                dark
            >
    <v-card-text>
      <v-sheet color="blue">
        <v-sparkline
          :value="value"
          color="rgba(255, 255, 255, .7)"
          height="100"
          padding="24"
          stroke-linecap="round"
          smooth
        >
          <template v-slot:label="item">
            Rs{{ item.value }}
          </template>
        </v-sparkline>
      </v-sheet>
    </v-card-text>

    <v-card-text>
      <div class="display-1 font-weight-thin">
        Sales Last 24h
      </div>
    </v-card-text>

    <v-divider></v-divider>
  </v-card>
</template>
    </div>
</template>

<script>
export default {
    name: 'chartComponent',
    data: () => ({
      value: [],
    }),
    mounted (){
        this.getChartData();
    },

    methods: {
        getChartData(){
            let url = "https://jsonplaceholder.typicode.com/users"
            this.axios.get(url).then((response)=>{
                let newAr = response.data.map((item)=>{
                    this.value.push(item.id);
                })
            },(error) =>{
                console.log(error);
            })
        }
    }
}
</script>

<style scoped>

</style>
