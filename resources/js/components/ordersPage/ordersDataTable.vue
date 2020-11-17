<template>
    <div>
        <v-data-table
            :headers="headers"
            :items="orders"
            :options.sync="options"
            :server-items-length="totalOrders"
            :loading="loading"
            class="elevation-1"
        >
        <template v-slot:top>
          <v-toolbar
            flat
          >
          <v-dialog
            v-model="dialog"
            max-width="1000px"
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
                  <v-overflow-btn
                    class="my-2"
                    :items="dropdown_edit"
                    label="Items"
                    editable
                    item-value="text"
                  ></v-overflow-btn>
                    <!-- <v-text-field
                      v-model="editedItem.name"
                      label="Items"
                    ></v-text-field> -->
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="2"
                  >
                    <v-text-field
                      v-model="editedItem.quantity"
                      label="Quantity"
                      type="number"
                      min="1"
                    >{{editedItem.quantity}}</v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="2"
                  >
                    <v-text-field
                      label="Price (Rs)"
                      type="number"
                      :value="editedItem.quantity * editedItem.price"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                  <v-select
                    :items="dropdown_edit_status"
                    label="Status"
                    outlined
                  ></v-select>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <v-text-field
                      label="Total"
                      outlined
                      :value="editedItem.price"
                      readonly
                  ></v-text-field>
                  </v-col>
                </v-row>

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
              @click="editItem(item.id)"
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
        </v-data-table>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                dialog: false,
                dialogDelete: false,
                totalOrders: 0,
                rowId: 0,
                orders: [],
                loading: true,
                options: {},
                dropdown_edit: [
                  { text: 'Chicken' },
                  { text: 'Coke' },
                  { text: 'Naan' }
                ],
                dropdown_edit_status: [
                  { text: 'Paid' },
                  { text: 'Unpaid' }
                ],
                headers: [
                    { text: 'Order#', align: 'start', value: 'id'},
                    { text: 'Order Status', value: 'order_status', sortable: false},
                    { text: 'Discount', value: 'order_discount', sortable: false },
                    { text: 'Order Total ', value: 'order_total', sortable: false },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                editedIndex: 0,
                editedItem: {
                  order_total: 0,
                  quantity: 10,
                  price: 2,
                  order_status: '',
                  order_discount: 0,
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
          return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
        },
      },
        mounted(){

        },
        methods: {
            getDataFromApi () {
                this.loading = true;
                this.options['user_id'] = 1;
                let items = this.getOrders (this.options);
            },
            getOrders (order_data) {
                this.axios.post('/get_user_orders', order_data)
                    .then(response => {
                        this.orders = response.data.orders;
                        this.totalOrders = response.data.orders.length;
                        this.loading = false
                        console.log(response.data.orders)
                    }).catch (error => {
                        console.log(error.message)
                })
            },
            getItems () {
              console.log("here" + this.orders[1].item_name)
            },
            editItem (item) {
              this.editedIndex = this.orders.item
              this.editedItem = Object.assign({}, item)
              this.dialog = true
            },
            deleteItem (item) {
              // let id = item;
              // this.dialogDelete = true
              // for(var i =0; i <this.orders.length; i++){
              //   if(this.orders[i].id == id){
              //     this.orders.splice(i, 1);
              //     break;
              //   }
              // }
              this.editedIndex = this.orders.indexOf(item)
              console.log(this.editedIndex)
              this.editedItem = Object.assign({}, item)
              this.dialogDelete = true
              console.log(this.editedIndex)
            },
            closeDelete () {
              console.log(this.editedIndex)
              this.orders.splice(this.editedIndex, 1)

              this.dialogDelete = false
            },
            deleteItemConfirm () {
              this.deleteItem()
              this.closeDelete()
            },
            close () {
              this.dialog = false
          },
            addOrder (){

              this.axios.post('')
            },
            save () {
              if (this.editedIndex > -1) {
                Object.assign(this.orders[this.editedIndex], this.editedItem)
              } else {
                this.orders.push(this.editedItem)
              }
              this.close()
            },
        },
    }
</script>
