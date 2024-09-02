@extends('components.layouts.dashboard')
@section('content')
    <div class="container-fluid">
        <div class="row">

            @include('components.sale-page.bill')
            @include('components.sale-page.products')
            @include('components.sale-page.customers')

        </div>
    </div>

    <script>
        (async () => {
            showLoader();
            await CustomerList();
            await ProductList();
            hideLoader();
        })()

        async function createInvoice() {
            let total = document.getElementById('total').innerText;
            let discount = document.getElementById('discount').innerText
            let vat = document.getElementById('vat').innerText
            let payable = document.getElementById('payable').innerText
            let customer_id = document.getElementById('customer_id').innerText;

            let Data = {
                "total": total,
                "discount": discount,
                "vat": vat,
                "payable": payable,
                "customer_id": customer_id,
                "products": InvoiceItemList
            }

            if (customer_id.length === 0) {
                errorToast("Customer Required !")
            } else if (InvoiceItemList.length === 0) {
                errorToast("Product Required !")
            } else {
                showLoader();
                let res = await axios.post("{{ route('invoice.create') }}", Data)
                hideLoader();
                if (res.data['status'] == 'success') {
                    successToast("Invoice Created");
                    window.location.href = '{{ route('invoiceList') }}'
                } else {
                    errorToast("Something Went Wrong")
                }
            }
        }
    </script>
@endsection
