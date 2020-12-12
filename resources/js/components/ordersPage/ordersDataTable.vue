<template>
    <div>
        <v-row>
            <v-col
                cols="12"
                sm="6"
                md="4"
                >
                <v-text-field
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    v-model="searchOrder"
                    @keydown.enter="getDataFromApi"
                    type="number"
                    hint="Enter Order Number"
                    persistent-hint
                ></v-text-field>
            </v-col>
        </v-row>

        <v-data-table
            :headers="headers"
            :items="orders"
            :options.sync="options"
            :server-items-length="totalOrders"
            :loading="loading"
            class="elevation-1"
        >
        <template v-slot:top>
          <v-form  v-model="isFormValid" @submit.prevent ref="form">
          <v-toolbar
            flat
            v-model="rowIndex"
          >
          <v-dialog
            @click:outside="closeDialog"
            v-model="dialog"
            max-width="1000px"
          >
          <template v-slot:activator="{ on, attrs }">
            <v-row>
              <v-col
                  cols="12"
                  sm="6"
                  md="6">
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
              </v-col>
            </v-row>
          </template>
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>

                <v-row v-for="(row, index) in newOrderRow" :key="index" :rules="rowRules">
                  <v-col
                    cols="12"
                    sm="6"
                    md="4"
                  >
                  <v-overflow-btn
                    class="my-2"
                    :items="itemsTable"
                    item-text="item_name"
                    v-model="newOrderRow[index].newItem"
                    label="Items"
                    editable
                    required
                    return-object
                    @change="calculateOrderTotal"
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
                      :rules="quantityRules"
                      hint="KG"
                      persistent-hint
                      required
                     @input="calculateOrderTotal"
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
                      :value="newOrderRow[index].newItem.item_price * newOrderRow[index].quantity"
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
                      hint="Press ENTER after adding Discount"
                      persistent-hint
                      color="green"
                      @keydown.enter="addDiscount"
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
                      :rules="[v=>v>0 || 'Total should not be negative']"
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
                    <v-select
                    :items="dropdown_edit_shift"
                    v-model="selectedShift"
                    label="Shift"
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
                      label="Cash Recieved"
                      type="number"
                      min="0"
                      outlined
                      hint="Press ENTER after adding Cash"
                      persistent-hint
                      color="blue"
                      @keydown.enter="returnCash"
                      v-model="editedItem.cash_recieved"
                  ></v-text-field>
                  </v-col>
                   <v-col
                    cols="12"
                    sm="6"
                    md="3"
                  >
                    <v-text-field
                      label="Cash Return"
                      :rules="[v=>v>=0 || 'Cash Return should not be negative']"
                      type="number"
                      outlined
                      readonly
                      v-model="editedItem.cash_return"
                  ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="2"
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
                :disabled="!isFormValid || !valid || editedItem.order_total === 0"
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
              :disabled="item.order_status === 'Paid'"
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
            <router-link :to="{name: 'print_order', params: {id : item.id}}" target="_blank">
            <v-icon
              color="green"
              large
            >
              mdi-printer
            </v-icon>
            </router-link>
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
                isFormValid: false,
                valid: false,
                totalOrders: 0,
                rowIndex: 0,
                orders: [],
                loading: true,
                searchOrder: null,
                options: {},
                itemsTable: [],
                newOrderRow: [],
                selectedStatus: "Unpaid",
                selectedShift: 'breakfast',
                formTitle: '',
                currentRowId: 0,
                totalWithoutDiscount: 0,
                savedItems: [],
                getEditItems: {},

                dropdown_edit: [],
                dropdown_edit_status: ['Paid', 'Unpaid'],
                dropdown_edit_shift: ['breakfast', 'dinner'],
                headers: [
                    { text: 'Order#', align: 'start', value: 'id'},
                    { text: 'Order Status', value: 'order_status', sortable: false},
                    { text: 'Discount', value: 'order_discount', sortable: false },
                    { text: 'Order Total ', value: 'order_total', sortable: false },
                    { text: 'Shift ', value: 'order_shift', sortable: false },
                    { text: 'Actions', value: 'actions', sortable: false }
                ],
                editedIndex: 0,
                editedItem: {
                  selectedItem: "",
                  quantity: 0,
                  price: 0,
                  order_status: '',
                  order_discount: 0,
                  order_total: 0,
                  cash_recieved: 0,
                  cash_return: 0,
                },
                quantityRules: [
                   (v) => !!v || 'Quantity is required',
                   v =>  v  > 0 || "Quantity should be greater than 0"
                ],
                rowRules: [
                  (v) => v  === null || 'An Item is required'

                ],
                itemSelectRules: [
                  (v) => v !== this.itemsTable || 'Select an Item',
                ]
            }
        },
        computed: {
          getTotal(){
            let total = 0;
            this.newOrderRow.forEach((value,index) => {
            })
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
                this.options['search_order'] = this.searchOrder;
                let items = this.getOrders (this.options);
            },
            getOrders (order_data) {
                this.axios.post('/get_user_orders', order_data)
                    .then(response => {
                      if(!response.data.error){
                        this.orders = response.data.orders;
                        // this.totalOrders = this.orders.length;
                        this.totalOrders = response.data.total_orders;
                        this.loading = false
                        return
                        }
                        this.showErrorAlert(response.data.message);
                    }).catch (error => {
                        this.showErrorAlert(error.message);
                    if (error.response) {
                        this.showErrorAlert(error.response.data.message);
                        if (error.response.status === 401) {
                            localStorage.removeItem('isAuthenticated')
                            localStorage.removeItem('user_data');
                            this.$router.push('/')
                        }
                    }
                })
            },
            getItemTable () {
              this.axios.get('get_all_items/orders').then(response =>{
                this.itemsTable = response.data.data;
                // let self = this;
                // this.itemsTable.forEach(function (item, index) {
                // self.dropdown_edit.push(item['item_name'])
                // })
              })
                  .catch(error => {
                      if (error.response) {
                          if (error.response.status === 401) {
                              localStorage.removeItem('isAuthenticated')
                              localStorage.removeItem('user_data');
                              this.$router.push('/')
                          }
                      }
                  })
            },
            calculateOrderTotal () {
              let total = 0;
              this.newOrderRow.forEach((value,index) => {
                total +=  (value.newItem.item_price * value.quantity)
            })
              this.editedItem.order_total = total;
              this.editedItem.order_discount = 0;
              this.totalWithoutDiscount = total
            },
            selectRules(){
              if(this.newOrderRow == null){
                return "Select an Item"
              }
            },
            addDiscount () {
              this.editedItem.order_total = (this.totalWithoutDiscount - this.editedItem.order_discount);
            },
            returnCash () {
              this.editedItem.cash_return = (this.editedItem.cash_recieved - this.editedItem.order_total);
            },
            addNewRow () {
              this.valid = true;
              this.newOrderRow.push({price: 0, quantity: 0, newItem:{},orderItem_id:0, items: []});
            },
            editItem (item) {
              this.editedIndex = item
              let newItems = [];
              let itemsAtt = []
              this.formTitle = "Edit Order"
              this.currentRowId = this.orders.indexOf(item)
              this.selectedStatus = item.order_status;
              this.editedItem.order_total = item.order_total
              this.axios.post('/get_order', {order_id: item.id}).then(response=>{
                this.getEditItems = response.data.data
                this.getEditItems.forEach((value)=>{
                  this.editedItem.cash_recieved = value.cash_received
                  this.editedItem.cash_return = value.change_returned
                  newItems.push(value.items)
                  newItems.forEach((newValue, index)=>{
                    for(var i=0; i <= response.data.data[0].items.length-1; i++){
                      this.addNewRow(i)
                      itemsAtt = {"item_id": newValue[i].item_id,"item_name": newValue[i].item_name, "item_price": newValue[i].item_price, "quantity": newValue[i].quantity}
                      this.newOrderRow[i].newItem = itemsAtt
                      this.newOrderRow[i].price = itemsAtt.item_price
                      this.newOrderRow[i].quantity = itemsAtt.quantity
                      this.newOrderRow[i].newItem.id = itemsAtt.item_id
                      this.selectedShift = item.order_shift
                      this.calculateOrderTotal()
                      // this.editedItem.order_total = item.order_total
                      // this.totalWithoutDiscount = item.order_total
                      this.editedItem.order_discount = item.order_discount                      
                    }
                  })
                })
              }).catch(error=>{
                  if (error.response) {
                      this.showErrorAlert(error.response.data.message);
                      if (error.response.status === 401) {
                          localStorage.removeItem('isAuthenticated')
                          localStorage.removeItem('user_data');
                          this.$router.push('/')
                      }
                  }
              })

              this.close();
              this.dialog = true
            },
            deleteItem () {
              let id = this.currentRowId;
              for(var i =0; i <=this.orders.length; i++){
                if(this.orders[i].id == id){
                  this.orders.splice(i, 1);
                  this.axios.post('/delete_order', {order_id: id}).then(response=>{
                    if(!response.data.error){
                      this.showSuccessAlert('Order Deleted')
                      return
                    }
                    this.showErrorAlert(response.data.message)
                  }).catch(error=>{
                      if (error.response) {
                          this.showErrorAlert(error.response.data.message);
                          if (error.response.status === 401) {
                              localStorage.removeItem('isAuthenticated')
                              localStorage.removeItem('user_data');
                              this.$router.push('/')
                          }
                      }
                  })
                  break;
                }
              }
              this.closeDelete();
            },
            closeDelete () {
              this.dialogDelete = false
              this.valid = false
            },
            changeFormTitle () {
              this.formTitle = "New Order"
            },
            deleteItemConfirm (item) {
              this.currentRowId = item
              this.dialogDelete = true
            },
            close () {
              this.dialog = false
              this.order_total = null;
              this.editedItem.cash_return = 0;
              this.editedItem.cash_recieved = 0
              this.editedItem.order_total = 0;
              this.editedItem.order_discount = 0;
              this.getEditItems= new Array;
              this.currentRowId = 0;
              this.newOrderRow=[]
              this.selectedShift= 'Breakfaast'
              this.valid = false
              this.resetValidation ()
          },
            resetValidation () {
              this.$refs.form.resetValidation()
            },
            closeDialog () {
              this.close();
            },
            save () {
              if(this.formTitle == "New Order"){
                this.postData();
              }
              else{
                this.postEditedData();
              }
              this.resetValidation ();

              this.close()
            },
            removeRow (index) {
              this.editedItem.order_total = this.editedItem.order_total - (this.newOrderRow[index].newItem.item_price * this.newOrderRow[index].quantity)
              // this.newOrderRow[index].newItem.item_id = 0;
              // this.mewOrderRow[index].quantity = null;
              this.newOrderRow.splice(index, 1)
            },
            getSelectedItems () {
              let items = [];
              this.newOrderRow.forEach((value,index)=>{
                items.push({"item_id": value.newItem.id, "quantity": value.quantity})
              })
              return items
            },
          postData(){
            let items = this.getSelectedItems()
            let shift = this.selectedShift.toLowerCase();
            let Data = {
            "order_total": this.editedItem.order_total,
            "order_status": this.selectedStatus,
            "order_discount": this.editedItem.order_discount,
            "order_shift": shift,
            "user_id": 1,
            "cash_received" : this.editedItem.cash_recieved,
            "change_returned" : this.editedItem.cash_return,
            "items": items
          };
          let url = "/create_new_order"
          this.axios.post(url,Data).then(response =>{
            if(response.data.error == false){
              this.showSuccessAlert('Order Added');
              return
            }
            this.showErrorAlert(response.data.message)
          }).catch(error =>{
              if (error.response) {
                  this.showErrorAlert(error.response.data.message);
                  if (error.response.status === 401) {
                      localStorage.removeItem('isAuthenticated')
                      localStorage.removeItem('user_data');
                      this.$router.push('/')
                  }
              }
          })
          this.getDataFromApi();
        },
        postEditedData(){

          let items = this.getSelectedItems()
          let shift = this.selectedShift.toLowerCase();
          let editData = {
            "order_id": this.editedIndex.id,
            "order_total": this.editedItem.order_total,
            "order_status": this.selectedStatus,
            "order_discount": this.editedItem.order_discount,
            "order_shift": shift,
            "user_id": 1,
            "cash_received" : this.editedItem.cash_recieved,
            "change_returned" : this.editedItem.cash_return,
            "items": items
          }
          let url = "/update_order"
            this.axios.post(url,editData).then(response =>{
              if(!response.data.error){
                this.showSuccessAlert('Order Updated')
                return
              }
            }).catch(error =>{
                if (error.response) {
                    this.showErrorAlert(error.response.data.message);
                    if (error.response.status === 401) {
                        localStorage.removeItem('isAuthenticated')
                        localStorage.removeItem('user_data');
                        this.$router.push('/')
                    }
                }
            })
          this.getDataFromApi();
          this.close();
        },
      }

    }
</script>
