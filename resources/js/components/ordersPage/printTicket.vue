<template>
    <div v-if="isDataLoaded">
        <div class="ticket" id="print">
            <img :src=" baseUrl == 'http://shahjeerestaurants.com' ? baseUrl + '/public/images/logo.png' : 'images/logo.png' " alt="Logo">
            <p class="centered">Shahjee Restaurant
                <br>Adda Plot, Main Raiwind Road, Lahore
            </p>
            <v-row class="centered order_date">
                <v-col md="6" class="pull-left">Order# {{orderData.id}}</v-col>
                <v-col md="6" class="pull-right">{{orderDate}}</v-col>
            </v-row>
            <v-row class="centered order_date" style="margin-top: -20px">
                <v-col md="6" class="pull-left">Table# {{orderData.table_name}}</v-col>
                <v-col md="6" class="pull-right">{{orderTime}}</v-col>
            </v-row>
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
                        <td class="price">Rs {{parseFloat(orderData.order_total) + parseFloat(orderData.order_discount)}}</td>
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
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">Received: </td>
                        <td class="price">Rs {{orderData.cash_received}}</td>
                    </tr>
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">Returned: </td>
                        <td class="price">Rs {{orderData.change_returned}}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <p class="centered">Thank You for your visit!<br>ShahJee Restaurant</p>
            <p class="centered">0320-4647275 / 0311-4170475</p>
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
            baseUrl : '',
            orderDate : '',
            orderTime : ''
        }
    },
    mounted(){
        this.getOrderData();
        this.baseUrl = this.getBaseUrl();
    },
    methods: {
        getOrderData(){
            this.axios.post('/get_order', {"order_id" : this.$route.params.id})
                .then (resp => {
                    this.isDataLoaded = true;
                    if (!resp.data.error) {
                        this.orderData = resp.data.data[0];
                        // this.orderDate = new Date(resp.data.data[0].created_at).toLocaleString();
                        let timestamp  = new Date(resp.data.data[0].created_at);
                        this.orderDate = timestamp.getDate() + '/'  + timestamp.getMonth() + '/' + timestamp.getFullYear();
                        let hours = timestamp.getHours();
                        let minutes = timestamp.getMinutes();
                        let ampm = hours >= 12 ? 'PM' : 'AM';
                        hours = hours % 12;
                        hours = hours ? hours : 12; // the hour '0' should be '12'
                        minutes = minutes < 10 ? '0'+minutes : minutes;
                        this.orderTime = hours + ':' + minutes + ':' + timestamp.getSeconds() + ' ' + ampm;
                        // this.orderTime = timestamp.getHours() + ':' + timestamp.getMinutes() + ':' + timestamp.getSeconds() + ' ' + (timestamp.getHours() > 12 ? 'PM' : 'AM');
                    }
                })
                .catch (err => {
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
    color: black;
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
    align-items: start !important;
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
    text-align: center !important;
    align-content: center !important;
}

.ticket {
    width: 155px;
    max-width: 155px;
    color: black;
    font-weight:400;
    background-color: white;
}

img {
    max-width: 100px;
    width: inherit;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

.order_date {
    margin-left : 2px;
    margin-right : 2px;
}

@media print {
    .hidden-print,
    .hidden-print * {
        display: none !important;
    }
}
</style>
