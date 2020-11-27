<template>
    <div v-if="isDataLoaded">
        <div class="ticket" id="print">
            <img  src="../../../images/logo.png" alt="Logo">
            <p class="centered">Shahjee Restaurants
                <br>Adda Plot, Main Raiwind Road, Lahore
                <br>Order# {{orderData.id}}</p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Q.</th>
                        <th class="name">Description</th>
                        <th class="price">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item,index) in orderData.items" :key="index">
                        <td class="quantity">{{item.quantity}}</td>
                        <td class="name">{{item.item_name}}</td>
                        <td class="price">Rs {{item.item_price * item.quantity}}</td>
                    </tr>
                    <tr v-if="orderData.order_discount > 0">
                        <td class="quantity"></td>
                        <td class="description">Gross Bill </td>
                        <td class="price">Rs {{orderData.order_total + orderData.order_discount}}</td>
                    </tr>
                    <tr v-if="orderData.order_discount > 0">
                        <td class="quantity"></td>
                        <td class="description">Discount </td>
                        <td class="price">Rs {{orderData.order_discount}}</td>
                    </tr>
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">TOTAL: </td>
                        <td class="price">Rs {{orderData.order_total}}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p class="centered">Thank You for your visit!<br>ShahJee Restaurant</p>
        </div>
        <button id="btnPrint" class="hidden-print btn btn-info" @click="printTicket()">Print</button>
    </div>
    <div v-else>
        <h1>No Data Found!</h1>
    </div>
</template>

<script>
export default {
    name: 'printTicket',
    data(){
        return{
            orderData : {},
            isDataLoaded : false,
            orderTotalWithDiscount : 0,
            orderTotalWithoutDiscount: 0,
        }
    },
    mounted(){
        this.getOrderData();
    },
    methods: {
        getOrderData(){
            this.axios.post('/get_order', {"order_id" : this.$route.params.id})
                .then (resp => {
                    this.isDataLoaded = true;
                    if (!resp.data.error) {
                        this.orderData = resp.data.data[0];
                    }
                })
                .catch (err => {
                    this.showErrorAlert(err.message)
                })
        },
        updateTotal(){
            let total = 0;
            this.orderData.forEach((value,index) => {
            total +=  (value.newItem.item_price * value.quantity)                
        })
            this.orderTotalWithDiscount = total
            this.totalWithoutDiscount = total            
        },
        addDiscount () {
            this.orderTotalWithDiscount = (this.orderTotalWithoutDiscount - this.orderData.order_discount);
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
    margin-left: auto;
    margin-right: auto;
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
    /* word-break: break-all; */
}

td.price,
th.price {
    width: 40px;
    max-width: 40px;
    /* word-break: break-all; */
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
    max-width: 100px;
    width: inherit;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

@media print {
    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}
</style>
