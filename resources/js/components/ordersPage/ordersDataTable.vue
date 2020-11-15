<template>
    <div>
        <div class="mg-t-5">
            <v-data-table
            :headers="headers"
            :items="desserts"
            :options.sync="options"
            :server-items-length="totalDesserts"
            :loading="loading"
            class="elevation-1"
            >
            <template v-slot:top>
                <v-toolbar
                    flat
                >
                <v-toolbar-title>All Orders</v-toolbar-title>
                <v-divider
                    class="mx-4"
                    inset
                    vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-dialog
                    v-model="dialog"
                    max-width="500px"
                >
                    <template v-slot:activator="{ on, attrs }">
                        <v-btn
                            color="primary"
                            dark
                            class="mb-2"
                            v-bind="attrs"
                            v-on="on"
                        >
                        New Order
                        </v-btn>
                            </template>
                                <v-card>
                                    <v-card-title>
                                        <span class="headline">{{ formTitle }}</span>
                                    </v-card-title>

                                    <v-card-text>
                                        <v-container>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="4"
                                                >
                                                    <v-select
                                                        :items="newItems"
                                                        label="Menu Item"
                                                        v-model="editedItem.name"
                                                        outlined
                                                    ></v-select>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="2"
                                                >
                                                    <v-text-field
                                                        v-model="editedItem.number"
                                                        label="Quantity"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                    cols="12"
                                                    sm="6"
                                                    md="2"
                                                >
                                                    <v-text-field
                                                        v-model="editedItem.price"
                                                        label="Total Price"
                                                    ></v-text-field>
                                                </v-col>
                                                <v-col
                                                cols="12"
                                                sm="6"
                                                md="4"
                                                >
                                                  <v-select 
                                                  label="Status"
                                                  :items="status"
                                                  v-model="editedItem.status"
                                                  outlined
                                                  >
                                                  </v-select>
                                                </v-col>
                                            </v-row>
                                            <div class="text-center">
                                                <v-btn
                                                  class="mx-2"
                                                  fab
                                                  dark
                                                  color="indigo"
                                                  v-bind="rowArray"
                                                  @click="addNewRow"
                                                >
                                                <v-icon dark>
                                                  mdi-plus
                                                </v-icon>
                                              </v-btn>
                                            </div>

                                        </v-container>
                                    </v-card-text>

                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="close"
                                        >
                                            Cancel
                                        </v-btn>
                                        <v-btn
                                            color="blue darken-1"
                                            text
                                            @click="save"
                                        >
                                            Save
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                            <v-dialog v-model="dialogDelete" max-width="500px">
                                <v-card>
                                    <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
                                        <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                                        <v-spacer></v-spacer>
                                    </v-card-actions>
                                </v-card>
                            </v-dialog>
                        </v-toolbar>
                    </template>
                    <template v-slot:item.actions="{ item }">
                        <v-icon
                            color="primary"
                            small
                            class="mr-2"
                            @click="editItem(item)"
                        >
                            mdi-pencil
                        </v-icon>
                        <v-icon
                            color="red"
                            small
                            @click="deleteItem(item)"
                        >
                            mdi-delete
                        </v-icon>
                    </template>
                    <template v-slot:no-data>
                        <v-btn
                            color="primary"
                            @click="getData"
                        >
                            Reset
                        </v-btn>
                    </template>
            </v-data-table>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ordersDataTable',
    
    data () {
      return {
        rowArray: [{
          menuItem: '',
          quantity
        }],
        status: ['Paid', 'Unpaid'],
        newItems: ['Karahi', 'Coke', 'Naan'],
        dialog: false,
        dialogDelete: false,  
        totalDesserts: 0,
        desserts: [],
        loading: true,
        options: {},
        headers: [
          {
            text: 'Order#',
            align: 'start',
            value: 'number',
          },
          { text: 'Status', value: 'status' },
          { text: 'Total (Rs)', value: 'price' },
          { text: 'Actions', value: 'actions', sortable: false },
        ],
        editedIndex: -1,
            editedItem: {
                number: 0,
                name: '',
                price: 0,
                status: ''
            },
            defaultItem: {
                number: 0,
                name: '',
                price: 0,
                status: ''
            },
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
     computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Order' : 'Edit Order'
            },
        },
    mounted () {
      this.getDataFromApi();
      this.getRealData();
    },
    methods: {
        getRealData (){
            let url = 'https://jsonplaceholder.typicode.com/posts';
                this.axios.get(url).then((response)=>{
                    response.data = this.desserts[{...response.data}];
                    console.log(response);
                },(error)=>{
                    console.log(error);
            })
        },
      getDataFromApi () {
        this.loading = true
        this.fakeApiCall().then(data => {
          this.desserts = data.items
          this.totalDesserts = data.total
          this.loading = false
        })
      },
      /**
       * In a real application this would be a call to fetch() or axios.get()
       */
      fakeApiCall () {
        return new Promise((resolve, reject) => {
          const { sortBy, sortDesc, page, itemsPerPage } = this.options

          let items = this.getDesserts()
          const total = items.length

          if (sortBy.length === 1 && sortDesc.length === 1) {
            items = items.sort((a, b) => {
              const sortA = a[sortBy[0]]
              const sortB = b[sortBy[0]]

              if (sortDesc[0]) {
                if (sortA < sortB) return 1
                if (sortA > sortB) return -1
                return 0
              } else {
                if (sortA < sortB) return -1
                if (sortA > sortB) return 1
                return 0
              }
            })
          }

          if (itemsPerPage > 0) {
            items = items.slice((page - 1) * itemsPerPage, page * itemsPerPage)
          }

          setTimeout(() => {
            resolve({
              items,
              total,
            })
          }, 1000)
        })
        
      },
      editItem (item) {
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },

            deleteItem (item) {
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialogDelete = true
            },

            deleteItemConfirm () {
                this.desserts.splice(this.editedIndex, 1)
                this.closeDelete()
            },

            close () {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            closeDelete () {
                this.dialogDelete = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                })
            },

            save () {
                if (this.editedIndex > -1) {
                    Object.assign(this.desserts[this.editedIndex], this.editedItem)
                } else {
                    this.desserts.push(this.editedItem)
                }
                this.close()
            },
            addNewRow (){
              console.log(this.rowArray);
              // let rowHtml = (<h2>Hello</h2>)
            },
      getDesserts () {
        return [
          {
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%',
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
            iron: '1%',
          },
          {
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
            iron: '7%',
          },
          {
            name: 'Cupcake',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
            iron: '8%',
          },
          {
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
            iron: '16%',
          },
          {
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
            iron: '0%',
          },
          {
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
            iron: '2%',
          },
          {
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
            iron: '45%',
          },
          {
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
            iron: '22%',
          },
          {
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
            iron: '6%',
          },
        ]
      },
    },
}
</script>

<style scoped>

</style>