<template>
    <div>
         <v-data-table
            :headers="headers"
            :items="items"
            :options.sync="options"
            :server-items-length="totalItems"
            :loading="loading"
            class="elevation-1"
        >
        <template v-slot:top>
          <v-form ref="form" v-model="isFormValid" lazy-validation>
          <v-toolbar
            flat
          >
          <v-dialog
            @click:outside="closeDialog"
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
              New Item
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
                    md="5"
                  >
                    <v-text-field
                      label="Item-Name"
                      type="text"
                      outlined
                      v-model="itemName"
                      :rules="[(v)=>!!v || 'Item name is required']"
                  ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="5"
                  >
                    <v-text-field
                      label="Item-Price"
                      type="number"
                      outlined
                      :rules="[(v)=>!!v || 'Item price is required', (v)=> v>=1 || 'Price must be greater than 0']"                    
                      v-model="itemPrice"
                  ></v-text-field>
                  </v-col>
                  <v-col
                    cols="12"
                    sm="6"
                    md="2"
                  >
                    <v-text-field
                      label="Quantity"
                      type="number"
                      hint="KG"
                      persistent-hint
                      outlined                 
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
                :disabled="!isFormValid || itemName === '' || itemPrice === 0"
              >
                Save
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
        <v-dialog 
        v-model="dialogDelete"  
        max-width="500px"
        @click:outside="closeDialog"
        >
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
              class="mr-2"
              @click="deleteItemConfirm(item)"
            >
              mdi-delete
            </v-icon>
          </template>
        </v-data-table>
    </div>
</template>

<script>
export default {
    name: 'storeDataTable',
    data () {
            return {
                dialog: false,
                dialogDelete: false,
                isFormValid: false,
                valid: false,
                items: [],
                loading: true,
                options: {},
                formTitle: '',
                itemName: '',
                itemPrice: 0,
                totalItems: 0,
                currentRowId: 0,

                headers: [
                    { text: 'Item#', align: 'start', value: 'id', sortable: false},
                    { text: 'Item-Name', value: 'item_name', sortable: false },
                    { text: 'Quantity', value: 'quantity', sortable: false },
                    { text: 'Item-Price', value: 'item_price', sortable: false },
                    { text: 'Date', value: 'created_at', sortable: false },
                    { text: 'Actions', value: 'actions', sortable: false }
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
        //   this.getItemTable();
        },
        methods: {
            getDataFromApi () {
                this.loading = true;
                this.options['user_id'] = 1;
                let items = this.getOrders (this.options);
            },
            getOrders (order_data) {
                this.axios.get('get_all_items/get_store_items' + this.options.itemsPerPage + '/' + this.options.page)
                    .then(response => {
                        this.items = response.data.data;
                        // this.totalItems = response.data.data.length;
                        this.loading = false
                        console.log(response.data, "orders")
                    }).catch (error => {
                        console.log(error.message)
                })
            },
            editItem (item) {
                this.formTitle = "Edit Item"
                this.currentRowId = item.id
                this.itemName = item.item_name
                this.itemPrice = item.item_price
                console.log(item,"working")
                this.dialog = true
            },
            deleteItemConfirm (item) {
              this.currentRowId = item.id
              this.dialogDelete = true
            },
            deleteItem () {
              let id = this.currentRowId;
              for(var i =0; i <=this.items.length; i++){
                if(this.items[i].id == id){
                  this.items.splice(i, 1);
                  this.axios.post('/delete_item', {item_id: id}).then(response=>{
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
            },
            changeFormTitle () {
              this.formTitle = "New Item"
            },
            close () {
              this.dialog = false
              this.currentRowId = 0;
              this.formTitle = '';
              this.itemName = '';
              this.itemPrice = 0;
          },
            closeDialog () {
              this.close();
            },
            save () {
              if(this.formTitle == "New Order"){
                this.saveNewItem();
              }
              else{
                this.saveEditItem();
              }
              this.close()
            },
            saveEditItem () {
                 let editData = {
                    "item_id": this.currentRowId,
                    "item_name": this.itemName,
                    "item_price": this.itemPrice
                }
                let url = "/update_item"
                this.axios.post( url, editData ).then(response =>{
                    console.log(response);
                }).catch(err=>{
                    console.log(err)
                })
                this.getDataFromApi()
            },
            saveNewItem () {
                let Data = {
                    "item_name": this.itemName,
                    "item_price": this.itemPrice,
                };
                console.log(Data);
                let url = "/create_new_item"
                    this.axios.post(url,Data).then(response =>{
                        console.log(response)
                        }).catch(error =>{
                        console.log(error)
                    })
                this.getDataFromApi()
            },
        }
}
</script>

<style scoped>

</style>