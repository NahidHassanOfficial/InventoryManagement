 {{-- product section --}}
 <div class="col-md-4 col-lg-4 p-2">
     <div class="shadow-sm h-100 bg-white rounded-3 p-3">
         <table class="table  w-100" id="productTable">
             <thead class="w-100">
                 <tr class="text-xs text-bold">
                     <td>Product</td>
                     <td>Pick</td>
                 </tr>
             </thead>
             <tbody class="w-100" id="productList">

             </tbody>
         </table>
     </div>
 </div>

 {{-- productModal --}}
 <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
     <div class="modal-dialog modal-md modal-dialog-centered">
         <div class="modal-content">
             <div class="modal-header">
                 <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
             </div>
             <div class="modal-body">
                 <form id="add-form">
                     <div class="container">
                         <div class="row">
                             <div class="col-12 p-1">
                                 <label class="form-label d-none">Product ID *</label>
                                 <input type="text" class="form-control d-none" id="productID">
                                 <label class="form-label mt-2">Product Name *</label>
                                 <input type="text" class="form-control" id="productName">
                                 <label class="form-label mt-2">Product Price *</label>
                                 <input type="text" class="form-control" id="productPrice">
                                 <label class="form-label mt-2">Product Qty *</label>
                                 <input type="number" class="form-control" id="PQty"
                                     oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="1">
                             </div>
                         </div>
                     </div>
                 </form>
             </div>
             <div class="modal-footer">
                 <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal"
                     aria-label="Close">Close</button>
                 <button onclick="addProduct()" id="save-btn" class="btn bg-gradient-success">Add</button>
             </div>
         </div>
     </div>
 </div>


 <script>
     async function ProductList() {
         let res = await axios.get("{{ route('product.data') }}");
         let productList = $("#productList");
         let productTable = $("#productTable");
         productTable.DataTable().destroy();
         productList.empty();

         res.data.forEach(function(item, index) {
             let row = `<tr class="text-xs">
                        <td> <img class="w-10" src="uploads/products/${item['img_url']}"/> ${item['name']} ($ ${item['price']})</td>
                        <td><a data-name="${item['name']}" data-price="${item['price']}" data-id="${item['id']}" class="addItem btn btn-outline-dark text-xxs px-2 py-1 btn-sm m-0" >Add</a></td>
                     </tr>`
             productList.append(row)
         })

         $('.addItem').on('click', async function() {
             let productName = $(this).data('name');
             let productPrice = $(this).data('price');
             let productID = $(this).data('id');
             modalFill(productID, productName, productPrice)
         })

         new DataTable('#productTable', {
             order: [
                 [0, 'desc']
             ],
             scrollCollapse: false,
             info: false,
             lengthChange: false
         });
     }

     function modalFill(id, name, price) {
         document.getElementById('productID').value = id
         document.getElementById('productName').value = name
         document.getElementById('productPrice').value = price
         $('#create-modal').modal('show')
     }

     function addProduct() {
         let productID = document.getElementById('productID').value;
         let productName = document.getElementById('productName').value;
         let productPrice = document.getElementById('productPrice').value;
         let PQty = document.getElementById('PQty').value;
         let PTotalPrice = (parseFloat(productPrice) * parseFloat(PQty)).toFixed(2);

         if (productID.length === 0) {
             errorToast("Product ID Required");
         } else if (productName.length === 0) {
             errorToast("Product Name Required");
         } else if (productPrice.length === 0) {
             errorToast("Product Price Required");
         } else if (PQty <= 0) {
             errorToast("Product Quantity Required");
         } else {
             let existingItem = InvoiceItemList.find(item => item.product_id === productID);

             if (existingItem) {
                 existingItem.qty = +existingItem.qty + +PQty;
                 existingItem.sale_price = (parseFloat(existingItem.sale_price) + parseFloat(PTotalPrice)).toFixed(2);
             } else {
                 let item = {
                     product_name: productName,
                     product_id: productID,
                     qty: PQty,
                     sale_price: PTotalPrice
                 };
                 InvoiceItemList.push(item);
             }

             $('#create-modal').modal('hide');
             $('#PQty').val('1');
             ShowInvoiceItem();
         }
     }
 </script>
