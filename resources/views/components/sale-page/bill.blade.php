 <div class="col-md-4 col-lg-4 p-2">
     <div class="shadow-sm h-100 bg-white rounded-3 p-3">
         <div class="row">
             <div class="col-8">
                 <span class="text-bold text-dark">BILLED TO </span>
                 <p class="text-xs mx-0 my-1">Name: <span id="name"></span> </p>
                 <p class="text-xs mx-0 my-1">Email: <span id="email"></span></p>
                 <p class="text-xs mx-0 my-1">Phone: <span id="phone"></span> </p>
                 <p class="text-xs mx-0 my-1 d-none">Customer ID: <span id="customer_id"></span> </p>
             </div>
             <div class="col-4">
                 <img class="w-50" src="{{ asset('uploads/logo/logo') . $id . '.png' }}">
                 <p class="text-bold mx-0 my-1 text-dark">Invoice </p>
                 <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
             </div>
         </div>
         <hr class="mx-0 my-2 p-0 bg-secondary" />
         <div class="row">
             <div class="col-12">
                 <table class="table w-100" id="invoiceTable">
                     <thead class="w-100">
                         <tr class="text-xs">
                             <td>Name</td>
                             <td>Qty</td>
                             <td>Total</td>
                             <td>Remove</td>
                         </tr>
                     </thead>
                     <tbody class="w-100" id="invoiceList">

                     </tbody>
                 </table>
             </div>
         </div>
         <hr class="mx-0 my-2 p-0 bg-secondary" />
         <div class="row">
             <div class="col-12">
                 <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span
                         id="total"></span></p>
                 <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>
                     <span id="payable"></span>
                 </p>
                 <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>
                     <span id="vat"></span>
                 </p>
                 <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>
                     <span id="discount"></span>
                 </p>
                 <span class="text-xxs">Discount(%):</span>
                 <input onkeydown="return false" value="0" min="0" type="number" step="0.25"
                     onchange="DiscountChange()" class="form-control w-40 " id="discountP" />
                 <p>
                     <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                 </p>
             </div>
             <div class="col-12 p-2">

             </div>

         </div>
     </div>
 </div>


 <script>
     let InvoiceItemList = [];

     function ShowInvoiceItem() {
         let invoiceList = $('#invoiceList');
         invoiceList.empty();
         InvoiceItemList.forEach(function(item, index) {
             let row = `<tr class="text-xs">
                        <td>${item['product_name']}</td>
                        <td>${item['qty']}</td>
                        <td>${item['sale_price']}</td>
                        <td><a data-index="${index}" class="btn remove text-xxs px-2 py-1  btn-sm m-0">Remove</a></td>
                     </tr>`
             invoiceList.append(row)
         })

         CalculateGrandTotal();

         $('.remove').on('click', async function() {
             let index = $(this).data('index');
             removeItem(index);
         })

     }

     function removeItem(index) {
         InvoiceItemList.splice(index, 1);
         ShowInvoiceItem()
     }

     function DiscountChange() {
         CalculateGrandTotal();
     }

     function CalculateGrandTotal() {
         let Total = 0;
         let Vat = 0;
         let Payable = 0;
         let Discount = 0;
         let discountPercentage = (parseFloat(document.getElementById('discountP').value));

         InvoiceItemList.forEach((item, index) => {
             Total = Total + parseFloat(item['sale_price'])
         })

         if (discountPercentage === 0) {
             Vat = ((Total * 5) / 100).toFixed(2);
         } else {
             Discount = ((Total * discountPercentage) / 100).toFixed(2);
             Total = (Total - ((Total * discountPercentage) / 100)).toFixed(2);
             Vat = ((Total * 5) / 100).toFixed(2);
         }

         Payable = (parseFloat(Total) + parseFloat(Vat)).toFixed(2);

         document.getElementById('total').innerText = Total;
         document.getElementById('payable').innerText = Payable;
         document.getElementById('vat').innerText = Vat;
         document.getElementById('discount').innerText = Discount;
     }
 </script>
