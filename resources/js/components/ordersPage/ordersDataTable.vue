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
          <v-form ref="form" v-model="valid" lazy-validation>
          <v-toolbar
            flat
            v-model="rowIndex"
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
              @click="changeFormTitle()"
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
                
                <v-row v-for="(row, index) in newOrderRow" :key="index">
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                  <v-overflow-btn
                    class="my-2"
                    :items="dropdown_edit"
                    v-model="newOrderRow[index].newItem"
                    label="Items"
                    editable
                    item-value="text"
                    :rules="[v => !!v || 'Item is required']"
                    required                    
                  ></v-overflow-btn>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="2"
                  >
                    <v-text-field
                      v-model="newOrderRow[index].quantity"
                      label="Quantity"
                      type="number"
                      min="1"
                      @input="addPrice(index)"
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="2"
                  >
                    <v-text-field
                      label="Price (Rs)"
                      type="number"
                      v-model="newOrderRow[index].price"
                      readonly
                    ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                  <v-icon
                    color="red"
                    @click="removeRow(index)">
                    mdi-close-circle
                  </v-icon>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <v-text-field
                      label="Discount"
                      type="number"
                      min="0"
                      outlined
                      @keydown.enter.once="addDiscount"
                      v-model="editedItem.order_discount"
                  ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <v-text-field
                      label="Total"
                      type="number"
                      outlined
                      readonly
                      v-model="editedItem.order_total"
                  ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <v-select
                    :items="dropdown_edit_status"
                    v-model="selectedStatus"
                    label="Status"
                    outlined
                  ></v-select>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <v-btn
                      class="mx-2"
                      fab
                      dark
                      color="indigo"
                      @click="addNewRow"
                    >
                      <v-icon dark>
                        mdi-plus
                      </v-icon>
                    </v-btn>
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
                @click="save(rowIndex)"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog>
          <printTicket/>
        </v-dialog>
        <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="headline">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItem">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
      </v-form>
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
              @click="deleteItemConfirm(item.id)"
            >
              mdi-delete
            </v-icon>
            <v-icon
              color="green"
              large
            >
              mdi-printer
            </v-icon>
          </template>
        </v-data-table>
    </div>
</template>

<script>
  import printTicket from './printTicket'; 
    export default {
      components: {
        printTicket
      },
        data () {
            return {
                dialog: false,
                dialogDelete: false,
                valid: true,
                totalOrders: 0,
                rowIndex: 0,
                orders: [],
                loading: true,
                options: {},
                itemsTable: [],
                newOrderRow: [{}],
                selectedStatus: "",
                formTitle: '',
                formTitle: '',
                currentRowId: 0,
                
                dropdown_edit: [],
                dropdown_edit_status: ['Paid', 'Unpaid'],
                headers: [
                    { text: 'Order#', align: 'start', value: 'id'},
                    { text: 'Order Status', value: 'order_status', sortable: false},
                    { text: 'Discount', value: 'order_discount', sortable: false },
                    { text: 'Order Total ', value: 'order_total', sortable: false },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                editedIndex: -1,
                editedItem: {
                  selectedItem: "",
                  quantity: 0,
                  price: 0,
                  order_status: '',
                  order_discount: 0,
                  order_total: 0,
                },
                itemSelectRules: [
                   v => v = !null || 'Company Name is required',
                ],                  
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
        mounted(){
          this.getItemTable();
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
                        console.log(response.data.orders , "orders")
                    }).catch (error => {
                        console.log(error.message)
                })
            },
            getItemTable () {
              this.axios.get('get_all_items').then(response =>{
                console.log(response.data.data)
                this.itemsTable = response.data.data;
                let self = this;
                this.itemsTable.forEach(function (item, index) {
                self.dropdown_edit.push(item['item_name'])
                })
              })
            },
            addPrice (index) {
              for(var i = 0; i<=this.itemsTable.length;i++){
                if(this.newOrderRow[index].newItem === this.itemsTable[i].item_name){
                  this.newOrderRow[index].price = this.itemsTable[i].item_price * this.newOrderRow[index].quantity;
                  this.newOrderRow[index].orderItem_id = this.itemsTable[i].item_id;
                  console.log(this.newOrderRow[index].newItem)
                  this.editedItem.order_total = this.newOrderRow.reduce(function(a,b){
                    return a+b.price
                  },0)
                  break
                }
              }
            },
            addDiscount () {
              this.editedItem.order_total = (this.editedItem.order_total - this.editedItem.order_discount);
            },
            addNewRow () { 
              this.newOrderRow.push({price: 0, quantity: 0, newItem: '',orderItem_id:0});
            },
            editItem (item) {
              this.formTitle = "Edit Order"
              this.currentRowId = this.orders.indexOf(item)
              this.selectedStatus = item.order_status;
              this.editedItem.order_total = item.order_total
              this.dialog = true
              console.log(item)
            },
            deleteItem () {
              let id = this.currentRowId;
              for(var i =0; i <=this.orders.length; i++){
                if(this.orders[i].id == id){
                  this.orders.splice(i, 1);
                  console.log(id)
                  break;
                }
              }
              this.closeDelete();
            },
            closeDelete () {
              this.dialogDelete = false
            },
            changeFormTitle () {
              this.formTitle = "New Order"
            },
            deleteItemConfirm (item) {
              this.currentRowId = item
              this.dialogDelete = true
            },
            close () {
              this.newOrderRow = new Array;
              this.dialog = false
              this.order_total = null;
              this.editedItem = new Object;
              this.currentRowId = 0;
          },
          getBill () {
            console.log("hi");
          },
            save (rowIndex) {
              if(this.newOrderRow[rowIndex].newItem = !null){
              console.log(this.newOrderRow[rowIndex].quantity)
              this.postData(rowIndex);    
              this.close()
              }
              else{
                console.log('works')
              }
            },
            removeRow (index){
              this.newOrderRow.splice(index, 1)
            },
          postData(rowIndex){
          let Data = {
            "order_total": this.editedItem.order_total,
            "order_status": this.selectedStatus,
            "order_discount": this.editedItem.order_discount,
            "user_id": 1,
            "items": [
              {
                "item_id": this.newOrderRow[rowIndex].id,
                "quantity": this.newOrderRow[rowIndex].quantity
              }
            ]
          };
          console.log(Data);
          let url = "/create_new_order"
          this.axios.post(url,Data).then(response =>{
            console.log(response)
          }).catch(error =>{
            console.log(error);
          })
        }
      }
    }
</script>
