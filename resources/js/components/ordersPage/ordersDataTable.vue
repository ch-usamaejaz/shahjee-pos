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
          <v-form ref="form" v-model="isFormValid" lazy-validation>
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
                options: {},
                itemsTable: [],
                newOrderRow: [],
                selectedStatus: "",
                formTitle: '',
                currentRowId: 0,
                totalWithoutDiscount: 0,
                savedItems: [],
                getEditItems: {},

                dropdown_edit: [],
                dropdown_edit_status: ['Paid', 'Unpaid'],
                headers: [
                    { text: 'Order#', align: 'start', value: 'id'},
                    { text: 'Order Status', value: 'order_status', sortable: false},
                    { text: 'Discount', value: 'order_discount', sortable: false },
                    { text: 'Order Total ', value: 'order_total', sortable: false },
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
                },
                quantityRules: [
                   (v) => !!v || 'Quantity is required',
                   v =>  v  >= 1 || "Quantity should be atleast 1"
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
              console.log(value,index, "total")
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
              this.axios.get('get_all_items/orders').then(response =>{
                console.log('new',response.data.data)
                this.itemsTable = response.data.data;
                // let self = this;
                // this.itemsTable.forEach(function (item, index) {
                // self.dropdown_edit.push(item['item_name'])
                // })
              })
              // console.log(this.itemsTable, 'table')
            },
            calculateOrderTotal () {
              let total = 0;
              this.newOrderRow.forEach((value,index) => {
                total +=  (value.newItem.item_price * value.quantity)
                console.log(value, 'inside total')
            })
            this.totalWithoutDiscount = total
            this.editedItem.order_total = total;
            console.log(this.totalWithoutDiscount, 'newTotal')
            },
            addPrice (index) {
              console.log(this.newOrderRow[index], 'this')
              for(var i = 0; i<=this.itemsTable.length;i++){
                if(this.newOrderRow[index].newItem === this.itemsTable[i].item_name){
                  this.savedItems.push({newItemId: this.itemsTable[i].id, newItemQuantity: this.newOrderRow[index].quantity})
                  // this.newOrderRow[index].price = this.itemsTable[i].item_price * this.newOrderRow[index].quantity;
                  this.newOrderRow[index].orderItem_id = this.itemsTable[i].id;
                   console.log(this.itemsTable[i].id, 'itemID')
                   this.rowIndex = index
                   console.log(this.newOrderRow)
                  this.editedItem.order_total = this.newOrderRow.reduce(function(a,b){
                    return a+b.price
                  },0)
                  break
                }

              }
            },
            selectRules(){
              if(this.newOrderRow == null){
                return "Select an Item"
              }
            },
            addDiscount () {
              console.log("working")
              this.editedItem.order_total = (this.editedItem.order_total - this.editedItem.order_discount);
              console.log(this.editedItem.order_total, 'totallll')
            },
            addNewRow () {
              this.valid = true;
              // for(var i=0; i <= this.newOrderRow.length; i++){
              //   if()
              // }
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
                console.log(response.data.data, 'res')
                this.getEditItems.forEach((value)=>{
                  newItems.push(value.items)
                  newItems.forEach((newValue, index)=>{
                    for(var i=0; i<=newItems.length; i++){
                      this.addNewRow(i)
                      itemsAtt = {"item_id": newValue[i].item_id,"item_name": newValue[i].item_name, "item_price": newValue[i].item_price, "quantity": newValue[i].quantity}
                      this.newOrderRow[i].newItem = itemsAtt
                      this.newOrderRow[i].price = itemsAtt.item_price
                      this.newOrderRow[i].quantity = itemsAtt.quantity
                      this.newOrderRow[i].newItem.id = itemsAtt.item_id
                      this.editedItem.order_total = item.order_total
                      this.editedItem.order_discount = item.order_discount
                      console.log(this.newOrderRow[i].newItem.id, 'neeeeeeeee')
                    }
                  })
                })
              }).catch(error=>{
                console.log(error)
              })
              this.close();
              this.dialog = true
            },
            deleteItem () {
              let id = this.currentRowId;
              for(var i =0; i <=this.orders.length; i++){
                if(this.orders[i].id == id){
                  this.orders.splice(i, 1);
                  console.log(id)
                  this.axios.post('/delete_order', {order_id: id}).then(response=>{
                    console.log(response)
                  }).catch(error=>{
                    console.log(error)
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
              this.editedItem = new Object;
              this.currentRowId = 0;
              this.newOrderRow=[]
              this.valid = false
          },
          getBill () {
            console.log("hi");
          },
            save () {
            console.log(this.formTitle)
              if(this.formTitle == "New Order"){
                this.postData();
              }
              else{
                this.postEditedData();
              }
              this.close()
            },
            removeRow (index){
              this.editedItem.order_total = this.editedItem.order_total - (this.newOrderRow[index].newItem.item_price * this.newOrderRow[index].quantity)
              this.newOrderRow.splice(index, 1)
            },
            getSelectedItems () {
              let items = [];
              this.newOrderRow.forEach((value,index)=>{
                items.push({"item_id": value.newItem.id, "quantity": value.quantity})
              })
              console.log(items)
              return items
            },
          postData(){
            console.log(this.savedItems, 'post')
            let items = this.getSelectedItems()
          let Data = {
            "order_total": this.editedItem.order_total,
            "order_status": this.selectedStatus,
            "order_discount": this.editedItem.order_discount,
            "user_id": 1,
            "items": items
          };
          console.log(Data);
          let url = "/create_new_order"
          this.axios.post(url,Data).then(response =>{
            console.log(response)
          }).catch(error =>{
            console.log(error);
          })
        },
        postEditedData(){

          let items = this.getSelectedItems()
          let editData = {
            "order_id": this.editedIndex.id,
            "order_total": this.editedItem.order_total,
            "order_status": this.selectedStatus,
            "order_discount": this.editedItem.order_discount,
            "user_id": 1,
            "items": items
          }
          let url = "/update_order"
            this.axios.post(url,editData).then(response =>{
              console.log(response)
            }).catch(error =>{
              console.log(error);
            })
          this.close();
        },
      }

    }
</script>
