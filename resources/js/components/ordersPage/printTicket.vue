<template>
    <div>
        <div class="ticket" id="print">
            <p class="centered">RECEIPT EXAMPLE
                <br>Address line 1
                <br>{{item.id}}</p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Q.</th>
                        <th class="name">Description</th>
                        <th class="price">Rs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(items,index) in numberOfItems" :key="index">
                        <td class="quantity">{{itemQuantity}}</td>
                        <td class="name">{{itemName}}</td>
                        <td class="price">Rs{{itemPrice}}</td>
                    </tr>
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">TOTAL</td>
                        <td class="price">Rs{{totalPrice}}</td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">Thanks for your purchase!<br>ShahJee Restuerent</p>
        </div>
        <button id="btnPrint" class="hidden-print" @click="printTicket()">Print</button>
    </div>
</template>

<script>
export default {
    name: 'printTicket',
    props: ['item'],
    data(){
        return{
            numberOfItems: [],
            itemsTemplate:[],
            itemQuantity: 2,
            itemName: 'Chicken Karahi',
            itemPrice: 0,
            totalPrice: 0,
            discount: 0,
        }
    },
    mounted(){
        this.getOrderItems(this.item)
    },
    methods: {
        getOrderItems(item){
            this.itemsTemplate.push({newItem: {}, price: 0, quantity: 0})
            let newItems = [];
              let itemsAtt = []
              this.axios.post('/get_order', {order_id: item.id}).then(response=>{
                this.getEditItems = response.data.data
                console.log(response.data.data, 'res')
                this.numberOfItems.forEach((value)=>{            
                  newItems.push(value.items)
                  newItems.forEach((newValue, index)=>{
                    for(var i=0; i<=newItems.length; i++){
                    itemsAtt = {"item_name": newValue[i].item_name, "item_price": newValue[i].item_price, "quantity": newValue[i].quantity}
                    this.itemsTemplate[i].newItem = itemsAtt
                    this.itemsTemplate[i].price = itemsAtt.item_price
                    this.itemsTemplate[i].quantity = itemsAtt.quantity
                    this.totalPrice= item.order_total
                    this.discount = item.order_discount
                    }
                  })
                })                    
              }).catch(error=>{
                console.log(error)
              })
        },
        printTicket(){
            const prtHtml = document.getElementById('print').innerHTML;
            // Get all stylesheets HTML
            let stylesHtml = '';
            for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
                stylesHtml += node.outerHTML;
            }

            // Open the print window
            const WinPrint = window.open();

            WinPrint.document.write(`<!DOCTYPE html>
            <html>
                <head>
                    ${stylesHtml}
                </head>
                <body>
                    ${prtHtml}
                </body>
            </html>`);

            WinPrint.document.close();
            WinPrint.focus();
            WinPrint.print();
            // WinPrint.close();
        }
    }
}
</script>

<style scoped>
    * {
    font-size: 12px;
    font-family: 'Times New Roman';
    font-weight: bolder;
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
    align-items: center !important;
    margin-left: 60px;
}

td.description,
th.description {
    width: 75px;
    max-width: 75px;
}

td.quantity,
th.quantity {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

td.price,
th.price {
    width: 40px;
    max-width: 40px;
    word-break: break-all;
}

.centered {
    text-align: center;
    align-content: center;
}

.ticket {
    width: 155px;
    max-width: 155px;
}

img {
    max-width: inherit;
    width: inherit;
}

@media print {
    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}
</style>