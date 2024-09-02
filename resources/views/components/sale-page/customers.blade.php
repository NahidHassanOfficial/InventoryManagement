{{-- customer section --}}
<div class="col-md-4 col-lg-4 p-2">
    <div class="shadow-sm h-100 bg-white rounded-3 p-3">
        <table class="table table-sm w-100" id="customerTable">
            <thead class="w-100">
                <tr class="text-xs text-bold">
                    <td>Customer</td>
                    <td>Pick</td>
                </tr>
            </thead>
            <tbody class="w-100" id="customerList">

            </tbody>
        </table>
    </div>
</div>

<script>
    async function CustomerList() {
        let res = await axios.get("{{ route('customer.data') }}");
        let customerList = $("#customerList");
        let customerTable = $("#customerTable");
        customerTable.DataTable().destroy();
        customerList.empty();

        res.data.forEach(function(item, index) {
            let row = `<tr class="text-xs">
                        <td><i class="bi bi-person"></i> ${item['name']}</td>
                        <td><a data-name="${item['name']}" data-email="${item['email']}" data-phone="${item['phone']}"data-id="${item['id']}" class="addCustomer btn btn-outline-dark text-xxs px-2 py-1 btn-sm m-0">Add</a></td>
                     </tr>`
            customerList.append(row)
        })


        $('.addCustomer').on('click', async function() {

            let name = $(this).data('name');
            let email = $(this).data('email');
            let phone = $(this).data('phone');
            let customer_id = $(this).data('id');

            $("#name").text(name)
            $("#email").text(email)
            $("#phone").text(phone)
            $("#customer_id").text(customer_id)

        })

        new DataTable('#customerTable', {
            order: [
                [0, 'desc']
            ],
            scrollCollapse: false,
            info: false,
            lengthChange: false
        });
    }
</script>
