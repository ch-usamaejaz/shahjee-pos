<template>
    <div>
        <div class="ticket" id="print">
            <p class="centered">RECEIPT EXAMPLE
                <br>Address line 1
                <br>Address line 2</p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Q.</th>
                        <th class="description">Description</th>
                        <th class="price">Rs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="quantity">{{itemQuantity}}</td>
                        <td class="description">{{itemName}}</td>
                        <td class="price">Rs{{itemPrice}}</td>
                    </tr>
                    <tr>
                        <td class="quantity">2.00</td>
                        <td class="description">JAVASCRIPT BOOK</td>
                        <td class="price">$10.00</td>
                    </tr>
                    <tr>
                        <td class="quantity">1.00</td>
                        <td class="description">STICKER PACK</td>
                        <td class="price">$10.00</td>
                    </tr>
                    <tr>
                        <td class="quantity"></td>
                        <td class="description">TOTAL</td>
                        <td class="price">$55.00</td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">Thanks for your purchase!
                <br>ShahJee Restuerent</p>
        </div>
        <button id="btnPrint" class="hidden-print" @click="printTicket()">Print</button>
    </div>
</template>

<script>
export default {
    name: 'printButton',
    data(){
        return{
            itemQuantity: 0,
            itemName: '',
            itemPrice: 0,
            totalPrice: 0
        }
    },
    methods: {
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
}

td,
th,
tr,
table {
    border-top: 1px solid black;
    border-collapse: collapse;
    align-items: center;
    margin-left: 160px;
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